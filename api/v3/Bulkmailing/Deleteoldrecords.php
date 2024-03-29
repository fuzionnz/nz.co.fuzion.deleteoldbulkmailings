<?php
/**
 * Bulkmailing.Deleteoldrecords API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_bulkmailing_Deleteoldrecords($params) {
  $isEnabled = Civi::settings()->get('logging');
  $logging = new CRM_Logging_Schema();
  if ($isEnabled) {
    $logging->disableLogging();
  }
  CRM_BulkMailing_BAO_Delete::delete($params);
  if ($isEnabled) {
    $logging->enableLogging();
  }
  // Spec: civicrm_api3_create_success($values = 1, $params = array(), $entity = NULL, $action = NULL)
  return civicrm_api3_create_success(array(), $params, 'BulkMailing', 'DeleteOldRecords');
}
