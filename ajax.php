<?php

require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $USER;
require_once($CFG->dirroot . '/comment/lib.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');

use mod_quiz\quiz_settings;

$questionbankentryid = required_param('questionbankentryid', PARAM_INT);
$questionid = required_param('questionid', PARAM_INT);
$action = required_param('action', PARAM_TEXT);
$courseid = required_param('courseid', PARAM_INT);
$users = optional_param('users', null, PARAM_RAW);
//$formalreviewusers = optional_param('formalreviewusers', null, PARAM_RAW);
$commenttext = optional_param('commenttext', null, PARAM_TEXT);
$quizid = optional_param('quizid', null, PARAM_INT);

require_login($courseid);
// check if the user has the capability of either addquestiontoexam or viewquestionbanktab
$context = context_course::instance($courseid);
if( !has_capability('block/exaquest:addquestiontoexam', $context) && !has_capability('block/exaquest:viewquestionbanktab', $context) ) {
    throw new moodle_exception('no_permission, require addquestiontoexam or viewquestionbanktab capability');
}
require_sesskey();

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
        //$catAndCont = get_question_category_and_context_of_course($courseid); // this IS relevant, but it is done in the request_review_function
        // $course = get_course($courseid);
        // $coursecategoryid = $course->category;
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
            if ($reviewedByMover) {
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
            if ($isMover) {
                // continue right on to "release_question"
            } else {
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
    case ('revise_question_from_quiz'):
        $change_status_and_remove_from_quiz = optional_param('change_status_and_remove_from_quiz', false, PARAM_BOOL);
        if (!$change_status_and_remove_from_quiz) {
            // only send a notification to the PKs, that the question has to be revised
            //$DB->record_exists(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, array("questionbankentryid" => $questionbankentryid));
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
                            $courseid, true, $questionid);
                    }
                }
            }
            break;
        }
        // if no break, then continue to revise_question and also remove the question from the quiz
        // code for removing from quiz is from edit_rest.php from mod/quiz
        // only try to remove from quiz if it is already in quiz .... if the id is not 0 (id is the slotid of the question in the quiz)
        $id = optional_param('id', 0, PARAM_INT);
        if ($id != -1){
            $quizobj = quiz_settings::create($quizid);
            $quiz = $quizobj->get_quiz();
            $structure = $quizobj->get_structure();
            $gradecalculator = $quizobj->get_grade_calculator();
            $modcontext = $quizobj->get_context();
            require_capability('mod/quiz:manage', $modcontext);

            if (!$slot = $DB->get_record('quiz_slots', ['quizid' => $quiz->id, 'id' => $id])) {
                throw new moodle_exception('AJAX commands.php: Bad slot ID ' . $id);
            }

            if (!$structure->has_use_capability($slot->slot)) {
                $slotdetail = $structure->get_slot_by_id($slot->id);
                $context = context::instance_by_id($slotdetail->contextid);
                throw new required_capability_exception($context,
                    'moodle/question:useall', 'nopermissions', '');
            }
            $structure->remove_slot($slot->slot);
            quiz_delete_previews($quiz); // moodle code also calls this whenever the quiz sumgrades are recomputed
            $gradecalculator->recompute_quiz_sumgrades();
            $result = ['newsummarks' => quiz_format_grade($quiz, $quiz->sumgrades),
                'deleted' => true, 'newnumquestions' => $structure->get_question_count()];
        }
        // if removed from the quiz, or also if not removed: always send it to revise
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

        // delete the entry in exaquestreviewassign, since no review can be done, while it is in revise status
        $DB->delete_records(BLOCK_EXAQUEST_DB_REVIEWASSIGN, ['questionbankentryid' => $questionbankentryid]);
        break;

    case ('mark_request_as_done'):

        break;
    case ('addquestion'):
        //add the quiz question
        $quiz = new stdClass();
        $quiz->id = $quizid;
        $quiz->course = $courseid;
        $quiz->questionsperpage = 1;
        //$ret = quiz_add_quiz_question($questionid, $quiz, $page = 0, $maxmark = null);

        // from edit.php from mod/quiz
        // get $cm coursemodule for this quiz
        $cm = get_coursemodule_from_instance('quiz', $quiz->id, $quiz->course);
        $quizobj = new quiz_settings($quiz, $cm, $quiz->course);
        //$structure = $quizobj->get_structure();
        $gradecalculator = $quizobj->get_grade_calculator();
        quiz_require_question_use($questionid);
        $addonpage = optional_param('addonpage', 0, PARAM_INT);
        quiz_add_quiz_question($questionid, $quiz, $addonpage);
        quiz_delete_previews($quiz);
        $gradecalculator->recompute_quiz_sumgrades();
        //$thispageurl->param('lastchanged', $questionid);
        //redirect($afteractionurl);
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

function realease_question($questionbankentryid) {
    global $DB;
    $data = new stdClass;
    $data->questionbankentryid = $questionbankentryid;
    $data->timestamp = time();
    $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED;
    $data->id = $DB->get_field(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, 'id', array("questionbankentryid" => $questionbankentryid));
    $DB->update_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $data);

    // delete any entry for this questinbankentryid in exaquestreviewassign, since no review has to be done anymore once it is released.
    $DB->delete_records(BLOCK_EXAQUEST_DB_REVIEWASSIGN, ['questionbankentryid' => $questionbankentryid]);
}
