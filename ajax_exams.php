<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $USER;

$action = required_param('action', PARAM_TEXT);
$quizid = required_param('quizid', PARAM_INT);


require_login($COURSE->id);
require_capability('block/exaquest:viewexamstab', context_course::instance($COURSE->id));

switch ($action) {
    case ('create_exam'):
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
        break;
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