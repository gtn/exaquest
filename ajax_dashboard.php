<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $USER;

$action = required_param('action', PARAM_TEXT);
$courseid = required_param('courseid', PARAM_INT);
require_login($courseid);

switch ($action) {
    case ('mark_exam_request_as_done'):
        $requestid = required_param('requestid', PARAM_INT);
        $DB->delete_records(BLOCK_EXAQUEST_DB_REQUESTEXAM, array('id' => $requestid));
        break;
    case ('mark_question_request_as_done'):
        $requestid = required_param('requestid', PARAM_INT);
        $DB->delete_records(BLOCK_EXAQUEST_DB_REQUESTQUEST, array('id' => $requestid));
        break;
}
