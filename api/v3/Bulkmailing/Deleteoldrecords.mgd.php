<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 =>
  array (
    'name' => 'Delete Old Bulk Mailings',
    'entity' => 'Job',
    'params' =>
    array (
      'version' => 3,
      'name' => 'Delete Old Bulk Mailings',
      'description' => 'Delete Old Bulk Mailings and Activities',
      'run_frequency' => 'Daily',
      'api_entity' => 'Bulkmailing',
      'api_action' => 'Deleteoldrecords',
      'parameters' => "limit=1\nmailing_ids=[comma separated mailing ids]\ndelivered_date_before=yyyy-mm-dd",
    ),
  ),
);
