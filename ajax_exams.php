<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $USER;

$action = required_param('action', PARAM_TEXT);
$quizid = required_param('quizid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

require_login($courseid);
$context = context_course::instance($COURSE->id);
require_capability('block/exaquest:viewexamstab', $context);

switch ($action) {
    case ('create_exam'):
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
        // remove entries in exaquestquizassign
        $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN, ['quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS]);
        break;
    case ('skipandrelease_exam'):
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE);
        // remove entries in exaquestquizassign
        $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN, ['quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS]);
        break;
    //case ('assign_fp_and_pmw'):
    //    block_exaquest_assign_quiz_addquestions($quizid, BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS);
    //    break;
    case ('fachlich_release_exam'):
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED);
        break;
    case ('request_revision'):
        //TODO: do something else than only revert it to created?
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_NEW);
        break;
    case ('formal_release_exam'):
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE);
        break;
    //case ('release_exam'):
    //    block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE);
    //    break;
    case ('finish_exam'):
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_FINISHED);
        break;
    case ('release_grades'):
        // same as mark_check_exam_grading_request_as_done in ajax_dashboard.php
        // we do not have the quizassignid here, so we need to get it first
        $quizassignid = $DB->get_field(BLOCK_EXAQUEST_DB_QUIZASSIGN, 'id', array(
                        'quizid' => $quizid,
                        'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHECK_EXAM_GRADING,
                        'assigneeid' => $USER->id)
        );
        $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN, array('id' => $quizassignid));
        // check if every assignment of this kind is done for this quiz
        //block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED_BY_FP);
        // only mark the todoo for the FP as done, do NOT release instantly, but first check if the BMWs have also done their part
        // check if the exam is now completely released
        block_exaquest_check_if_grades_should_be_released($quizid);
        break;
}
