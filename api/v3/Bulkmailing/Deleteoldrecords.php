<?php
/**
 *
 */
function _civicrm_api3_bulkmailing_Deleteoldrecords_spec(&$spec) {
  $spec['limit'] = array(
    'name' => 'limit',
    'title' => 'Limit number of mailings deleted.',
    'api.required' => 0,
    'type' => 1,
  );
}

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
  $logging = new CRM_Logging_Schema();
  // $logging->disableLogging();
  CRM_BulkMailing_BAO_Delete::delete($params);
  // $logging->enableLogging();
  // Spec: civicrm_api3_create_success($values = 1, $params = array(), $entity = NULL, $action = NULL)
  return civicrm_api3_create_success(array(), $params, 'BulkMailing', 'DeleteOldRecords');
}
