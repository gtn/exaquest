<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $USER;

$action = required_param('action', PARAM_TEXT);

switch ($action) {
    case ('mark_request_as_done'):
        $requestid = required_param('requestid', PARAM_INT);
        // TODO mark request as done
        $DB->delete_records(BLOCK_EXAQUEST_DB_REQUESTQUEST, array('id' => $requestid));
        break;
}
