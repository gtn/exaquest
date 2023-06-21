<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * ${PLUGINNAME} file description here.
 *
 * @package    ${PLUGINNAME}
 * @copyright  2022 Richard <${USEREMAIL}>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * DATABSE TABLE NAMES
 */

use qbank_managecategories\helper;

const BLOCK_EXAQUEST_DB_QUESTIONSTATUS = 'block_exaquestquestionstatus';
const BLOCK_EXAQUEST_DB_REVIEWASSIGN = 'block_exaquestreviewassign';
const BLOCK_EXAQUEST_DB_REQUESTQUEST = 'block_exaquestrequestquest';
const BLOCK_EXAQUEST_DB_REQUESTEXAM = 'block_exaquestrequestexam';
const BLOCK_EXAQUEST_DB_QUIZSTATUS = 'block_exaquestquizstatus';
const BLOCK_EXAQUEST_DB_REVISEASSIGN = 'block_exaquestreviseassign';
const BLOCK_EXAQUEST_DB_QUIZASSIGN = 'block_exaquestquizassign';
const BLOCK_EXAQUEST_DB_CATEGORIES = 'block_exaquestcategories';
const BLOCK_EXAQUEST_DB_QUIZQCOUNT = 'block_exaquestquizqcount';
const BLOCK_EXAQUEST_DB_QUIZCOMMENT = 'block_exaquestquizcomment';
/**
 * Question Status
 */
const BLOCK_EXAQUEST_QUESTIONSTATUS_NEW = 0; // created by Fragenersteller and still need to be submitted for review
const BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS = 1; // created by Fragenersteller and submitted for review
const BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE = 2;
const BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE = 3;
const BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED = 4; // finalised == to release in most cases
const BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE = 5;
const BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED = 6;
const BLOCK_EXAQUEST_QUESTIONSTATUS_IN_QUIZ = 7;
const BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED = 8;
const BLOCK_EXAQUEST_QUESTIONSTATUS_IMPORTED = 9;
/**
 * Quiz/Pruefung/Exam Status
 */
const BLOCK_EXAQUEST_QUIZSTATUS_NEW = 0; // Questions are being added
const BLOCK_EXAQUEST_QUIZSTATUS_CREATED = 1; // Questions have been added
const BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED = 2; // Fachlicher Pruefer has released
const BLOCK_EXAQUEST_QUIZSTATUS_FORMAL_RELEASED = 3; // MUSSS released it
const BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE = 4; // ongoing exam?
const BLOCK_EXAQUEST_QUIZSTATUS_FINISHED = 5; // exam finished
const BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED = 6; // grades released

/**
 * Categorytype for the exaquest categories
 */
const BLOCK_EXAQUEST_CATEGORYTYPE_FRAGENCHARACTER = 0;
const BLOCK_EXAQUEST_CATEGORYTYPE_KLASSIFIKATION = 1;
const BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH = 2;
const BLOCK_EXAQUEST_CATEGORYTYPE_LEHRINHALT = 3;

/**
 * Misc
 */
const BLOCK_EXAQUEST_REVIEWTYPE_FORMAL = 0;
const BLOCK_EXAQUEST_REVIEWTYPE_FACHLICH = 1;
//const BLOCK_EXAQUEST_REVIEWTYPE_REVISE = 2;
const BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS = 3;
const BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER = 4; // this FP is responsible for the quiz

/**
 * Filter Status
 */
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS = 0;
const BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS = 1;
const BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS_TO_SUBMIT = 2; // MY questions created but not submitted for review/assessment
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVIEW = 3;
const BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVIEW = 4;
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVISE = 5;
const BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVISE = 6;
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_RELEASE = 7;
const BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_RELEASE = 8; // 2023.02.28: there is no difference between "for me to release" "all to release" for now
const BLOCK_EXAQUEST_FILTERSTATUS_All_RELEASED_QUESTIONS = 9;
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_NEW_QUESTIONS = 10; // all questions that are created but not submitted for review
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_FACHLICH_REVIEWED = 11;
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_FORMAL_REVIEWED = 12;
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_IMPORTED_QUESTIONS = 13;

function block_exaquest_init_js_css() {
    global $PAGE, $CFG;

    // only allowed to be called once
    static $js_inited = false;
    if ($js_inited) {
        return;
    }
    $js_inited = true;
    $PAGE->requires->jquery();
    $PAGE->requires->js('/blocks/exaquest/javascript/block_exaquest.js', false);

    // main block CSS
    $PAGE->requires->css('/blocks/exaquest/css/block_exaquest.css');

    // page specific js/css
    $scriptName = preg_replace('!\.[^\.]+$!', '', basename($_SERVER['PHP_SELF']));
    if (file_exists($CFG->dirroot . '/blocks/exaquest/css/' . $scriptName . '.css')) {
        $PAGE->requires->css('/blocks/exaquest/css/' . $scriptName . '.css');
    }
    if (file_exists($CFG->dirroot . '/blocks/exaquest/javascript/' . $scriptName . '.js')) {
        $PAGE->requires->js('/blocks/exaquest/javascript/' . $scriptName . '.js', false);
    }

}

function block_exaquest_request_question($userfrom, $userto, $comment) {
    global $DB, $COURSE;
    // enter data into the exaquest tables
    $request = new stdClass();
    $request->userid = $userto;
    $request->comment = $comment;
    $request->coursecategoryid = block_exaquest_get_coursecategoryid_by_courseid($COURSE->id);
    $DB->insert_record(BLOCK_EXAQUEST_DB_REQUESTQUEST, $request, $returnid = true, $bulk = false);

    // create the message
    $messageobject = new stdClass();
    $messageobject->fullname = $COURSE->fullname;
    $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $COURSE->id]);
    $messageobject->url = $messageobject->url->raw_out(false);
    $messageobject->requestcomment = $comment;
    $message = get_string('please_create_new_questions', 'block_exaquest', $messageobject);
    $subject = get_string('please_create_new_questions_subject', 'block_exaquest', $messageobject);

    block_exaquest_send_moodle_notification("newquestionsrequest", $userfrom, $userto, $subject, $message,
        "Frageerstellung", $messageobject->url);
}

function block_exaquest_request_exam($userfrom, $userto, $comment) {
    global $DB, $COURSE;
    // enter data into the exaquest tables
    $request = new stdClass();
    $request->userid = $userto;
    $request->comment = $comment;
    $request->coursecategoryid = block_exaquest_get_coursecategoryid_by_courseid($COURSE->id);
    $DB->insert_record(BLOCK_EXAQUEST_DB_REQUESTEXAM, $request, $returnid = true, $bulk = false);

    // create the message
    $messageobject = new stdClass();
    $messageobject->fullname = $COURSE->fullname;
    $messageobject->url = new moodle_url('/course/view.php', ['id' => $COURSE->id]);
    $messageobject->url = $messageobject->url->raw_out(false);
    $messageobject->requestcomment = $comment;
    $message = get_string('please_create_new_exams', 'block_exaquest', $messageobject);
    $subject = get_string('please_create_new_exams_subject', 'block_exaquest', $messageobject);

    block_exaquest_send_moodle_notification("newexamsrequest", $userfrom, $userto, $subject, $message,
        "Prüfungserstellung", $messageobject->url);
}

function block_exaquest_request_review($userfrom, $userto, $comment, $questionbankentryid, $questionname, $coursecategoryid,
    $courseid,
    $reviewtype) {
    global $DB, $COURSE;
    // enter data into the exaquest tables
    $assigndata = new stdClass;
    $assigndata->questionbankentryid = $questionbankentryid;
    $assigndata->reviewerid = $userto;
    $assigndata->reviewtype = $reviewtype;
    $assigndata->coursecategoryid = $coursecategoryid;
    $DB->insert_record(BLOCK_EXAQUEST_DB_REVIEWASSIGN, $assigndata);

    // create the message
    $messageobject = new stdClass;
    $messageobject->fullname = $questionname;
    //$messageobject->url = new moodle_url('/blocks/exaquest/questbank.php', array('courseid' => $courseid, 'category' => $catAndCont[0] . ',' . $catAndCont[1]));
    $messageobject->url = new moodle_url('/blocks/exaquest/questbank.php',
        array('courseid' => $courseid, 'filterstatus' => BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVIEW));
    $messageobject->url = $messageobject->url->raw_out(false);
    $messageobject->requestcomment = $comment;
    $message = get_string('please_review_question', 'block_exaquest', $messageobject);
    $subject = get_string('please_review_question_subject', 'block_exaquest', $messageobject);
    block_exaquest_send_moodle_notification("reviewquestion", $userfrom->id, $userto, $subject, $message,
        "Review", $messageobject->url);
}

function block_exaquest_request_revision($userfrom, $userto, $comment, $questionbankentryid, $questionname, $coursecategoryid,
    $courseid) {
    global $DB, $COURSE;
    // enter data into the exaquest tables
    $assigndata = new stdClass;
    $assigndata->questionbankentryid = $questionbankentryid;
    $assigndata->reviserid = $userto;
    $assigndata->coursecategoryid = block_exaquest_get_coursecategoryid_by_courseid($courseid);
    // I am assigning formal and fachlich review here... is this correct? --> NO, this would not make sense... The "request revision" should maybe change the owner? or add it to reviewassign but with 3rd value, not fachlich or formal review
    // created table like the reviewtable to assign revision
    //$assigndata->reviewtype = BLOCK_EXAQUEST_REVIEWTYPE_FORMAL;
    //$DB->insert_record(BLOCK_EXAQUEST_DB_REVIEWASSIGN, $assigndata);
    //$assigndata->reviewtype = BLOCK_EXAQUEST_REVIEWTYPE_FACHLICH;
    //$DB->insert_record(BLOCK_EXAQUEST_DB_REVIEWASSIGN, $assigndata);
    $DB->insert_record(BLOCK_EXAQUEST_DB_REVISEASSIGN, $assigndata);

    // create the message
    $messageobject = new stdClass;
    $messageobject->fullname = $questionname;
    //$messageobject->url = new moodle_url('/blocks/exaquest/questbank.php', array('courseid' => $courseid, 'category' => $catAndCont[0] . ',' . $catAndCont[1]));
    $messageobject->url = new moodle_url('/blocks/exaquest/questbank.php',
        array('courseid' => $courseid, 'filterstatus' => BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVISE));
    $messageobject->url = $messageobject->url->raw_out(false);
    $messageobject->requestcomment = $comment;
    $message = get_string('please_revise_question', 'block_exaquest', $messageobject);
    $subject = get_string('please_revise_question_subject', 'block_exaquest', $messageobject);
    block_exaquest_send_moodle_notification("revisequestion", $userfrom->id, $userto, $subject, $message,
        "Revise", $messageobject->url);
}

function block_exaquest_notify_mover_of_finalised_question($userfrom, $questionbankentryid, $questionname, $courseid) {
    // create the message
    $messageobject = new stdClass;
    $messageobject->fullname = $questionname;
    $messageobject->url = new moodle_url('/blocks/exaquest/questbank.php',
        array('courseid' => $courseid, 'filterstatus' => BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_RELEASE));
    $messageobject->url = $messageobject->url->raw_out(false);
    $message = get_string('please_release_question', 'block_exaquest', $messageobject);
    $subject = get_string('please_release_question_subject', 'block_exaquest', $messageobject);
    // get the movers
    $movers = block_exaquest_get_modulverantwortliche_by_courseid($courseid);
    foreach ($movers as $mover) {
        block_exaquest_send_moodle_notification("releasequestion", $userfrom->id, $mover->id, $subject, $message, "Release",
            $messageobject->url);
    }

}

function block_exaquest_send_moodle_notification($notificationtype, $userfrom, $userto, $subject, $message, $context,
    $contexturl = null, $courseid = 0, $customdata = null, $messageformat = FORMAT_HTML) {
    global $CFG, $DB;

    require_once($CFG->dirroot . '/message/lib.php');

    $eventdata = new core\message\message();

    $eventdata->modulename = 'block_exaquest';
    $eventdata->userfrom = $userfrom;
    $eventdata->userto = $userto;
    $eventdata->fullmessage = $message;
    $eventdata->name = $notificationtype;
    $eventdata->subject = $subject;
    $eventdata->fullmessageformat = $messageformat;
    $eventdata->fullmessagehtml = $message;
    $eventdata->smallmessage = $subject;
    $eventdata->component = 'block_exaquest';
    $eventdata->notification = 1;
    $eventdata->contexturl = $contexturl;
    $eventdata->contexturlname = $context;
    $eventdata->courseid = $courseid;
    $eventdata->customdata = $customdata;    // version must be 3.7 or higher, otherwise this field does not yet exist

    message_send($eventdata);
}

/**
 *
 * Returns all fragenersteller of this course
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_fragenersteller_by_courseid($courseid) {
    $context = context_course::instance($courseid);
    $userarray = array();
    $userarray = array_replace($userarray, get_enrolled_users($context, 'block/exaquest:fragenerstellerlight'));
    $userarray = array_replace($userarray, get_enrolled_users($context, 'block/exaquest:fragenersteller'));
    return $userarray;
}

/**
 *
 * Returns all pmw of this course
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_pmw_by_courseid($courseid) {
    $context = context_course::instance($courseid);
    $userarray = array();
    $userarray = array_merge($userarray, get_enrolled_users($context, 'block/exaquest:pruefungsmitwirkende'));
    return $userarray;
}

/**
 *
 * Returns all fachlichepruefer of this course
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_fachlichepruefer_by_courseid($courseid) {
    $context = context_course::instance($courseid);
    return get_enrolled_users($context, 'block/exaquest:fachlicherpruefer');
}

/**
 *
 * Returns all modulverantwortliche of this course
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_modulverantwortliche_by_courseid($courseid) {
    $context = context_course::instance($courseid);
    return get_enrolled_users($context, 'block/exaquest:modulverantwortlicher');
}

/**
 *
 * Returns all who have the right to review of this course
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_reviewer_by_courseid($courseid) {
    $context = context_course::instance($courseid);
    $userarray = array();
    //$userarray = array_merge($userarray, get_enrolled_users($context, 'block/exaquest:modulverantwortlicher'));
    $userarray = array_merge($userarray, get_enrolled_users($context, 'block/exaquest:fachlfragenreviewer'));
    $userarray = array_merge($userarray, get_enrolled_users($context, 'block/exaquest:fachlfragenreviewerlight'));
    //$userarray = array_merge($userarray, get_enrolled_users($context,
    //    'block/exaquest:pruefungskoordination')); // according to 20230112 Feedbackliste. But should they really have the right to review? nope, they should not be here, as told in later feedbackliste 20230323
    $userarray = array_unique($userarray, SORT_REGULAR); // to remove users who have multiple roles
    return $userarray;
}

/**
 * Returns all count of questionbankentries that have to be formally reviewed
 * used e.g. for the prüfungscoordination or the studmis to see which questions they should revise
 *
 * @param $courseid
 * @param $userid
 * @return array
 */
function block_exaquest_get_questionbankentries_to_formal_review_count($coursecategoryid,
    $userid) { // TODO change to coursecategoryid and use it in query
    global $DB;
    $sql = "SELECT q.*
			FROM {" . BLOCK_EXAQUEST_DB_REVIEWASSIGN . "} ra
			JOIN {question_bank_entries} qe ON ra.questionbankentryid = qe.id
			WHERE ra.reviewerid = :reviewerid
			AND ra.reviewtype = :reviewtype";

    $questions =
        count($DB->get_records_sql($sql, array("reviewerid" => $userid, "reviewtype" => BLOCK_EXAQUEST_REVIEWTYPE_FORMAL)));

    return $questions;
}

/**
 * Returns count of questionbankentries that have to be fachlich reviewed
 * used e.g. for the fachlicherreviewer to see which questions they should revise
 *
 * @param $courseid
 * @param $userid
 * @return array
 */
function block_exaquest_get_questionbankentries_to_fachlich_review_count($courseid,
    $userid) { // TODO change to coursecategoryid and use it in query
    global $DB;
    $sql = "SELECT q.*
			FROM {" . BLOCK_EXAQUEST_DB_REVIEWASSIGN . "} ra
			JOIN {question_bank_entries} qe ON ra.questionbankentryid = qe.id
			WHERE ra.reviewerid = :reviewerid
			AND ra.reviewtype = :reviewtype";

    $questions =
        count($DB->get_records_sql($sql, array("reviewerid" => $userid, "reviewtype" => BLOCK_EXAQUEST_REVIEWTYPE_FACHLICH)));

    return $questions;
}

/**
 * Returns count of all questionbankentries (all entries in exaquestqeustionstatus)
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questionbankentries_by_coursecategoryid_count($coursecategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			WHERE qs.coursecategoryid = :coursecategoryid";

    // we simply count the exaquestquestionstatus entries for this course, so we do not need to have the category, do not read unneccesary entries in the question_bank_entries etc

    $questions = count($DB->get_records_sql($sql, array("coursecategoryid" => $coursecategoryid)));

    // TODO: check the questionlib for functions like get_question_bank_entry( that could be useful

    return $questions;
}

/**
 * Returns count of all questionbankentries (all entries in exaquestqeustionstatus)
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_my_questionbankentries_count($coursecategoryid, $userid) {
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }
    $sql = "SELECT qs.id
              FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
              JOIN {question_bank_entries} qbe ON qbe.id = qs.questionbankentryid 
             WHERE qs.coursecategoryid = :coursecategoryid
             AND qbe.ownerid = :ownerid";

    $questions = count($DB->get_records_sql($sql, array("coursecategoryid" => $coursecategoryid, "ownerid" => $userid)));

    return $questions;
}

/**
 * Returns count of all questionbankentries (all entries in exaquestqeustionstatus)
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_my_questionbankentries_to_submit_count($coursecategoryid, $userid) {
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }
    $sql = "SELECT qs.id
              FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
              JOIN {question_bank_entries} qbe ON qbe.id = qs.questionbankentryid 
             WHERE qs.coursecategoryid = :coursecategoryid
             AND qbe.ownerid = :ownerid
             AND qs.status = :newquestion";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid, "ownerid" => $userid, "newquestion" => BLOCK_EXAQUEST_QUESTIONSTATUS_NEW)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questionbankentries_to_be_reviewed_count($coursecategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			WHERE qs.coursecategoryid = :coursecategoryid
			AND (qs.status = :fachlichreviewdone
			OR qs.status = :formalreviewdone
			OR qs.status = :toassess)";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid, "fachlichreviewdone" => BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE,
            "formalreviewdone" => BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE,
            "toassess" => BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS)));

    return $questions;

}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questionbankentries_to_be_revised_count($coursecategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			WHERE qs.coursecategoryid = :coursecategoryid
			AND qs.status = :questionstatus";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid, "questionstatus" => BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE)));

    return $questions;

}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questionbankentries_new_count($coursecategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			WHERE qs.coursecategoryid = :coursecategoryid
			AND qs.status = :questionstatus";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid, "questionstatus" => BLOCK_EXAQUEST_QUESTIONSTATUS_NEW)));

    return $questions;

}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questionbankentries_formal_reviewed_count($coursecategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			WHERE qs.coursecategoryid = :coursecategoryid
			AND qs.status = :formalreviewdone";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid, "formalreviewdone" => BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questionbankentries_fachlich_reviewed_count($coursecategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			WHERE qs.coursecategoryid = :coursecategoryid
			AND qs.status = :fachlichreviewdone";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid,
            "fachlichreviewdone" => BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_my_questionbankentries_to_be_reviewed_count($coursecategoryid, $userid) {
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qbe.id = qs.questionbankentryid 
			WHERE qs.coursecategoryid = :coursecategoryid
			AND (qs.status = :fachlichreviewdone
			OR qs.status = :formalreviewdone
			OR qs.status = :toassess)
            AND qbe.ownerid = :ownerid";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid, "fachlichreviewdone" => BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE,
            "formalreviewdone" => BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE,
            "toassess" => BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS, "ownerid" => $userid)));

    return $questions;
}

/**
 * Returns count of
 *
 * TODO: reviewed == finalised ?
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_released_questionbankentries_count($coursecategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			WHERE qs.coursecategoryid = :coursecategoryid
			AND qs.status = :finalised";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid, "finalised" => BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_released_and_to_review_questionbankentries_count($coursecategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			WHERE qs.coursecategoryid = :coursecategoryid
			AND qs.status = :finalised";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid, "finalised" => BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_finalised_questionbankentries_count($coursecategoryid) {
    // the same as to_release in most cases
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			WHERE qs.coursecategoryid = :coursecategoryid
			AND qs.status = :finalised";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid, "finalised" => BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_my_finalised_questionbankentries_count($coursecategoryid, $userid) {
    // the same as to_release in most cases
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qbe.id = qs.questionbankentryid 
			WHERE qs.coursecategoryid = :coursecategoryid
			AND qs.status = :finalised
			AND qbe.ownerid = :ownerid";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid, "finalised" => BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED,
            "ownerid" => $userid)));

    return $questions;
}

function block_exaquest_get_quizzes_for_me_to_fill_count($userid) {
    return count(block_exaquest_get_quizzes_for_me_to_fill($userid));
}

function block_exaquest_get_quizzes_for_me_to_fill($userid) {
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }
    // questionbankentryid DISTINCT to not count twice
    $sql = 'SELECT DISTINCT qa.quizid as quizid, q.name as name,  cm.id as coursemoduleid
			FROM {' . BLOCK_EXAQUEST_DB_QUIZASSIGN . '} qa
			JOIN {quiz} q on q.id = qa.quizid 
			JOIN {course_modules} cm on cm.instance = q.id
			JOIN {modules} m on m.id = cm.module
			WHERE qa.assigneeid = :userid
			AND qa.assigntype = ' . BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS . '
            AND m. name = "quiz"';

    $quizzes = $DB->get_records_sql($sql,
        array('userid' => $userid));
    return $quizzes;
}

//-----------

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questions_for_me_to_review_count($coursecategoryid, $userid = 0) {
    // get from the table exaquestreviewassing. But there are 2 reviewtypes, do not count twice!
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }

    // questionbankentryid DISTINCT to not count twice
    $sql = 'SELECT DISTINCT ra.questionbankentryid
			FROM {' . BLOCK_EXAQUEST_DB_REVIEWASSIGN . '} ra
			WHERE ra.reviewerid = :userid
			AND ra.coursecategoryid = :coursecategoryid';
    //AND (ra.reviewtype = '. BLOCK_EXAQUEST_REVIEWTYPE_FORMAL .' OR ra.reviewtype =  '. BLOCK_EXAQUEST_REVIEWTYPE_FACHLICH .')';

    $questions = count($DB->get_records_sql($sql,
        array('userid' => $userid, 'coursecategoryid' => $coursecategoryid)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questions_for_me_to_create_count($coursecategoryid, $userid = 0) {
    return count(block_exaquest_get_questions_for_me_to_create($coursecategoryid, $userid));
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_exams_for_me_to_create_count($coursecategoryid, $userid = 0) {
    return count(block_exaquest_get_exams_for_me_to_create($coursecategoryid, $userid));
}

/**
 * Returns
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questions_for_me_to_create($coursecategoryid, $userid = 0) {
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }

    // questionbankentryid DISTINCT to not count twice
    $sql = 'SELECT *
			FROM {' . BLOCK_EXAQUEST_DB_REQUESTQUEST . '} req
			WHERE req.userid = :userid
			AND req.coursecategoryid = :coursecategoryid';

    $questions = $DB->get_records_sql($sql,
        array('userid' => $userid, 'coursecategoryid' => $coursecategoryid));

    return $questions;
}

/**
 * Returns
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_exams_for_me_to_create($coursecategoryid, $userid = 0) {
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }

    $sql = 'SELECT *
			FROM {' . BLOCK_EXAQUEST_DB_REQUESTEXAM . '} req
			WHERE req.userid = :userid
            AND req.coursecategoryid = :coursecategoryid';

    $questions = $DB->get_records_sql($sql,
        array('userid' => $userid, 'coursecategoryid' => $coursecategoryid));

    return $questions;
}

/**
 * Returns
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_exams_for_me_to_fill($courseid, $userid = 0) {
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }

    // get the exams that are assigned to me and that are in the course of the course
    $sql = 'SELECT qa.*, q.name, qc.comment
			FROM {' . BLOCK_EXAQUEST_DB_QUIZASSIGN . '} qa
			JOIN {quiz} q on q.id = qa.quizid
			JOIN {' . BLOCK_EXAQUEST_DB_QUIZCOMMENT . '} qc on qc.quizid = qa.quizid AND qc.quizassignid = qa.id
			WHERE qa.assigneeid = :assigneeid
            AND q.course = :courseid';

    $exams = $DB->get_records_sql($sql,
        array('assigneeid' => $userid, 'courseid' => $courseid));

    return $exams;
}

/**
 * Returns
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_exams_for_me_to_fill_count($courseid, $userid = 0) {
    return count(block_exaquest_get_exams_for_me_to_fill($courseid, $userid));
}

/**
 * Returns count of questionbankentries that have to be revised of this course of this user
 * used e.g. for the fragenersteller to see which questions they should revise
 *
 * @param $coursecategoryid
 * @param $userid
 * @return array
 */
function block_exaquest_get_questions_for_me_to_revise_count($coursecategoryid, $userid = 0) {
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }

    $sql = 'SELECT qs.id
			FROM {' . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . '} qs
			JOIN {question_bank_entries} qe ON qs.questionbankentryid = qe.id
			WHERE qe.ownerid = :ownerid
			AND qs.status = :status
			AND qs.coursecategoryid = :coursecategoryid';

    $questions =
        count($DB->get_records_sql($sql, array('ownerid' => $userid, 'status' => BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE,
            'coursecategoryid' => $coursecategoryid)));

    return $questions;
}

/**
 * Returns count of questions for me to release. TODO: what should that mean? In which case is there a specific person responsible
 * for releasing?
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questions_for_me_to_release_count($coursecategoryid, $userid = 0) {
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {" . BLOCK_EXAQUEST_DB_REVIEWASSIGN . "} qra ON qra.questionbankentryid = qs.questionbankentryid 
			WHERE qs.coursecategoryid = :coursecategoryid
			AND qs.status = :finalised
			AND qra.reviewerid = :reviewerid";

    $questions = count($DB->get_records_sql($sql,
        array("coursecategoryid" => $coursecategoryid, "finalised" => BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED,
            "reviewerid" => $userid)));

    return $questions;
}

//-----------

function block_exaquest_set_up_roles_test() {
    global $DB;
    $context = \context_system::instance();
    $options = array(
        'shortname' => 0,
        'name' => 0,
        'description' => 0,
        'permissions' => 1,
        'archetype' => 0,
        'contextlevels' => 1,
        'allowassign' => 1,
        'allowoverride' => 1,
        'allowswitch' => 1,
        'allowview' => 1);

    if (!$DB->record_exists('role', ['shortname' => 'testen'])) {
        $roleid = create_role('Test Role', 'testen', '', 'manager');
        $archetype = intval($DB->get_record('role', ['shortname' => 'manager'])->id); // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'admintechnpruefungsdurchf'])->id;
    }
    assign_capability('block/exaquest:admintechnpruefungsdurchf', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:doformalreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:executeexam', CAP_ALLOW, $roleid, $context);

}

/**
 * Sets up the roles in install.php and upgrade.php
 */
function block_exaquest_set_up_roles() {
    global $DB;
    $context = \context_system::instance();
    $options = array(
        'shortname' => 0,
        'name' => 0,
        'description' => 0,
        'permissions' => 1,
        'archetype' => 0,
        'contextlevels' => 1,
        'allowassign' => 1,
        'allowoverride' => 1,
        'allowswitch' => 1,
        'allowview' => 1);

    if (!$DB->record_exists('role', ['shortname' => 'admintechnpruefungsdurchf'])) {
        $roleid = create_role('admin./techn. Prüfungsdurchf.', 'admintechnpruefungsdurchf', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'admintechnpruefungsdurchf'])->id;
    }
    assign_capability('block/exaquest:admintechnpruefungsdurchf', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:doformalreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:executeexam', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'pruefungskoordination'])) {
        $roleid = create_role('Prüfungskoordination', 'pruefungskoordination', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'pruefungskoordination'])->id;
    }
    assign_capability('block/exaquest:pruefungskoordination', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:readallquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:readquestionstatistics', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changestatusofreleasedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createquestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:setstatustoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:setstatustofinalised', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:editquestiontoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinalisedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:editallquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assignsecondexaminator', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:definequestionblockingtime', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:viewquestionstorelease', CAP_ALLOW, $roleid, $context); only modulverantwortlicher
    //assign_capability('block/exaquest:viewquestionstorelease', CAP_PROHIBIT, $roleid, $context);

    assign_capability('block/exaquest:viewnewexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:releasequestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:requestnewexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:doformalreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:setquestioncount', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'pruefungsstudmis'])) {
        $roleid = create_role('PrüfungsStudMis', 'pruefungsstudmis', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'pruefungsstudmis'])->id;
    }
    assign_capability('block/exaquest:pruefungsstudmis', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:readquestionstatistics', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexams', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:createexam', $roleid, $context->id); // accidentally added, should be deleted

    if (!$DB->record_exists('role', ['shortname' => 'modulverantwortlicher'])) {
        $roleid = create_role('Modulverantwortlicher', 'modulverantwortlicher', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'modulverantwortlicher'])->id;
    }
    assign_capability('block/exaquest:modulverantwortlicher', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:readallquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:readquestionstatistics', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changestatusofreleasedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:reviseownquestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:setstatustofinalised', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:editquestiontoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinalisedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:releasequestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:editallquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createquestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorelease', CAP_ALLOW, $roleid, $context);

    assign_capability('block/exaquest:viewquestionstoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:requestnewexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:dofachlichreview', CAP_ALLOW, $roleid, $context);
    //Mover should not be able to do this : assign_capability('block/exaquest:doformalreview', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:doformalreview', $roleid, $context->id); // accidentally added, should be deleted
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:setquestioncount', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'fragenersteller'])) {
        $roleid = create_role('Fragenersteller', 'fragenersteller', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fragenersteller'])->id;
    }
    assign_capability('block/exaquest:fragenersteller', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createquestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:readallquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changestatusofreleasedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:setstatustoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:reviseownquestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewownrevisedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'fachlfragenreviewer'])) {
        $roleid = create_role('fachl. Fragenreviewer', 'fachlfragenreviewer', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fachlfragenreviewer'])->id;
    }
    assign_capability('block/exaquest:fachlfragenreviewer', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:readallquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changestatusofreleasedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:editquestiontoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:dofachlichreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'beurteilungsmitwirkende'])) {
        $roleid = create_role('Beurteilungsmitwirkende', 'beurteilungsmitwirkende', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'beurteilungsmitwirkende'])->id;
    }
    assign_capability('block/exaquest:beurteilungsmitwirkende', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);

    assign_capability('block/exaquest:viewfinishedexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexams', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'fachlicherpruefer'])) {
        $roleid = create_role('fachlicher Prüfer', 'fachlicherpruefer', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fachlicherpruefer'])->id;
    }
    assign_capability('block/exaquest:fachlicherpruefer', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changestatusofreleasedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:releaseexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assignsecondexaminator', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createexam', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'pruefungsmitwirkende'])) {
        $roleid = create_role('Prüfungsmitwirkende', 'pruefungsmitwirkende', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'pruefungsmitwirkende'])->id;
    }
    assign_capability('block/exaquest:pruefungsmitwirkende', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'fachlicherzweitpruefer'])) {
        $roleid = create_role('Fachlicher Zweitprüfer', 'fachlicherzweitpruefer', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fachlicherzweitpruefer'])->id;
    }
    assign_capability('block/exaquest:fachlicherzweitpruefer', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);

    // ---
    if (!$DB->record_exists('role', ['shortname' => 'fragenerstellerlight'])) {
        $roleid = create_role('Fragenerstellerlight', 'fragenerstellerlight', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fragenerstellerlight'])->id;
    }
    assign_capability('block/exaquest:fragenerstellerlight', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createquestion', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:readallquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changestatusofreleasedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:setstatustoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:reviseownquestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewownrevisedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewownquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'fachlfragenreviewerlight'])) {
        $roleid = create_role('fachl. Fragenreviewerlight', 'fachlfragenreviewerlight', '', 'manager');
        $archetype = $DB->get_record('role', ['shortname' => 'manager'])->id; // manager archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
            $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fachlfragenreviewerlight'])->id;
    }
    assign_capability('block/exaquest:fachlfragenreviewerlight', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:readallquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changestatusofreleasedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:editquestiontoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewownquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);

    //
    //role_assign($roleid, $USER->id, $contextid);

    //if ($roleid = $DB->get_field('role', 'id', array('shortname' => 'custom_role')){
    //$context = \context_system::instance(){;
    //assign_capability('block/custom_block:custom_capability', CAP_ALLOW,
    //    $roleid, $context);
    //}
}

/**
 * Checks the active exams and changes status to finished, according to timing.
 */
function block_exaquest_check_active_exams() {
    $activeexams = block_exaquest_exams_by_status(null, BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE);
    $timeoverdueexams = array_filter($activeexams, function($exam, $key) {
        return $exam->timeclose < time();
    }, ARRAY_FILTER_USE_BOTH);

    foreach ($timeoverdueexams as $exam) {
        block_exaquest_exams_set_status($exam->quizid, BLOCK_EXAQUEST_QUIZSTATUS_FINISHED);
    }
}

/**
 * Build navigtion tabs, depending on role and version
 *
 * @param object $context
 * @param int $courseid
 */
function block_exaquest_build_navigation_tabs($context, $courseid) {
    global $USER, $PAGE, $COURSE;

    //$globalcontext = context_system::instance();

    //$courseSettings = block_exacomp_get_settings_by_course($courseid);
    //$ready_for_use = block_exacomp_is_ready_for_use($courseid);

    //$de = false;
    //$lang = current_language();
    //if (isset($lang) && substr($lang, 0, 2) === 'de') {
    //    $de = true;
    //}
    //
    //$rows = array();

    //$isTeacher = block_exacomp_is_teacher($context) && $courseid != 1;
    //$isStudent = has_capability('block/exacomp:student', $context) && $courseid != 1 && !has_capability('block/exacomp:admin', $context);
    //$isTeacherOrStudent = $isTeacher || $isStudent;
    $catAndCont = get_question_category_and_context_of_course();
    //var_dump($catAndCont);
    //die;

    $rows[] = new tabobject('tab_dashboard',
        new moodle_url('/blocks/exaquest/dashboard.php', array("courseid" => $courseid)),
        get_string('dashboard', 'block_exaquest'), null, true);

    if (has_capability('block/exaquest:viewquestionbanktab', \context_course::instance($COURSE->id))) {
        $rows[] = new tabobject('tab_get_questions',
            new moodle_url('/blocks/exaquest/questbank.php',
                array("courseid" => $courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1])),
            get_string('get_questionbank', 'block_exaquest'), null, true);
    }

    if (has_capability('block/exaquest:viewsimilaritytab', \context_course::instance($COURSE->id))) {
        $rows[] = new tabobject('tab_similarity_comparison',
            new moodle_url('/blocks/exaquest/similarity_comparison.php',
                array("courseid" => $courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1])),
            get_string('similarity', 'block_exaquest'), null, true);
    }

    if (has_capability('block/exaquest:viewexamstab', \context_course::instance($COURSE->id))) {
        $rows[] = new tabobject('tab_exams',
            new moodle_url('/blocks/exaquest/exams.php', array("courseid" => $courseid)),
            get_string('exams', 'block_exaquest'), null, true);
    }

    if (has_capability('block/exaquest:viewcategorytab', \context_course::instance($COURSE->id))) {
        $rows[] = new tabobject('tab_category_settings',
            new moodle_url('/blocks/exaquest/category_settings.php', array("courseid" => $courseid)),
            get_string('category_settings', 'block_exaquest'), null, true);
    }

    return $rows;
}

// this is used to get the contexts of the category in the questionbank. The default question category of the coursecategory is used.

function get_question_category_and_context_of_course($courseid = null) {
    global $COURSE, $DB;
    if ($courseid == null) {
        $courseid = $COURSE->id;
    }
    // this is used to get the contexts of the category in the questionbank
    //
    //
    //// different way of getting question category and context of course:
    //$context = context_course::instance($courseid);
    //
    //// get the course:
    //$course = $DB->get_record('course', ['id' => $courseid]);
    //// get the question category for the default question category of the course:
    ////$questioncategory = $DB->get_record('question_categories', ['id' => $course->defaultquestioncategory]);
    //
    //list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) =
    //    question_edit_setup('questions', '/question/edit.php');

    // get coursecontext and coursecategory context
    $coursecontext = context_course::instance($courseid);
    $course = $DB->get_record('course', ['id' => $courseid]);
    $coursecategorycontext = context_coursecat::instance($course->category);

    // put them into $contexts so the get_categories_for_contexts function can use them
    $contexts = [$coursecontext, $coursecategorycontext];
    // get the categories for the contexts
    $pcontexts = [];
    foreach ($contexts as $context) {
        $pcontexts[] = $context->id;
    }
    $contextslist = join(', ', $pcontexts);
    $categories = helper::get_categories_for_contexts($contextslist, 'id', false);
    // find the category that is not for the course, but for the coursecategory. There can be different questioncategories for one coursecategory
    // ==> take the first one (except top, which is not returned by get_categories_for_contexts) which is the default questioncategory
    foreach ($categories as $category) {
        if ($category->contextid == $coursecategorycontext->id) {
            $categoryid = $category->id;
            $contextid = $category->contextid;
            break;
        }
    }

    // explanation for what categoryid and contextid is and how context works in this case:
    // categoryid is the id of the questioncategory in the question_categories table, which is what we need
    // contextid is the contextid saved in the questioncategory table. This contextid is the id in the context table.
    // in the context table the instanceid is the id of the coursecategory
    // this way, the questioncontext for the coursecategory of the current course can be found.

    return [$categoryid, $contextid];

    ////$catmenu = helper::question_category_options($contexts, true, 0,
    ////    true, -1, false);
    //
    //$context = context_course::instance($courseid);
    ////var_dump($context);
    ////die;
    //$contexts = explode('/', $context->path);
    //$questioncategory = $DB->get_records('question_categories',
    //    ['contextid' => $contexts[2]]); // hardcoded contexts[2] leads to problems when the path has a different depth than expected
    //$category =
    //    end($questioncategory); // an actual array, not a returnvalue of a function has to be passed, since it sets the internal pointer of the array, so there has to be a real array
    //
    //if ($category) {
    //    //var_dump([$category->id, $contexts[2]]);
    //    //die;
    //    return [$category->id,
    //        $contexts[2]]; // TODO why $contexts[2]? That should give the same as $category->contextid but $category->contextid seems safer
    //} else {
    //    return false;
    //}

}

/**block_instances
 *
 * Returns all course ids where an instance of Exabis question management tool is installed
 */
function block_exaquest_get_courseids() {
    global $DB;

    $instances = $DB->get_records('block_instances', array('blockname' => 'exaquest'));

    $exaquest_courses = array();

    foreach ($instances as $instance) {
        // get only the instances of the block in a COURSE context
        $context = $DB->get_record('context', array('id' => $instance->parentcontextid, 'contextlevel' => CONTEXT_COURSE));
        if ($context) {
            $exaquest_courses[$context->instanceid] = $context->instanceid;
        }
    }

    return $exaquest_courses;
}

/**block_instances
 *
 * Returns all course ids where an instance of Exabis question management tool is installed for this user
 * The course has to have an enddate that ends in the last 6 months, or no enddate (be active)
 */
function block_exaquest_get_courseids_of_relevant_courses_for_user($userid = null) {
    global $DB, $USER;

    if ($userid == null) {
        $userid = $USER->id;
    }

    $instances = $DB->get_records('block_instances', array('blockname' => 'exaquest'));

    $exaquest_courses = array();

    foreach ($instances as $instance) {
        $context = $DB->get_record('context', array('id' => $instance->parentcontextid, 'contextlevel' => CONTEXT_COURSE));
        if ($context) {
            if (block_exaquest_is_user_in_course($userid, $context->instanceid)) {
                // check if the course is still active or was active in the lase 6 months
                $course = get_course($context->instanceid);
                if ($course->enddate) {
                    if ($course->enddate > (time() - 15552000)) { // 15552000 is 6 months in seconds
                        $exaquest_courses[$context->instanceid] = $context->instanceid;
                    }
                } else {
                    $exaquest_courses[$context->instanceid] = $context->instanceid;
                }
            }
        }
    }

    return $exaquest_courses;
}

/**
 * @param $userid
 * Returns the summed up count of todos. E.g. "2 questions for me to create + 2 questions for me to submit = 4 todos"
 * questions_for_me_to_create_count + my_questions_to_submit_count + questions_for_me_to_review_count + questions_finalised_count +
 *     questions_for_me_to_revise_count + exams_for_me_to_create_count. Depending on your role some of those todos may not exist,
 *     but this will lead to a count of 0 for those todos, which means, it does not matter. No capabilities check needed.
 */
function block_exaquest_get_todo_count($userid, $coursecategoryid, $context) {
    $questions_for_me_to_create_count =
        block_exaquest_get_questions_for_me_to_create_count($coursecategoryid, $userid);
    $questions_for_me_to_review_count =
        block_exaquest_get_questions_for_me_to_review_count($coursecategoryid, $userid);
    $questions_for_me_to_revise_count =
        block_exaquest_get_questions_for_me_to_revise_count($coursecategoryid, $userid);

    // there is no "for me to release, which is why we take the finalised questionbankentries count. This is not available for everyone ==> check capability
    $questions_finalised_count = 0;
    if (has_capability('block/exaquest:viewquestionstorelease', $context, $userid)) {
        $questions_finalised_count = block_exaquest_get_finalised_questionbankentries_count($coursecategoryid);
    }

    $my_questions_to_submit_count =
        block_exaquest_get_my_questionbankentries_to_submit_count($coursecategoryid, $userid);
    //$exams_for_me_to_create_count =
    //    block_exaquest_get_exams_for_me_to_create_count($coursecategoryid, $userid);
    $exams_for_me_to_fill_count = block_exaquest_get_exams_for_me_to_fill_count($coursecategoryid, $userid);

    return $questions_for_me_to_create_count + $questions_for_me_to_review_count + $questions_for_me_to_revise_count +
        $questions_finalised_count + $exams_for_me_to_fill_count + $my_questions_to_submit_count;
}

function block_exaquest_is_user_in_course($userid, $courseid) {
    $context = context_course::instance($courseid);

    // also check for exacomp course?
    // has_capability('block/exacomp:use', $context, $userid))
    return is_enrolled($context, $userid, '', true);
}

function block_exaquest_get_coursecategoryid_by_courseid($courseid) {
    global $DB;
    $course = $DB->get_record('course', array('id' => $courseid));
    return $course->category;
}

/**
 * Returns exams filtered by status.
 * Similar to the get questions functions, but all in one function with ifs, instead of that many functions.
 *
 * @param $coursecategoryid
 * @param $status
 * @return array
 */
function block_exaquest_exams_by_status($coursecategoryid = null, $status = BLOCK_EXAQUEST_QUIZSTATUS_NEW) {
    global $DB, $USER;

    if ($coursecategoryid) {
        $sql = "SELECT q.id as quizid, q.name as name,  cm.id as coursemoduleid
			FROM {" . BLOCK_EXAQUEST_DB_QUIZSTATUS . "} quizstatus
			JOIN {quiz} q on q.id = quizstatus.quizid 
			JOIN {course_modules} cm on cm.instance = q.id
			JOIN {modules} m on m.id = cm.module
			WHERE quizstatus.status = :status
			AND quizstatus.coursecategoryid = :coursecategoryid
			AND m.name = 'quiz'";

        /**
         * SELECT q.id as quizid, q.name as name, cm.id as coursemoduleid, cm.module as module
         * FROM mdl_block_exaquestquizstatus quizstatus
         * JOIN mdl_quiz q on q.id = quizstatus.quizid
         * JOIN mdl_course_modules cm on cm.instance = q.id
         * JOIN mdl_modules m on m.id = cm.module
         * WHERE quizstatus.status = 4
         * AND quizstatus.coursecategoryid = 2
         * AND m.name = "quiz"
         * faster without the join on modules by simply checking AND cm.module = 18... but what if that changes one day? ==> JOIN
         */

        $quizzes = $DB->get_records_sql($sql,
            array("status" => $status, "coursecategoryid" => $coursecategoryid));
    } else {
        $sql = "SELECT q.id as quizid, q.name as name,  cm.id as coursemoduleid, q.timeclose as timeclose
			FROM {" . BLOCK_EXAQUEST_DB_QUIZSTATUS . "} quizstatus
			JOIN {quiz} q on q.id = quizstatus.quizid 
			JOIN {course_modules} cm on cm.instance = q.id
			JOIN {modules} m on m.id = cm.module
			WHERE quizstatus.status = :status
			AND m.name = 'quiz'";
        $quizzes = $DB->get_records_sql($sql,
            array("status" => $status));
    }

    return $quizzes;
}

/**
 * Set status of exam.
 *
 * @param $quizid
 * @param $status
 * @return array
 */
function block_exaquest_exams_set_status($quizid, $status) {
    global $DB;
    $record = $DB->get_record('block_exaquestquizstatus', array("quizid" => $quizid));
    $record->status = $status;
    $DB->update_record('block_exaquestquizstatus', $record);
}

function is_exaquest_active_in_course() {
    global $COURSE, $PAGE, $CFG;

    $page = new moodle_page();
    $page->set_url('/course/view.php', array('id' => $COURSE->id));
    $page->set_pagelayout('course');
    $page->set_course($COURSE);

    $blockmanager = $page->blocks;

    $blockmanager->load_blocks(true);

    foreach ($blockmanager->get_regions() as $region) {
        foreach ($blockmanager->get_blocks_for_region($region) as $block) {
            $instance = $block->instance;
            if ($instance->blockname == "exaquest") {
                return true;
            }
        }
    }
    return false;
}

// don't use this. It is not complete, and not useful. Would always have to update.
function block_exaquest_get_capabilities($context) {
    global $USER;
    $capabilities = [];
    // roles
    $capabilities["modulverantwortlicher"] = is_enrolled($context, $USER, "block/exaquest:modulverantwortlicher");
    $capabilities["fragenersteller"] = is_enrolled($context, $USER, "block/exaquest:fragenersteller");
    $capabilities["fachlfragenreviewer"] = is_enrolled($context, $USER, "block/exaquest:fachlfragenreviewer");
    $capabilities["pruefungskoordination"] = is_enrolled($context, $USER, "block/exaquest:pruefungskoordination");
    $capabilities["admintechnpruefungsdurchf"] = is_enrolled($context, $USER, "block/exaquest:admintechnpruefungsdurchf");
    $capabilities["pruefungsstudmis"] = is_enrolled($context, $USER, "block/exaquest:pruefungsstudmis");
    $capabilities["beurteilungsmitwirkende"] = is_enrolled($context, $USER, "block/exaquest:beurteilungsmitwirkende");
    $capabilities["fachlicherpruefer"] = is_enrolled($context, $USER, "block/exaquest:fachlicherpruefer");
    $capabilities["pruefungsmitwirkende"] = is_enrolled($context, $USER, "block/exaquest:pruefungsmitwirkende");
    $capabilities["fachlicherzweitpruefer"] = is_enrolled($context, $USER, "block/exaquest:fachlicherzweitpruefer");

    // capabilities defined by ZML
    $capabilities["viewquestionstorevise"] = is_enrolled($context, $USER, "block/exaquest:viewquestionstorevise");
    $capabilities["createquestion"] = is_enrolled($context, $USER, "block/exaquest:createquestion");
    $capabilities["releasequestion"] = is_enrolled($context, $USER, "block/exaquest:releasequestion");
    $capabilities["readallquestions"] = is_enrolled($context, $USER, "block/exaquest:readallquestions");
    $capabilities["readquestionstatistics"] = is_enrolled($context, $USER, "block/exaquest:readquestionstatistics");
    $capabilities["changestatusofreleasedquestions"] =
        is_enrolled($context, $USER, "block/exaquest:changestatusofreleasedquestions");
    $capabilities["setstatustoreview"] = is_enrolled($context, $USER, "block/exaquest:setstatustoreview");
    $capabilities["reviseownquestion"] = is_enrolled($context, $USER, "block/exaquest:reviseownquestion");
    $capabilities["setstatustofinalised"] = is_enrolled($context, $USER, "block/exaquest:setstatustofinalised");
    $capabilities["viewownrevisedquestions"] = is_enrolled($context, $USER, "block/exaquest:viewownrevisedquestions");
    $capabilities["viewquestionstoreview"] = is_enrolled($context, $USER, "block/exaquest:viewquestionstoreview");
    $capabilities["editquestiontoreview"] = is_enrolled($context, $USER, "block/exaquest:editquestiontoreview");
    $capabilities["viewfinalisedquestions"] = is_enrolled($context, $USER, "block/exaquest:viewfinalisedquestions");
    $capabilities["viewquestionstorevise"] = is_enrolled($context, $USER, "block/exaquest:viewquestionstorevise");
    $capabilities["editallquestions"] = is_enrolled($context, $USER, "block/exaquest:editallquestions");
    $capabilities["addquestiontoexam"] = is_enrolled($context, $USER, "block/exaquest:addquestiontoexam");
    $capabilities["releaseexam"] = is_enrolled($context, $USER, "block/exaquest:releaseexam");
    $capabilities["doformalreview"] = is_enrolled($context, $USER, "block/exaquest:doformalreview");
    $capabilities["executeexam"] = is_enrolled($context, $USER, "block/exaquest:executeexam");
    $capabilities["assignsecondexaminator"] = is_enrolled($context, $USER, "block/exaquest:assignsecondexaminator");
    $capabilities["definequestionblockingtime"] = is_enrolled($context, $USER, "block/exaquest:definequestionblockingtime");
    $capabilities["viewexamresults"] = is_enrolled($context, $USER, "block/exaquest:viewexamresults");
    $capabilities["gradeexam"] = is_enrolled($context, $USER, "block/exaquest:gradeexam");
    $capabilities["createexamstatistics"] = is_enrolled($context, $USER, "block/exaquest:createexamstatistics");
    $capabilities["viewexamstatistics"] = is_enrolled($context, $USER, "block/exaquest:viewexamstatistics");
    $capabilities["correctexam"] = is_enrolled($context, $USER, "block/exaquest:correctexam");
    $capabilities["acknowledgeexamcorrection"] = is_enrolled($context, $USER, "block/exaquest:acknowledgeexamcorrection");
    $capabilities["releaseexamgrade"] = is_enrolled($context, $USER, "block/exaquest:releaseexamgrade");
    $capabilities["releasecommissionalexamgrade"] = is_enrolled($context, $USER, "block/exaquest:releasecommissionalexamgrade");
    $capabilities["exportgradestokusss"] = is_enrolled($context, $USER, "block/exaquest:exportgradestokusss");
    $capabilities["executeexamreview"] = is_enrolled($context, $USER, "block/exaquest:executeexamreview");
    $capabilities["addparticipanttomodule"] = is_enrolled($context, $USER, "block/exaquest:addparticipanttomodule");
    $capabilities["assignroles"] = is_enrolled($context, $USER, "block/exaquest:assignroles");
    $capabilities["changerolecapabilities"] = is_enrolled($context, $USER, "block/exaquest:changerolecapabilities");
    $capabilities["createroles"] = is_enrolled($context, $USER, "block/exaquest:createroles");

    // created during development
    //$capabilities["viewstatistic"] = is_enrolled($context, $USER, "block/exaquest:viewstatistic");
    $capabilities["viewquestionstorelease"] = is_enrolled($context, $USER, "block/exaquest:viewquestionstorelease");
    $capabilities["viewquestionstorevise"] = is_enrolled($context, $USER, "block/exaquest:viewquestionstorevise");
    $capabilities["createexam"] = has_capability("block/exaquest:viewquestionstorevise", $context,
        $USER); // has_capability better than is_enrolled in this case, todo: change above
    $capabilities["setquestioncount"] = has_capability("block/exaquest:setquestioncount", $context, $USER);

    return $capabilities;
}

function block_exaquest_get_all_exaquest_users() {
    // get all exaquest courses and then for each course the users ==> get all exaquest users
    $courseids = block_exaquest_get_courseids();
    $users_merged = [];
    $users_unique = [];
    foreach ($courseids as $courseid) {
        $context = context_course::instance($courseid);
        $users_in_course = get_users_by_capability($context, 'block/exaquest:exaquestuser', 'u.id');
        $users_merged = array_merge($users_merged, $users_in_course);
    }

    // array_unique does not work for these objects ==> loop
    foreach ($users_merged as $user) {
        if (!in_array($user, $users_unique)) {
            $users_unique[] = $user;
        }
    }
    return $users_unique;
}

function block_exaquest_get_all_pruefungskoordination_users() {
    // get all exaquest courses and then for each course the users ==> get all exaquest users
    $courseids = block_exaquest_get_courseids();
    $users_merged = [];
    $users_unique = [];
    foreach ($courseids as $courseid) {
        $context = context_course::instance($courseid);
        $users_in_course = get_users_by_capability($context, 'block/exaquest:pruefungskoordination', 'u.id');
        $users_merged = array_merge($users_merged, $users_in_course);
    }

    // array_unique does not work for these objects ==> loop
    foreach ($users_merged as $user) {
        if (!in_array($user, $users_unique)) {
            $users_unique[] = $user;
        }
    }
    return $users_unique;
}

function block_exaquest_get_pruefungskoodrination_by_courseid($courseid) {
    $context = context_course::instance($courseid);
    return get_enrolled_users($context, 'block/exaquest:pruefungskoordination');
}

function block_exaquest_create_daily_notifications() {
    global $USER;

    $users = block_exaquest_get_all_exaquest_users();

    foreach ($users as $user) {
        // get the todocount and create the todos notification
        $courseids = block_exaquest_get_courseids_of_relevant_courses_for_user($user->id);
        $todosmessage = '';
        foreach ($courseids as $courseid) {
            $course = get_course($courseid);
            $context = \context_course::instance($courseid);
            $todocount = block_exaquest_get_todo_count($user->id, $course->category, $context);
            if ($todocount) {
                // create the message
                $messageobject = new stdClass();
                $messageobject->todoscount = $todocount;
                $messageobject->fullname = $course->fullname;
                $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $courseid]);
                $messageobject->url = $messageobject->url->raw_out(false);
                $todosmessage .= get_string('todos_in_course', 'block_exaquest', $messageobject);
            }
        }
        if ($todosmessage != '') {
            $messageobject = new stdClass();
            $messageobject->todosmessage = $todosmessage;
            $message = get_string('dailytodos', 'block_exaquest', $messageobject);
            $subject = get_string('dailytodos_subject', 'block_exaquest');
            $url_to_moodle_dashboard = new moodle_url('/my/index.php');
            $url_to_moodle_dashboard = $url_to_moodle_dashboard->raw_out();
            block_exaquest_send_moodle_notification("dailytodos", $USER->id, $user->id, $subject, $message,
                "TODOs", $url_to_moodle_dashboard);
        }
    }

    // TODO check that logic, not finished yet
    // get the PK of all courses and send notification about released questions
    $pks = block_exaquest_get_all_pruefungskoordination_users();
    foreach ($pks as $pk) {
        $courseids = block_exaquest_get_courseids_of_relevant_courses_for_user($user->id);
        $daily_released_questions_message = '';
        foreach ($courseids as $courseid) {
            if (has_capability('block/exaquest:pruefungskoordination', \context_course::instance($courseid),
                $pk->id)) { // could have another role in this course ==> skip
                $course = get_course($courseid);
                $daily_released_questions = get_daily_released_questions($course->category);
                if ($daily_released_questions) {
                    // create the message
                    $messageobject = new stdClass();
                    $messageobject->daily_released_questions = $daily_released_questions;
                    $messageobject->fullname = $course->fullname;
                    $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $courseid]);
                    $messageobject->url = $messageobject->url->raw_out(false);
                    $daily_released_questions_message .= get_string('daily_released_questions_in_course', 'block_exaquest',
                        $messageobject);
                }
            }
        }
        if ($daily_released_questions_message != '') {
            $messageobject = new stdClass();
            $messageobject->daily_released_questions_message = $daily_released_questions_message;
            $message = get_string('daily_released_questions', 'block_exaquest', $messageobject);
            $subject = get_string('daily_released_questions_subject', 'block_exaquest');
            $url_to_moodle_dashboard = new moodle_url('/my/index.php');
            $url_to_moodle_dashboard = $url_to_moodle_dashboard->raw_out();
            block_exaquest_send_moodle_notification('daily_released_questions', $USER->id, $pk->id, $subject, $message,
                'Daily released questions', $url_to_moodle_dashboard);
        }
    }

    //$courseids = block_exaquest_get_courseids();
    //foreach ($courseids as $courseid){
    //    $context = context_course::instance($courseid);
    //    $pks = get_enrolled_users($context, 'block/exaquest:pruefungskoordination');

    //}

}

/**
 * @param $courseid
 * returns the count of how many questions have been released in which course
 */
function get_daily_released_questions($coursecategoryid) {
    global $DB;

    $time_last_day = time() - 86400; // current time - 24*60*60 to have time of 24h hours ago.
    // anything that has a timestamp larger than 24h ago has been done yesterday

    $sql = 'SELECT qs.id
			FROM {' . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . '} qs
			WHERE qs.coursecategoryid = :coursecategoryid
			AND qs.timestamp > :timelastday
			AND qs.status = ' . BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED;

    $questions = $DB->get_records_sql($sql, array('coursecategoryid' => $coursecategoryid, 'timelastday' => $time_last_day));
    return count($questions);
}

/**
 * clean up exaquest tables. E.g. delete entries in questionstatus that have no questionbankentry related.
 */
function block_exaquest_clean_up_tables() {
    global $DB;

    //$DB->delete_records_select(BLOCK_EXACOMP_DB_COMPETENCES,
    //    "userid=? AND timestamp<=?", [$studentid, $time]);

    //$sql = "DELETE FROM {{$table}}
    //					WHERE source >= " . data::MIN_SOURCE_ID . "
    //					AND source NOT IN (SELECT id FROM {" . BLOCK_EXACOMP_DB_DATASOURCES . "})
    //				";

    // delete entries in exaquestquestionstatus that have no questionbankentry related
    $sql = 'DELETE FROM {' . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . '}
    WHERE questionbankentryid NOT IN (SELECT id FROM {question_bank_entries})';
    $DB->execute($sql);

    // delete entries in exaquestquizstatus that have no quiz related
    $sql = 'DELETE FROM {' . BLOCK_EXAQUEST_DB_QUIZSTATUS . '}
    WHERE quizid NOT IN (SELECT id FROM {quiz})';
    $DB->execute($sql);

    // delete entries in exaquestquizassig that have no quiz related
    $sql = 'DELETE FROM {' . BLOCK_EXAQUEST_DB_QUIZASSIGN . '}
    WHERE quizid NOT IN (SELECT id FROM {quiz})';
    $DB->execute($sql);

    // delete entries in exaquestquizcomment that have no quiz related
    $sql = 'DELETE FROM {' . BLOCK_EXAQUEST_DB_QUIZCOMMENT . '}
    WHERE quizid NOT IN (SELECT id FROM {quiz})';
    $DB->execute($sql);

    // delete entries in exaquestquizqcount that have no quiz related
    $sql = 'DELETE FROM {' . BLOCK_EXAQUEST_DB_QUIZQCOUNT . '}
    WHERE quizid NOT IN (SELECT id FROM {quiz})';
    $DB->execute($sql);

    // delete entries in exaquestreviewassign that have no questionbankentry related
    $sql = 'DELETE FROM {' . BLOCK_EXAQUEST_DB_REVIEWASSIGN . '}
    WHERE questionbankentryid NOT IN (SELECT id FROM {question_bank_entries})';
    $DB->execute($sql);

    // delete entries in exaquestreviewassign that do not have the status that they should be reviewed
    $sql = 'DELETE FROM {' . BLOCK_EXAQUEST_DB_REVIEWASSIGN . '}
    WHERE questionbankentryid NOT IN (SELECT id FROM {' . BLOCK_EXAQUEST_DB_QUESTIONSTATUS .
        '} WHERE  status = ' . BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS . ' OR status = ' .
        BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE . ' OR status = ' . BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE .
        ')';
    $DB->execute($sql);
}

function block_exaquest_assign_quiz_addquestions($courseid, $userfrom, $userto, $comment, $quizid, $quizname = null,
    $assigntype = null) {
    global $DB, $COURSE;

    // delete existing entries in BLOCK_EXAQUEST_DB_QUIZASSIGN for that exact quizid, assigneeid and assigntype. ? TODO: what to do if 2 requests are made... for now keep them both
    // enter data into the exaquest tables
    $assigndata = new stdClass;
    $assigndata->quizid = $quizid;
    $assigndata->assigneeid = $userto;
    $assigndata->assigntype = $assigntype;
    $quizassignid = $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZASSIGN, $assigndata);

    // insert comment into BLOCK_EXAQUEST_DB_QUIZCOMMENT
    if ($comment != '') {
        $commentdata = new stdClass;
        $commentdata->quizid = $quizid;
        $commentdata->commentorid = $userfrom->id;
        $commentdata->quizassignid = $quizassignid;
        $commentdata->comment = $comment;
        $commentdata->timestamp = time();
        $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZCOMMENT, $commentdata);
    }

    // create the message
    $messageobject = new stdClass;
    $messageobject->fullname = $quizname;
    $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $COURSE->id]);
    $messageobject->url = $messageobject->url->raw_out(false);
    $messageobject->requestcomment = $comment;
    $message = get_string('please_fill_exam', 'block_exaquest', $messageobject);
    $subject = get_string('please_fill_exam_subject', 'block_exaquest', $messageobject);
    block_exaquest_send_moodle_notification("fillexam", $userfrom->id, $userto, $subject, $message,
        "fillexam", $messageobject->url);
}

function block_exaquest_assign_quiz_fp($userto, $quizid) {
    global $DB, $COURSE;

    // delete existing entries in BLOCK_EXAQUEST_DB_QUIZASSIGN for that quizid and assigntype, as it should be overridden (there can only be one)
    $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN,
        array('quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER));

    // enter data into the exaquest tables
    $assigndata = new stdClass;
    $assigndata->quizid = $quizid;
    $assigndata->assigneeid = $userto;
    $assigndata->assigntype = BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER;
    $quizassignid = $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZASSIGN, $assigndata);

    //// insert comment into BLOCK_EXAQUEST_DB_QUIZCOMMENT
    //if ($comment != '') {
    //    $commentdata = new stdClass;
    //    $commentdata->quizid = $quizid;
    //    $commentdata->commentorid = $userfrom->id;
    //    $commentdata->quizassignid = $quizassignid;
    //    $commentdata->comment = $comment;
    //    $commentdata->timestamp = time();
    //    $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZCOMMENT, $commentdata);
    //}
    //
    //// create the message
    //$messageobject = new stdClass;
    //$messageobject->fullname = $quizname;
    //$messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $COURSE->id]);
    //$messageobject->url = $messageobject->url->raw_out(false);
    //$messageobject->requestcomment = $comment;
    //$message = get_string('please_fill_exam', 'block_exaquest', $messageobject);
    //$subject = get_string('please_fill_exam_subject', 'block_exaquest', $messageobject);
    //block_exaquest_send_moodle_notification("fillexam", $userfrom->id, $userto, $subject, $message,
    //    "fillexam", $messageobject->url);
}

function block_exaquest_get_fragefaecher_by_courseid_and_quizid($courseid, $quizid) {
    global $DB;

    // get course
    $course = $DB->get_record('course', array('id' => $courseid));

    $sql = 'SELECT cat.*, qc.questioncount, qc.quizid
            FROM {' . BLOCK_EXAQUEST_DB_CATEGORIES . '} cat
            LEFT JOIN {' . BLOCK_EXAQUEST_DB_QUIZQCOUNT . '} qc ON qc.exaquestcategoryid = cat.id AND qc.quizid = :quizid
            WHERE cat.coursecategoryid = :coursecategoryid
            AND cat.categorytype = ' . BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH;

    $fragefaecher = $DB->get_records_sql($sql, array('coursecategoryid' => $course->category, 'quizid' => $quizid));

    return $fragefaecher;
}

function block_exaquest_get_assigned_fachlicherpruefer($quizid) {
    global $DB;
    $sql = 'SELECT qa.assigneeid
			FROM {' . BLOCK_EXAQUEST_DB_QUIZASSIGN . '} qa
			WHERE qa.quizid = :quizid
			AND qa.assigntype = ' . BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER;

    $fachlicherpreufer = $DB->get_record_sql($sql,
        array('quizid' => $quizid));
    return $fachlicherpreufer;
}

function block_exaquest_get_fragefaecher_by_courseid($courseid) {
    global $DB;

    // get course
    $course = $DB->get_record('course', array('id' => $courseid));

    $sql = 'SELECT cat.*
            FROM {' . BLOCK_EXAQUEST_DB_CATEGORIES . '} cat
            WHERE cat.coursecategoryid = :coursecategoryid
            AND cat.categorytype = ' . BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH;

    $fragefaecher = $DB->get_records_sql($sql, array('coursecategoryid' => $course->category));

    return $fragefaecher;

}

function block_exaquest_set_questioncount_for_exaquestcategory($quizid, $exaquestcategoryid, $count) {
    global $DB;

    // get the current questioncount for this quiz and exaquestcategory
    $quizqcount =
        $DB->get_record(BLOCK_EXAQUEST_DB_QUIZQCOUNT, array('quizid' => $quizid, 'exaquestcategoryid' => $exaquestcategoryid));

    // if it exists: update, else: create new
    if ($quizqcount) {
        $quizqcount->questioncount = $count;
        $DB->update_record(BLOCK_EXAQUEST_DB_QUIZQCOUNT, $quizqcount);
    } else {
        $quizqcount = new stdClass;
        $quizqcount->quizid = $quizid;
        $quizqcount->exaquestcategoryid = $exaquestcategoryid;
        $quizqcount->questioncount = $count;
        $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZQCOUNT, $quizqcount);
    }
}

// this is the check, returns if imported, does not set any status
function block_exaquest_check_if_question_is_imported($questionid) {
    $handler = \qbank_customfields\customfield\question_handler::create();
    $datas = $handler->get_instance_data($questionid);
    // get the set customfields
    // if there is a field from the type "exaquestcategory" that is set, then it is a question that has been created in exaquest (since these are mandatory fields)
    // if there is a field from the type "exaquestcategory" that is NOT set, then it is a question that has been imported from exaquest (since these are mandatory fields)
    // $data->get_field()->get("type")
    $is_imported = false;
    foreach ($datas as $data) {
        // if it is not an exaquestcategory, then continue, since we are only interested in if exaquestcategories are set or not
        if ($data->get_field()->get('type') != 'exaquestcategory') {
            continue;
        }
        if ($data->get_value() == $data->get_default_value()) { // default value means nothing is set
            // this question has been imported
            $is_imported = true;
            break; // as soon as we find one exaquestcategory that is not set, we can stop searching, as it must be imported
        }
    }
    return $is_imported;
}

function block_exaquest_check_if_questions_imported($questionid, $questionbankentryid) {
    global $DB;
    $is_imported = block_exaquest_check_if_question_is_imported($questionid);
    if ($is_imported) {
        // it is imported --> set status to imported
        $questionstatus = $DB->get_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, ["questionbankentryid" => $questionbankentryid]);
        $questionstatus->status = BLOCK_EXAQUEST_QUESTIONSTATUS_IMPORTED;
        $questionstatus->is_imported = 1;
        $DB->update_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $questionstatus);
    } else {
        // it has been created manually --> everything can stay as it is
    }
}