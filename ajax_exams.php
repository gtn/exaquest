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
    case ('send_exam_to_review'):
        // dont instantly change the status
        // instead: do the same as when checking the todoo in the dashboard. When every assigned pm and the fp have checked this --> change status to "created"
        $quizassignid = $DB->get_field(BLOCK_EXAQUEST_DB_QUIZASSIGN, 'id', array(
                        'quizid' => $quizid,
                        'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS,
                        'assigneeid' => $USER->id)
        );

        //$DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN, array('id' => $quizassignid));
        $DB->set_field(BLOCK_EXAQUEST_DB_QUIZASSIGN, 'done', 1, array('id' => $quizassignid));

        // check if every assignment of this kind is done for this quiz
        block_exaquest_check_if_exam_is_ready($quizid);

        //block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
        // remove entries in exaquestquizassign
        //$DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN, ['quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS]);
        break;
    case ('force_send_exam_to_review'):
        // PK kann Prüfung auch zur Freigabe schicken, wenn Prüfung fertig (alle Fragen zugewiesen), aber noch nicht
        //alle ToDos auf "erledigt" gestellt wurden
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
        break;
    case ('skipandrelease_exam'):
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE);
        // remove entries in exaquestquizassign, as they don't make sense anymore (set status should actually already do this..)
        $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN, ['quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS]);
        //$DB->set_field(BLOCK_EXAQUEST_DB_QUIZASSIGN, 'done', 1, array('quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS));
        break;
    //case ('assign_fp_and_pmw'):
    //    block_exaquest_assign_quiz_addquestions($quizid, BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS);
    //    break;
    case ('fachlich_release_exam'):
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED);
        break;
    case ('request_revision'):
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
        $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN, array('id' => $quizassignid)); // don't set the assignments to done, but DELETE them, as they do NOT make sense anymore
        // check if every assignment of this kind is done for this quiz
        //block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED_BY_FP);
        // only mark the todoo for the FP as done, do NOT release instantly, but first check if the BMWs have also done their part
        // check if the exam is now completely released
        block_exaquest_check_if_grades_should_be_released($quizid);
        break;
}
