<?php

require __DIR__ . '/inc.php';


global $DB, $CFG, $COURSE, $USER;
require_once($CFG->dirroot . '/comment/lib.php');

$questionbankentryid = required_param('questionbankentryid', PARAM_INT);
$questionid = required_param('questionid', PARAM_INT);
$action = required_param('action', PARAM_TEXT);
$courseid  = required_param('courseid', PARAM_INT);
$users  = optional_param('users', null, PARAM_RAW);
$commenttext = optional_param('commenttext', null, PARAM_TEXT);

switch ($action) {
    case ('open_question_for_review'):
        //$DB->record_exists('block_exaquestquestionstatus', array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->questionbankentryid = $questionbankentryid;
        $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS;
        $data->id = $DB->get_field('block_exaquestquestionstatus','id', array("questionbankentryid" => $questionbankentryid));
        $DB->update_record('block_exaquestquestionstatus', $data);
        if($users!= null){

            foreach($users as $user) {
                $assigndata = new stdClass;
                $assigndata->questionbankentryid = $questionbankentryid;
                $assigndata->reviewerid = $user;
                $assigndata->reviewtype = BLOCK_EXAQUEST_DB_REVIEWTYPE_FORMAL;
                $DB->insert_record('block_exaquestreviewassign', $assigndata);
                $assigndata->reviewtype = BLOCK_EXAQUEST_DB_REVIEWTYPE_FACHLICH;
                $DB->insert_record('block_exaquestreviewassign', $assigndata);

                $messageobject = new stdClass;
                $messageobject->fullname = $COURSE->fullname;
                $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $COURSE->id]);
                $messageobject->url = $messageobject->url->raw_out(false);
                $message = get_string('please_review_question', 'block_exaquest', $messageobject);
                block_exaquest_send_moodle_notification("newquestionsrequest", $USER->id, $user, $message, $message,
                    "Review");
            }
        }
        if($commenttext!= null){
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
        $record= $DB->get_record('block_exaquestquestionstatus', array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->id = $record->id;
        $data->questionbankentryid = $questionbankentryid;
        if($record->status == BLOCK_EXAQUEST_QUESTIONSTATUS_TECHNICAL_REVIEW_DONE){
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED;
        } else {
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE;
        }
        $DB->update_record('block_exaquestquestionstatus', $data);
        break;
    case ('technical_review_done'):
        $record= $DB->get_record('block_exaquestquestionstatus', array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->id = $record->id;
        $data->questionbankentryid = $questionbankentryid;
        if($record->status == BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE){
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED;
        } else {
            $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_TECHNICAL_REVIEW_DONE;
        }
        $DB->update_record('block_exaquestquestionstatus', $data);
        break;
    case ('release_question'):
        //$DB->record_exists('block_exaquestquestionstatus', array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->questionbankentryid = $questionbankentryid;
        $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED;
        $data->id = $DB->get_field('block_exaquestquestionstatus','id', array("questionbankentryid" => $questionbankentryid));
        $DB->update_record('block_exaquestquestionstatus', $data);
        break;
    case ('rework_question'):
        //$DB->record_exists('block_exaquestquestionstatus', array("questionbankentryid" => $questionbankentryid));
        $data = new stdClass;
        $data->questionbankentryid = $questionbankentryid;
        $data->status = BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE;
        $data->id = $DB->get_field('block_exaquestquestionstatus','id', array("questionbankentryid" => $questionbankentryid));
        $DB->update_record('block_exaquestquestionstatus', $data);
        if($commenttext!= null){
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

        if($users!= null){

            foreach($users as $user) {
                $messageobject = new stdClass;
                $messageobject->fullname = $COURSE->fullname;
                $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $COURSE->id]);
                $messageobject->url = $messageobject->url->raw_out(false);
                $message = get_string('please_revise_question', 'block_exaquest', $messageobject);
                block_exaquest_send_moodle_notification("newquestionsrequest", $USER->id, $user, $message, $message,
                    "Revise");
            }
        }
        break;
}