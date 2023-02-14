<?php

require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $USER;
require_once($CFG->dirroot . '/comment/lib.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');

$questionbankentryid = required_param('questionbankentryid', PARAM_INT);
$questionid = required_param('questionid', PARAM_INT);
$action = required_param('action', PARAM_TEXT);
$courseid = required_param('courseid', PARAM_INT);
$users = optional_param('users', null, PARAM_RAW);
$commenttext = optional_param('commenttext', null, PARAM_TEXT);
$quizid = optional_param('quizid', null, PARAM_INT);

require_login($courseid);
require_capability('block/exaquest:seequestionbanktab', context_course::instance($courseid));

switch ($action) {
    case ('open_question_for_review'):
        //$DB->record_exists('block_exaquestquestionstatus', array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->questionbankentryid = $questionbankentryid;
        $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS;
        $data->id = $DB->get_field('block_exaquestquestionstatus', 'id', array("questionbankentryid" => $questionbankentryid));
        $DB->update_record('block_exaquestquestionstatus', $data);
        $questionname = $DB->get_record('question', array('id' => $questionid))->name;
        $catAndCont = get_question_category_and_context_of_course($courseid);
        if ($users != null) {
            foreach ($users as $user) {
                block_exaquest_request_review($USER, $user, $commenttext, $questionbankentryid, $questionname, $catAndCont,
                    $courseid);
            }
        }
        if ($commenttext != null) {
            $args = new stdClass;
            $args->contextid = 1;
            $args->course = $courseid;
            $args->area = 'question';
            $args->itemid = $questionid;
            $args->component = 'qbank_comment';
            $args->linktext = get_string('commentheader', 'qbank_comment');
            $args->notoggle = true;
            $args->autostart = true;
            $args->displaycancel = false;
            $comment = new comment($args);
            $comment->add($commenttext);
        }
        break;
    case ('formal_review_done'):
        //$DB->record_exists('block_exaquestquestionstatus', array("questionbankentryid" => $questionbankentryid))
        $record = $DB->get_record('block_exaquestquestionstatus', array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->id = $record->id;
        $data->questionbankentryid = $questionbankentryid;
        if ($record->status == BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE) {
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED;
            // delete the entry in exaquestreviewassign, since no review has to be done anymore once it is finalized. If it somehow gets reassigned again, a new entry has to be created
            $DB->delete_records(BLOCK_EXAQUEST_DB_REVIEWASSIGN, ['questionbankentryid' => $questionbankentryid]);
        } else {
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE;
        }
        $DB->update_record('block_exaquestquestionstatus', $data);
        break;
    case ('technical_review_done'):
        $record = $DB->get_record('block_exaquestquestionstatus', array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->id = $record->id;
        $data->questionbankentryid = $questionbankentryid;
        if ($record->status == BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE) {
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED;
            // delete the entry in exaquestreviewassign, since no review has to be done anymore once it is finalized. If it somehow gets reassigned again, a new entry has to be created
            $DB->delete_records(BLOCK_EXAQUEST_DB_REVIEWASSIGN, ['questionbankentryid' => $questionbankentryid]);
        } else {
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE;
        }
        $DB->update_record('block_exaquestquestionstatus', $data);
        break;
    case ('release_question'):
        //$DB->record_exists('block_exaquestquestionstatus', array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->questionbankentryid = $questionbankentryid;
        $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED;
        $data->id = $DB->get_field('block_exaquestquestionstatus', 'id', array("questionbankentryid" => $questionbankentryid));
        $DB->update_record('block_exaquestquestionstatus', $data);
        break;
    case ('revise_question'):
        //$DB->record_exists('block_exaquestquestionstatus', array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->questionbankentryid = $questionbankentryid;
        $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE;
        $data->id = $DB->get_field('block_exaquestquestionstatus', 'id', array("questionbankentryid" => $questionbankentryid));
        $DB->update_record('block_exaquestquestionstatus', $data);
        if ($commenttext != null) {
            $args = new stdClass;
            $args->contextid = 1;
            $args->course = $courseid;
            $args->area = 'question';
            $args->itemid = $questionid;
            $args->component = 'qbank_comment';
            $args->linktext = get_string('commentheader', 'qbank_comment');
            $args->notoggle = true;
            $args->autostart = true;
            $args->displaycancel = false;
            $comment = new comment($args);
            $comment->add($commenttext);
        }

        if ($users != null) {
            $questionname = $DB->get_record('question', array('id' => $questionid))->name;
            $catAndCont = get_question_category_and_context_of_course($courseid);
            if ($users != null) {
                foreach ($users as $user) {
                    block_exaquest_request_revision($USER, $user, $commenttext, $questionbankentryid, $questionname, $catAndCont,
                        $courseid);
                }
            }
        }
        break;
    case ('mark_request_as_done'):

        break;
    case ('addquestion'):#
        //add the quiz question
        $quiz = new stdClass();
        $quiz->id = $quizid;
        $quiz->course = $courseid;
        $quiz->questionsperpage = 1;
        $ret = quiz_add_quiz_question($questionid, $quiz, $page = 0, $maxmark = null);
        break;

}