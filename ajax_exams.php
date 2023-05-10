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
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_FORMAL_RELEASED);
        break;
    case ('release_exam'):
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE);
        break;
    case ('finish_exam'):
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_FINISHED);
        break;
    case ('release_grades'):
        //TODO ACTUALLY release the grades... and maybe task for when they release it to also change status
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED);
        break;
}