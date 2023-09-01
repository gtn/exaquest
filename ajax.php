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
//$formalreviewusers = optional_param('formalreviewusers', null, PARAM_RAW);
$commenttext = optional_param('commenttext', null, PARAM_TEXT);
$quizid = optional_param('quizid', null, PARAM_INT);

require_login($courseid);
require_capability('block/exaquest:viewquestionbanktab', context_course::instance($courseid));

switch ($action) {
    case ('open_question_for_review'):
        //$DB->record_exists(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, array("questionbankentryid" => $questionbankentryid));

        // if there is a reviseassign entry ==> delete that, since it is now revised
        $oldstatus =
            $DB->get_field(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, 'status', array("questionbankentryid" => $questionbankentryid));
        if ($oldstatus == BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE) {
            $DB->delete_records(BLOCK_EXAQUEST_DB_REVISEASSIGN, ['questionbankentryid' => $questionbankentryid]);
        }

        $data = new stdClass;
        $data->questionbankentryid = $questionbankentryid;
        $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS;
        $data->id = $DB->get_field(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, 'id', array("questionbankentryid" => $questionbankentryid));
        $data->timestamp = time();
        $DB->update_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $data);
        $questionname = $DB->get_record('question', array('id' => $questionid))->name;
        //$catAndCont = get_question_category_and_context_of_course($courseid);
        $course = get_course($courseid);
        $coursecategoryid = $course->category;
        if ($users != null) {
            foreach ($users as $user) {
                block_exaquest_request_review($USER, $user, $commenttext, $questionbankentryid, $questionname,
                    $courseid, BLOCK_EXAQUEST_REVIEWTYPE_FACHLICH);
            }
        }
        // get the PKs, which are the ones that should be assigned to do the formal review
        $formalreviewusers = block_exaquest_get_pruefungskoodrination_by_courseid($courseid);
        if ($formalreviewusers != null) {
            foreach ($formalreviewusers as $user) {
                block_exaquest_request_review($USER, $user->id, $commenttext, $questionbankentryid, $questionname,
                    $courseid, BLOCK_EXAQUEST_REVIEWTYPE_FORMAL);
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
        //$DB->record_exists(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, array("questionbankentryid" => $questionbankentryid))
        $record = $DB->get_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->id = $record->id;
        $data->timestamp = time();
        $data->questionbankentryid = $questionbankentryid;

        if ($record->status == BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE) {
            // delete the entry in exaquestreviewassign, since no review has to be done anymore once it is finalized or released.
            // If it somehow gets reassigned again, a new entry has to be created
            $DB->delete_records(BLOCK_EXAQUEST_DB_REVIEWASSIGN, ['questionbankentryid' => $questionbankentryid]);

            // check if the question has been fachlich reviewed by the mover
            $reviewedByMover = $record->reviewed_by_mover;
            if($reviewedByMover){
                // immediately release question instead of setting it to "finalised" and sending notification to mover
                realease_question($questionbankentryid);
                break;
            }
            // if it has not been reviewed by the mover but for example, the fachlicherreviewer --> send notification to mover and set status to finalised
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED;
            // send notification for releasing to the mover
            $questionname = $DB->get_record('question', array('id' => $questionid))->name;
            block_exaquest_notify_mover_of_finalised_question($USER, $questionbankentryid, $questionname, $courseid);
        } else {
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE;
            $DB->delete_records(BLOCK_EXAQUEST_DB_REVIEWASSIGN,
                ['questionbankentryid' => $questionbankentryid, 'reviewtype' => BLOCK_EXAQUEST_REVIEWTYPE_FORMAL]);
        }
        $DB->update_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $data);
        break;
    case ('fachlich_review_done'):
        $record = $DB->get_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->id = $record->id;
        $data->timestamp = time();
        $data->questionbankentryid = $questionbankentryid;

        // check if the current user is mover/modulverantwortlicher
        $context = context_course::instance($courseid);
        $isMover = has_capability("block/exaquest:modulverantwortlicher", $context, $USER);
        if ($isMover) {
            $data->reviewed_by_mover = 1;
        } else {
            $data->reviewed_by_mover = 0;
        }

        if ($record->status == BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE) {
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED;
            // delete the entry in exaquestreviewassign, since no review has to be done anymore once it is finalized. If it somehow gets reassigned again, a new entry has to be created
            $DB->delete_records(BLOCK_EXAQUEST_DB_REVIEWASSIGN, ['questionbankentryid' => $questionbankentryid]);
            if($isMover){
                // continue right on to "release_question"
            }else{
                // send notification for releasing to the mover
                $questionname = $DB->get_record('question', array('id' => $questionid))->name;
                block_exaquest_notify_mover_of_finalised_question($USER, $questionbankentryid, $questionname, $courseid);
                $DB->update_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $data);
                break;
            }
        } else {
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE;
            $DB->delete_records(BLOCK_EXAQUEST_DB_REVIEWASSIGN,
                ['questionbankentryid' => $questionbankentryid, 'reviewtype' => BLOCK_EXAQUEST_REVIEWTYPE_FACHLICH]);
            $DB->update_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $data);
            break;
        }
    case ('release_question'):
        realease_question($questionbankentryid);
        break;
    case ('revise_question'):
        //$DB->record_exists(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->questionbankentryid = $questionbankentryid;
        $data->timestamp = time();
        $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE;
        $data->id = $DB->get_field(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, 'id', array("questionbankentryid" => $questionbankentryid));
        $data->reviewed_by_mover = 0; // reset this field, since it is not reviewed by the mover anymore
        $DB->update_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $data);
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
            //$catAndCont = get_question_category_and_context_of_course($courseid);
            if ($users != null) {
                foreach ($users as $user) {
                    block_exaquest_request_revision($USER, $user, $commenttext, $questionbankentryid, $questionname,
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
    case ('change_owner'):
        if (is_array($users)) {
            $qbe = new stdClass();
            $qbe->id = $questionbankentryid;
            $qbe->ownerid = $users[0];
            $DB->update_record("question_bank_entries", $qbe);
        }
        break;
    case ('unlockquestion'):
        $data = new stdClass;
        $data->id = $DB->get_field(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, 'id', array("questionbankentryid" => $questionbankentryid));
        $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED;
        $DB->update_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $data);

        break;
    case ('lockquestion'):
        $data = new stdClass;
        $data->id = $DB->get_field(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, 'id', array("questionbankentryid" => $questionbankentryid));
        $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED;
        $DB->update_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $data);

        break;
}

function realease_question($questionbankentryid){
    global $DB;
    $data = new stdClass;
    $data->questionbankentryid = $questionbankentryid;
    $data->timestamp = time();
    $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED;
    $data->id = $DB->get_field(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, 'id', array("questionbankentryid" => $questionbankentryid));
    $DB->update_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $data);
}