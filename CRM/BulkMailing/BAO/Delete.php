<?php

class CRM_BulkMailing_BAO_Delete {

  /**
   * Delete older bulk mailing records
   */
  public static function delete($params) {
    $mailingIds = self::getMailingIdsFromParams($params);
    if (empty($mailingIds)) {
      return;
    }

    foreach ($mailingIds as $mailingId) {
      if (empty($mailingId) || !is_numeric($mailingId)) {
        continue;
      }
      $mailingJob = civicrm_api3('MailingJob', 'get', array(
        'sequential' => 1,
        'return' => array("id"),
        'mailing_id' => $mailingId,
        'options' => array('limit' => 0),
      ));
      $mailingJobIds = CRM_Utils_Array::collect('id', $mailingJob['values']);
      foreach ($mailingJobIds as $jobId) {
        $eventQueue = civicrm_api3('MailingEventQueue', 'get', array(
          'sequential' => 1,
          'return' => array("id"),
          'job_id' => $jobId,
        ));
        $mailingEventQueueIds = CRM_Utils_Array::collect('id', $eventQueue['values']);
        self::deleteEventQueueRecords($mailingEventQueueIds);
        self::deleteMailingJobRecords($jobId);
      }
      self::deleteRecordsOfMailingId($mailingId);
    }
  }

  /**
   * Get Mailing id array
   *
   * @param integer $mailingId
   */
  public static function getMailingIdsFromParams($params) {
    $limit = empty($params['limit']) ? 0 : $params['limit'];
    if (!empty($params['mailing_ids'])) {
      $mailingIds = explode(',', $params['mailing_ids']);
    }
    elseif (!empty($params['delivered_date_before'])) {
      $mailing = civicrm_api3('Mailing', 'get', array(
        'sequential' => 1,
        'return' => array("id"),
        'scheduled_date' => array('<' => $params['delivered_date_before']),
        'options' => array('limit' => $limit),
      ));
      if (!empty($mailing['count'])) {
        $mailingIds = CRM_Utils_Array::collect('id', $mailing['values']);
      }
    }
    else {
      throw new API_Exception('Unknown Parameters', 1234);
    }
    return $mailingIds;
  }

  /**
   * Delete records from mailing id
   * @param integer $mailingId
   */
  public static function deleteRecordsOfMailingId($mailingId) {
    if (empty($mailingId)) {
      return;
    }
    $tablesToDeleteFrom = array(
      'civicrm_mailing_trackable_url',
      'civicrm_mailing_recipients',
    );
    civicrm_api3('Mailing', 'delete', array(
      'id' => $mailingId,
    ));
    foreach ($tablesToDeleteFrom as $tableName) {
      CRM_Core_DAO::executeQuery("DELETE FROM {$tableName} WHERE mailing_id = {$mailingId}");
    }

    $activities = civicrm_api3('Activity', 'get', array(
      'sequential' => 1,
      'return' => array("id"),
      'activity_type_id' => "Bulk Email",
      'source_record_id' => $mailingId,
      'options' => array('limit' => 0),
    ));
    if (empty($activities['count'])) {
      return;
    }
    foreach ($activities['values'] as $activity) {
      civicrm_api3('Activity', 'delete', array(
        'id' => $activity['id'],
      ));
    }
  }

  /**
   * Delete records from event queue id.
   * @param integer $jobId
   */
  public static function deleteMailingJobRecords($jobId) {
    if (empty($jobId)) {
      return;
    }
    $mailingJob = civicrm_api3('MailingJob', 'get', array(
      'id' => $jobId,
    ));
    if (empty($mailingJob['count'])) {
      return;
    }
    civicrm_api3('MailingJob', 'delete', array(
      'id' => $jobId,
    ));
    CRM_Core_DAO::executeQuery("DELETE FROM civicrm_mailing_spool WHERE job_id = {$jobId}");
  }

  /**
   * Delete records from event queue id
   * @param array $queueIds
   *
   */
  public static function deleteEventQueueRecords($queueIds) {
    if (empty($queueIds)) {
      return;
    }
    $tablesToDeleteFrom = array(
      'civicrm_mailing_event_trackable_url_open',
      'civicrm_mailing_event_reply',
      'civicrm_mailing_event_opened',
      'civicrm_mailing_event_forward',
      'civicrm_mailing_event_bounce',
      'civicrm_mailing_event_delivered',
      'civicrm_mailing_event_unsubscribe'
    );
    foreach ($queueIds as $queueId) {
      civicrm_api3('MailingEventQueue', 'delete', array(
        'id' => $queueId,
      ));
      foreach ($tablesToDeleteFrom as $tableName) {
        CRM_Core_DAO::executeQuery("DELETE FROM {$tableName} WHERE event_queue_id = {$queueId}");
      }
    }
  }

}
