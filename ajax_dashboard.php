<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $USER;

$action = required_param('action', PARAM_TEXT);

require_login($COURSE->id);
require_capability('block/exaquest:seedashboardtab', context_course::instance($COURSE->id));

switch ($action) {
    case ('mark_request_as_done'):
        $requestid = required_param('requestid', PARAM_INT);
        $DB->delete_records(BLOCK_EXAQUEST_DB_REQUESTQUEST, array('id' => $requestid));
        break;
}
