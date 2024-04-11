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

use block_exaquest\output\popup_exams_for_me_to_fachlich_release;
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
//const BLOCK_EXAQUEST_QUIZSTATUS_FORMAL_RELEASED = 3; // MUSSS released it
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
const BLOCK_EXAQUEST_QUIZASSIGNTYPE_GRADE_EXAM = 5;
const BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHECK_EXAM_GRADING = 6;
const BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERZWEITPRUEFER = 7;
const BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERDRITTPRUEFER = 8;
const BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHANGE_EXAM_GRADING = 9;
// TODO add quizassigntype for finished exam for the PK
const BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_OPEN = 10;
const BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_DONE = 11;
const BLOCK_EXAQUEST_QUIZASSIGNTYPE_KOMMISSIONELL_CHECK_EXAM_GRADING = 12;

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
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_LOCKED_QUESTIONS = 14;

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

function block_exaquest_request_review($userfrom, $userto, $comment, $questionbankentryid, $questionname,
        $courseid,
        $reviewtype) {
    global $DB, $COURSE;
    // enter data into the exaquest tables
    $assigndata = new stdClass;
    $assigndata->questionbankentryid = $questionbankentryid;
    $assigndata->reviewerid = $userto;
    $assigndata->reviewtype = $reviewtype;
    //    $assigndata->coursecategoryid = $coursecategoryid;
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

function block_exaquest_request_revision($userfrom, $userto, $comment, $questionbankentryid, $questionname,
        $courseid, $link_to_released_questions = false, $questionid = 0) {
    global $DB, $COURSE;
    // enter data into the exaquest tables
    $assigndata = new stdClass;
    $assigndata->questionbankentryid = $questionbankentryid;
    $assigndata->reviserid = $userto;
    $DB->insert_record(BLOCK_EXAQUEST_DB_REVISEASSIGN, $assigndata);

    // create the message
    $messageobject = new stdClass;
    $messageobject->fullname = $questionname;
    //$messageobject->url = new moodle_url('/blocks/exaquest/questbank.php', array('courseid' => $courseid, 'category' => $catAndCont[0] . ',' . $catAndCont[1]));
    if ($link_to_released_questions) { // if the question status is NOT changed from the reviseassign, link to editquestion
        // e.g. http://localhost/question/bank/editquestion/question.php?returnurl=%2Fblocks%2Fexaquest%2Fquestbank.php%3Fcourseid%3D3&courseid=3&id=1
        $messageobject->url = new moodle_url('/question/bank/editquestion/question.php?',
                array('courseid' => $courseid, 'id' => $questionid,
                        'returnurl' => '/blocks/exaquest/dashboard.php?courseid=' . $courseid));
    } else {
        $messageobject->url = new moodle_url('/blocks/exaquest/questbank.php',
                array('courseid' => $courseid, 'filterstatus' => BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVISE));
    }
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
    $userarray = array_replace($userarray,
            get_enrolled_users($context, 'block/exaquest:fragenerstellerlight', 0, 'u.*', null, 0, 0, true));

    $userarray = array_replace($userarray,
            get_enrolled_users($context, 'block/exaquest:fragenersteller', 0, 'u.*', null, 0, 0, true));
    return $userarray;
}

/**
 *
 * Returns all pk of this course
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_pk_by_courseid($courseid) {
    $context = context_course::instance($courseid);
    $userarray = array();
    $userarray = array_replace($userarray,
            get_enrolled_users($context, 'block/exaquest:pruefungskoordination', 0, 'u.*', null, 0, 0, true));

    return $userarray;
}

/**
 *
 * Returns all pmw/prüfungsmitwirkender of this course
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_pmw_by_courseid($courseid) {
    $context = context_course::instance($courseid);
    $userarray = array();
    $userarray = array_merge($userarray,
            get_enrolled_users($context, 'block/exaquest:pruefungsmitwirkende', 0, 'u.*', null, 0, 0, true));
    return $userarray;
}

/**
 *
 * Returns all bmw/beurteilungsmitwirkender of this course
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_bmw_by_courseid($courseid) {
    $context = context_course::instance($courseid);
    $userarray = array();
    $userarray = array_merge($userarray,
            get_enrolled_users($context, 'block/exaquest:beurteilungsmitwirkende', 0, 'u.*', null, 0, 0, true));
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
    return get_enrolled_users($context, 'block/exaquest:fachlicherpruefer', 0, 'u.*', null, 0, 0, true);
}

/**
 *
 * Returns all fachlichezweitpruefer of this course
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_fachlichezweitpruefer_by_courseid($courseid) {
    $context = context_course::instance($courseid);
    return get_enrolled_users($context, 'block/exaquest:fachlichezweitpruefer', 0, 'u.*', null, 0, 0, true);
}

/**
 *
 * Returns all fachlichedrittpruefer of this course
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_fachlichedrittpruefer_by_courseid($courseid) {
    $context = context_course::instance($courseid);
    return get_enrolled_users($context, 'block/exaquest:fachlichedrittpruefer', 0, 'u.*', null, 0, 0, true);
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
    return get_enrolled_users($context, 'block/exaquest:modulverantwortlicher', 0, 'u.*', null, 0, 0, true);
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
    $userarray =
            array_merge($userarray, get_enrolled_users($context, 'block/exaquest:fachlfragenreviewer', 0, 'u.*', null, 0, 0, true));
    $userarray = array_merge($userarray,
            get_enrolled_users($context, 'block/exaquest:fachlfragenreviewerlight', 0, 'u.*', null, 0, 0, true));
    //$userarray = array_merge($userarray, get_enrolled_users($context,
    //    'block/exaquest:pruefungskoordination')); // according to 20230112 Feedbackliste. But should they really have the right to review? nope, they should not be here, as told in later feedbackliste 20230323
    $userarray = array_unique($userarray, SORT_REGULAR); // to remove users who have multiple roles
    return $userarray;
}

/**
 * Returns count of all questionbankentries (all entries in exaquestqeustionstatus)
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questionbankentries_by_questioncategoryid_count($questioncategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qs.questionbankentryid = qbe.id
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.status != " .
            BLOCK_EXAQUEST_QUESTIONSTATUS_IMPORTED; // "all" questions except imported ones that have not been released yet

    // we simply count the exaquestquestionstatus entries for this course, so we do not need to have the category, do not read unneccesary entries in the question_bank_entries etc

    $questions = count($DB->get_records_sql($sql, array("questioncategoryid" => $questioncategoryid)));

    // TODO: check the questionlib for functions like get_question_bank_entry( that could be useful

    return $questions;
}

/**
 * Returns count of all questionbankentries (all entries in exaquestqeustionstatus)
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_my_questionbankentries_count($questioncategoryid, $userid) {
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }
    $sql = "SELECT qs.id
              FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
              JOIN {question_bank_entries} qbe ON qbe.id = qs.questionbankentryid 
             WHERE qbe.questioncategoryid = :questioncategoryid
             AND qbe.ownerid = :ownerid";

    $questions = count($DB->get_records_sql($sql, array("questioncategoryid" => $questioncategoryid, "ownerid" => $userid)));

    return $questions;
}

/**
 * Returns count of all questionbankentries (all entries in exaquestqeustionstatus)
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_my_questionbankentries_to_submit_count($questioncategoryid, $userid) {
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }
    $sql = "SELECT qs.id
              FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
              JOIN {question_bank_entries} qbe ON qbe.id = qs.questionbankentryid 
             WHERE qbe.questioncategoryid = :questioncategoryid
             AND qbe.ownerid = :ownerid
             AND qs.status = :newquestion";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid, "ownerid" => $userid,
                    "newquestion" => BLOCK_EXAQUEST_QUESTIONSTATUS_NEW)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questionbankentries_to_be_reviewed_count($questioncategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qs.questionbankentryid = qbe.id
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND (qs.status = :fachlichreviewdone
			OR qs.status = :formalreviewdone
			OR qs.status = :toassess)";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid,
                    "fachlichreviewdone" => BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE,
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
function block_exaquest_get_questionbankentries_to_be_revised_count($questioncategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qs.questionbankentryid = qbe.id
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.status = :questionstatus";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid, "questionstatus" => BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE)));

    return $questions;

}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questionbankentries_new_count($questioncategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qs.questionbankentryid = qbe.id
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.status = :questionstatus";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid, "questionstatus" => BLOCK_EXAQUEST_QUESTIONSTATUS_NEW)));

    return $questions;

}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questionbankentries_formal_reviewed_count($questioncategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qs.questionbankentryid = qbe.id
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.status = :formalreviewdone";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid,
                    "formalreviewdone" => BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questionbankentries_fachlich_reviewed_count($questioncategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qs.questionbankentryid = qbe.id
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.status = :fachlichreviewdone";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid,
                    "fachlichreviewdone" => BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_my_questionbankentries_to_be_reviewed_count($questioncategoryid, $userid) {
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qbe.id = qs.questionbankentryid 
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND (qs.status = :fachlichreviewdone
			OR qs.status = :formalreviewdone
			OR qs.status = :toassess)
            AND qbe.ownerid = :ownerid";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid,
                    "fachlichreviewdone" => BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE,
                    "formalreviewdone" => BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE,
                    "toassess" => BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS, "ownerid" => $userid)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_released_questionbankentries_count($questioncategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qs.questionbankentryid = qbe.id
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.status = :finalised";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid, "finalised" => BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED)));

    return $questions;
}

/**
 * Returns count of
 *
 * Locked
 *
 * @param $coursecategoryid
 * @return array
 */

function block_exaquest_get_locked_questionbankentries_count($questioncategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qs.questionbankentryid = qbe.id
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.status = :locked";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid, "locked" => BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_released_and_to_review_questionbankentries_count($questioncategoryid) {
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qs.questionbankentryid = qbe.id
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.status = :finalised";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid, "finalised" => BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_finalised_questionbankentries_count($questioncategoryid) {
    // the same as to_release in most cases
    global $DB;
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qs.questionbankentryid = qbe.id
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.status = :finalised";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid, "finalised" => BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_my_finalised_questionbankentries_count($questioncategoryid, $userid) {
    // the same as to_release in most cases
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qbe ON qbe.id = qs.questionbankentryid 
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.status = :finalised
			AND qbe.ownerid = :ownerid";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid, "finalised" => BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED,
                    "ownerid" => $userid)));

    return $questions;
}

function block_exaquest_get_quizzes_for_me_to_fill_count($userid) {
    return count(block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid, BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS,
            BLOCK_EXAQUEST_QUIZSTATUS_NEW));
}

function block_exaquest_get_exams_finished_grading_open_count($userid) {
    return count(block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid,
            BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_OPEN,
            BLOCK_EXAQUEST_QUIZSTATUS_FINISHED));
}

function block_exaquest_get_exams_finished_grading_done_count($userid) {
    return count(block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid,
            BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_DONE,
            BLOCK_EXAQUEST_QUIZSTATUS_FINISHED));
}

function block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid, $assigntype, $quizstatus, $courseid = 0) {
    global $DB, $USER, $COURSE;

    if ($courseid == 0) {
        $courseid = $COURSE->id;
    }

    if (!$userid) {
        $userid = $USER->id;
    }
    // questionbankentryid DISTINCT to not count twice
    $sql = 'SELECT DISTINCT qa.quizid as quizid, q.name as name,  cm.id as coursemoduleid, qa.done
			FROM {' . BLOCK_EXAQUEST_DB_QUIZASSIGN . '} qa
			JOIN {' . BLOCK_EXAQUEST_DB_QUIZSTATUS . '} qs on qs.quizid = qa.quizid
			JOIN {quiz} q on q.id = qa.quizid 
			JOIN {course_modules} cm on cm.instance = q.id
			JOIN {modules} m on m.id = cm.module
			WHERE qa.assigneeid = :userid
			AND qa.assigntype = :assigntype
			AND qs.status = :quizstatus
            AND m. name = "quiz"
            AND q.course = :courseid'; // otherwise you would have a problem when you have 2 different courses in the same category.
    // The quiz would not show up in the e.g. new-exams but the assignment would

    $quizzes = $DB->get_records_sql($sql,
            array('userid' => $userid, 'assigntype' => $assigntype, 'quizstatus' => $quizstatus, 'courseid' => $courseid));
    return $quizzes;
}

//-----------

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questions_for_me_to_review_count($questioncategoryid, $userid = 0) {
    // get from the table exaquestreviewassing. But there are 2 reviewtypes, do not count twice!
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }

    // questionbankentryid DISTINCT to not count twice
    $sql = 'SELECT DISTINCT ra.questionbankentryid
			FROM {' . BLOCK_EXAQUEST_DB_REVIEWASSIGN . '} ra
			JOIN {question_bank_entries} qbe ON ra.questionbankentryid = qbe.id
			WHERE ra.reviewerid = :userid
			AND qbe.questioncategoryid = :questioncategoryid';
    //AND (ra.reviewtype = '. BLOCK_EXAQUEST_REVIEWTYPE_FORMAL .' OR ra.reviewtype =  '. BLOCK_EXAQUEST_REVIEWTYPE_FACHLICH .')';

    $questions = count($DB->get_records_sql($sql,
            array('userid' => $userid, 'questioncategoryid' => $questioncategoryid)));

    return $questions;
}

/**
 * Returns count of
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questions_for_me_to_create_count($questioncategoryid, $userid = 0) {
    return count(block_exaquest_get_questions_for_me_to_create($questioncategoryid, $userid));
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
function block_exaquest_get_assigned_exams_by_assigntype($courseid, $userid, $assigntype) {
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }

    // get the exams that are assigned to me and that are in the course of the course
    $sql = 'SELECT qa.*, q.name, qc.comment, cm.id as coursemoduleid
			FROM {' . BLOCK_EXAQUEST_DB_QUIZASSIGN . '} qa
			JOIN {quiz} q on q.id = qa.quizid
			JOIN {course_modules} cm on cm.instance = q.id 
			LEFT JOIN {' . BLOCK_EXAQUEST_DB_QUIZCOMMENT . '} qc on qc.quizid = qa.quizid AND qc.quizassignid = qa.id
			WHERE qa.assigneeid = :assigneeid
			AND qa.done = 0
			AND qa.assigntype = :assigntype
            AND q.course = :courseid
            AND cm.module = (SELECT id FROM {modules} WHERE name = "quiz")'; // to get the coursemoduleid for quizzes... "AND cm.module = 17"

    $exams = $DB->get_records_sql($sql,
            array('assigneeid' => $userid, 'courseid' => $courseid, 'assigntype' => $assigntype));

    // Note: this returns multiple lines if there are multiple comments (the same assignment has been made e.g. twice)
    // Either the commenting should be changed, or it is fine to simply display the latest comment, which is the case right now

    return $exams;
}

/**
 * Returns
 *
 * @param $courseid
 * @return array
 */
function block_exaquest_get_assigned_quizzes_by_assigntype_count($courseid, $userid, $assigntype) {
    return count(block_exaquest_get_assigned_exams_by_assigntype($courseid, $userid, $assigntype));
}

/**
 * Returns count of questionbankentries that have to be revised of this course of this user
 * used e.g. for the fragenersteller to see which questions they should revise
 *
 * @param $coursecategoryid
 * @param $userid
 * @return array
 */
function block_exaquest_get_questions_for_me_to_revise_count($questioncategoryid, $userid = 0) {
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }

    $sql = 'SELECT qs.id
			FROM {' . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . '} qs
			JOIN {question_bank_entries} qbe ON qs.questionbankentryid = qbe.id
			JOIN {' . BLOCK_EXAQUEST_DB_REVISEASSIGN . '} qra ON qra.questionbankentryid = qs.questionbankentryid
			WHERE qra.reviserid = :reviserid
			AND qs.status = :status
			AND qbe.questioncategoryid = :questioncategoryid';

    $questions =
            count($DB->get_records_sql($sql, array('reviserid' => $userid, 'status' => BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE,
                    'questioncategoryid' => $questioncategoryid)));

    return $questions;
}

/**
 * Returns count of questions for me to release.
 * TODO: what should that mean? In which case is there a specific person responsible for releasing?
 * most probably: this will not be needed
 *
 * @param $coursecategoryid
 * @return array
 */
function block_exaquest_get_questions_for_me_to_release_count($questioncategoryid, $userid = 0) {
    global $DB, $USER;
    if (!$userid) {
        $userid = $USER->id;
    }
    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {" . BLOCK_EXAQUEST_DB_REVIEWASSIGN . "} qra ON qra.questionbankentryid = qs.questionbankentryid
			JOIN {question_bank_entries} qbe ON qbe.id = qs.questionbankentryid 
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.status = :finalised
			AND qra.reviewerid = :reviewerid";

    $questions = count($DB->get_records_sql($sql,
            array("questioncategoryid" => $questioncategoryid, "finalised" => BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED,
                    "reviewerid" => $userid)));

    return $questions;
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
            'allowassign' => 0,
            'allowoverride' => 0,
            'allowswitch' => 0,
            'allowview' => 0);

    //if (!$DB->record_exists('role', ['shortname' => 'testroleauto'])) {
    //    $roleid = create_role('testroleauto', 'testroleauto', 'testroleauto');
    //    $archetype = 0;
    //    $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
    //    $definitiontable->force_duplicate($archetype,
    //            $options); // overwrites everything that is set in the options. The rest stays.
    //    $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
    //    $definitiontable->save_changes();
    //    $sourcerole = new \stdClass();
    //    $sourcerole->id = $archetype;
    //    role_cap_duplicate($sourcerole, $roleid);
    //
    //    // allow setting role at context level "course category"
    //    set_role_contextlevels($roleid, array(CONTEXT_COURSECAT));
    //} else {
    //    $roleid = $DB->get_record('role', ['shortname' => 'admintechnpruefungsdurchf'])->id;
    //}

    // is this the MUSSS?
    if (!$DB->record_exists('role', ['shortname' => 'admintechnpruefungsdurchf'])) {
        $roleid = create_role('admin./techn. Prüfungsdurchf.', 'admintechnpruefungsdurchf', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype
        //$archetype = 0;
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);

        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT));
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
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:doformalreviewexam', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'pruefungskoordination'])) {
        $roleid = create_role('Prüfungskoordination', 'pruefungskoordination', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);

        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT));
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

    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:releasequestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:requestnewexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:doformalreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:setquestioncount', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changeowner', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:dofachlichreviewexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assigncheckexamgrading', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:skipandreleaseexam', CAP_ALLOW, $roleid, $context);
    //unassign_capability('block/exaquest:skipandreleaseexam', $roleid, $context->id);
    assign_capability('block/exaquest:assigngradeexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changeexamsgrading', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:forcesendexamtoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:checkgradingforfp', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'pruefungsstudmis'])) {
        $roleid = create_role('PrüfungsStudMis', 'pruefungsstudmis', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);

        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT));
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
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assigngradeexam', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:createexam', $roleid, $context->id); // accidentally added, should be deleted
    assign_capability('block/exaquest:forcesendexamtoreview', CAP_ALLOW, $roleid, $context);

    // rework capabilities documentation: start from archetype 0. Almost no rights in exam page needed. Some rights needed for question page.
    if (!$DB->record_exists('role', ['shortname' => 'modulverantwortlicher'])) {
        $roleid = create_role('Modulverantwortlicher', 'modulverantwortlicher', '');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
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
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createquestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorelease', CAP_ALLOW, $roleid, $context);

    assign_capability('block/exaquest:viewquestionstoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:requestnewexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:dofachlichreview', CAP_ALLOW, $roleid, $context);
    //Mover should not be able to do this : assign_capability('block/exaquest:doformalreview', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:doformalreview', $roleid, $context->id); // accidentally added, should be deleted
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:setquestioncount', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changeowner', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:skipandreleaseexam', CAP_ALLOW, $roleid, $context);
    //unassign_capability('mod/quiz:skipandreleaseexam', $roleid, $context->id);
    //unassign_capability('mod/quiz:moodle/course:manageactivities', $roleid, $context->id); // "Prüfungen anlegen und Bearbeiten sollen nur MUSSS, PK und StudMA, nicht MOVER oder andere Rolle können"

    //moodle capabilities:
    assign_capability('moodle/question:add', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:viewmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:movemine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:tagmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:commentmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:editmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:viewall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:moveall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:tagall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:commentall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:editall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:usemine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:useall', CAP_ALLOW, $roleid, $context);

    // UNASSIGN CAPABILITIES from editinteacher. probably not the way to go, but instead start with archetype = 0
    //unassign_capability('mod/quiz:addinstance', $roleid, $context->id);

    if (!$DB->record_exists('role', ['shortname' => 'fragenersteller'])) {
        $roleid = create_role('Fragenersteller', 'fragenersteller', 'This user can only create questions and see the dashboard');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
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
    //moodle capabilities:
    assign_capability('moodle/question:add', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:viewmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:movemine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:tagmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:commentmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:editmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:viewall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:moveall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:tagall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:commentall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:editall', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'fachlfragenreviewer'])) {
        $roleid = create_role('fachl. Fragenreviewer', 'fachlfragenreviewer',
                '');
        $archetype = 0;
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
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
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:dofachlichreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    //moodle capabilities:
    //assign_capability('moodle/question:add', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:viewmine', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:movemine', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:tagmine', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:commentmine', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:editmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:viewall', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:moveall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:tagall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:commentall', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:editall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:useall', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'beurteilungsmitwirkende'])) {
        $roleid = create_role('Beurteilungsmitwirkende', 'beurteilungsmitwirkende', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);

        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'beurteilungsmitwirkende'])->id;
    }
    assign_capability('block/exaquest:beurteilungsmitwirkende', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);

    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:checkexamsgrading', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:gradequestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:gradeexam', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'fachlicherpruefer'])) {
        $roleid = create_role('fachlicher Prüfer', 'fachlicherpruefer', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null. This would read the form data if done by the admin in the settings page
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);

        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT));
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
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:dofachlichreviewexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:checkexamsgrading', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:gradequestion', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:assignaddquestions', $roleid,
            $context->id); // accidentally added, should be deleted. ONLY allow this for your own exams. Only pk and mover can do it generally
    // addquestion will be added in the output/exams.php for every exam the FP is FP of and for every PMW that has been assigned.
    assign_capability('block/exaquest:gradeexam', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'pruefungsmitwirkende'])) {
        $roleid = create_role('Prüfungsmitwirkende', 'pruefungsmitwirkende', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);

        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'pruefungsmitwirkende'])->id;
    }
    assign_capability('block/exaquest:pruefungsmitwirkende', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:assignaddquestions', $roleid, $context->id); // accidentally added, should be deleted

    //assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    //unassign_capability('block/exaquest:createexam', $roleid, $context->id); // accidentally added, should be deleted

    // TODO: is this even a needed role? Or is it actually just "fachlicherpruefer" but assignes as a zweitpruefer to an exam? I guess so...
    if (!$DB->record_exists('role', ['shortname' => 'fachlicherzweitpruefer'])) {
        $roleid = create_role('Fachlicher Zweitprüfer', 'fachlicherzweitpruefer', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);

        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fachlicherzweitpruefer'])->id;
    }
    assign_capability('block/exaquest:fachlicherzweitpruefer', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);

    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:gradeexam', CAP_ALLOW, $roleid, $context);

    // ---
    if (!$DB->record_exists('role', ['shortname' => 'fragenerstellerlight'])) {
        $roleid = create_role('Fragenerstellerlight', 'fragenerstellerlight', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);

        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT));
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
        $roleid = create_role('fachl. Fragenreviewerlight', 'fachlfragenreviewerlight', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);

        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT));
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
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewownquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'sekretariat'])) {
        $roleid = create_role('Sekretariat', 'sekretariat', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);

        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'sekretariat'])->id;
    }
    assign_capability('block/exaquest:sekretariat', CAP_ALLOW, $roleid, $context);
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
    assign_capability('block/exaquest:changeowner', CAP_ALLOW, $roleid, $context);

    //
    //role_assign($roleid, $USER->id, $contextid);

    //if ($roleid = $DB->get_field('role', 'id', array('shortname' => 'custom_role')){
    //$context = \context_system::instance(){;
    //assign_capability('block/custom_block:custom_capability', CAP_ALLOW,
    //    $roleid, $context);
    //}
    // now that every role exists:
    // set the allowassign, allowoverride, allowswitch and allowview for pk, mover and fp
    // get all roleids that are allowed to assign

    $allowedroles = array();
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'admintechnpruefungsdurchf'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'pruefungskoordination'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'pruefungsstudmis'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'modulverantwortlicher'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fragenersteller'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlfragenreviewer'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'beurteilungsmitwirkende'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlicherpruefer'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'pruefungsmitwirkende'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlicherzweitpruefer'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fragenerstellerlight'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlfragenreviewerlight'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'sekretariat'])->id;

    // for every role, allow VIEWING of role, since this is not a problem and NOT seeing the role leads to confusion
    foreach ($allowedroles as $roleid) {
        foreach ($allowedroles as $allowedrole) {
            if (!$DB->get_record('role_allow_view', array('roleid' => $roleid, 'allowview' => $allowedrole))) {
                core_role_set_view_allowed($roleid, $allowedrole);
            }
        }
    }

    // for pk: allow every role
    $roleid = $DB->get_record('role', ['shortname' => 'pruefungskoordination'])->id;

    foreach ($allowedroles as $allowedrole) {
        if (!$DB->get_record('role_allow_override', array('roleid' => $roleid, 'allowoverride' => $allowedrole))) {
            core_role_set_override_allowed($roleid, $allowedrole);
            core_role_set_assign_allowed($roleid, $allowedrole);
            core_role_set_switch_allowed($roleid, $allowedrole);
            //core_role_set_view_allowed($roleid, $allowedrole);
        }
    }

    $allowedroles = array();
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fragenersteller'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlfragenreviewer'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fragenerstellerlight'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlfragenreviewerlight'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'sekretariat'])->id;
    $roleid = $DB->get_record('role', ['shortname' => 'modulverantwortlicher'])->id;
    foreach ($allowedroles as $allowedrole) {
        if (!$DB->get_record('role_allow_override', array('roleid' => $roleid, 'allowoverride' => $allowedrole))) {
            core_role_set_override_allowed($roleid, $allowedrole);
            core_role_set_assign_allowed($roleid, $allowedrole);
            core_role_set_switch_allowed($roleid, $allowedrole);
            //core_role_set_view_allowed($roleid, $allowedrole);
        }
    }

    $allowedroles = array();
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'beurteilungsmitwirkende'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'pruefungsmitwirkende'])->id;
    $roleid = $DB->get_record('role', ['shortname' => 'fachlicherpruefer'])->id;
    foreach ($allowedroles as $allowedrole) {
        if (!$DB->get_record('role_allow_override', array('roleid' => $roleid, 'allowoverride' => $allowedrole))) {
            core_role_set_override_allowed($roleid, $allowedrole);
            core_role_set_assign_allowed($roleid, $allowedrole);
            core_role_set_switch_allowed($roleid, $allowedrole);
            //core_role_set_view_allowed($roleid, $allowedrole);
        }
    }

    // this approach does not work, since it is designed to be done by the admin via menues, so many of the attributes are private
    //$roleid = $DB->get_record('role', ['shortname' => 'pruefungskoordination'])->id;
    //$archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // manager archetype
    //$definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
    //$definitiontable->force_duplicate($archetype,
    //        $options); // overwrites everything that is set in the options. The rest stays.
    //$definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
    //$definitiontable->allowassign = array(7, 8, 9, 10);
    //$definitiontable->save_changes();

    // REWORKING ALL ROLES TO START WITH A WEAKER ARCHETYPE (e.g. no archetype) AND THEN ADD CAPABILITIES
    // is this the MUSSS?
    if (!$DB->record_exists('role', ['shortname' => 'admintechnpruefungsdurchf2'])) {
        $roleid = create_role('admin./techn. Prüfungsdurchf.2', 'admintechnpruefungsdurchf2', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype fits for this role, as it is an adminrole
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'admintechnpruefungsdurchf2'])->id;
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
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:doformalreviewexam', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'pruefungskoordination2'])) {
        $roleid = create_role('Prüfungskoordination2', 'pruefungskoordination2', '', 'editingteacher');
        $archetype = $DB->get_record('role', ['shortname' => 'editingteacher'])->id; // editingteacher archetype fits for this role
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'pruefungskoordination2'])->id;
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

    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:releasequestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:requestnewexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:doformalreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:setquestioncount', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changeowner', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:dofachlichreviewexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assigncheckexamgrading', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:skipandreleaseexam', CAP_ALLOW, $roleid, $context);
    //unassign_capability('block/exaquest:skipandreleaseexam', $roleid, $context->id);
    assign_capability('block/exaquest:assigngradeexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changeexamsgrading', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:forcesendexamtoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:checkgradingforfp', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'pruefungsstudmis2'])) {
        $roleid = create_role('PrüfungsStudMis2', 'pruefungsstudmis2', '');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'pruefungsstudmis2'])->id;
    }
    assign_capability('block/exaquest:pruefungsstudmis', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:readquestionstatistics', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assigngradeexam', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:createexam', $roleid, $context->id); // accidentally added, should be deleted
    assign_capability('block/exaquest:forcesendexamtoreview', CAP_ALLOW, $roleid, $context);

    // rework capabilities documentation: start from archetype 0. Almost no rights in exam page needed. Some rights needed for question page.
    if (!$DB->record_exists('role', ['shortname' => 'modulverantwortlicher2'])) {
        $roleid = create_role('modulverantwortlicher2', 'modulverantwortlicher2', '');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'modulverantwortlicher2'])->id;
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
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createquestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorelease', CAP_ALLOW, $roleid, $context);

    assign_capability('block/exaquest:viewquestionstoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:requestnewexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:dofachlichreview', CAP_ALLOW, $roleid, $context);
    //Mover should not be able to do this : assign_capability('block/exaquest:doformalreview', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:doformalreview', $roleid, $context->id); // accidentally added, should be deleted
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:setquestioncount', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:changeowner', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:skipandreleaseexam', CAP_ALLOW, $roleid, $context);
    //unassign_capability('mod/quiz:skipandreleaseexam', $roleid, $context->id);
    //unassign_capability('mod/quiz:moodle/course:manageactivities', $roleid, $context->id); // "Prüfungen anlegen und Bearbeiten sollen nur MUSSS, PK und StudMA, nicht MOVER oder andere Rolle können"

    //moodle capabilities:
    assign_capability('moodle/question:add', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:viewmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:movemine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:tagmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:commentmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:editmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:viewall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:moveall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:tagall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:commentall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:editall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:usemine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:useall', CAP_ALLOW, $roleid, $context);

    // UNASSIGN CAPABILITIES from editinteacher. probably not the way to go, but instead start with archetype = 0
    //unassign_capability('mod/quiz:addinstance', $roleid, $context->id);

    if (!$DB->record_exists('role', ['shortname' => 'fragenersteller2'])) {
        $roleid = create_role('fragenersteller2', 'fragenersteller2', 'This user can only create questions and see the dashboard');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fragenersteller2'])->id;
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
    //moodle capabilities:
    assign_capability('moodle/question:add', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:viewmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:movemine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:tagmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:commentmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:editmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:viewall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:moveall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:tagall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:commentall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:editall', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'fachlfragenreviewer2'])) {
        $roleid = create_role('fachl. Fragenreviewer2', 'fachlfragenreviewer2',
                '');
        $archetype = 0;
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fachlfragenreviewer2'])->id;
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
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:dofachlichreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);
    //moodle capabilities:
    //assign_capability('moodle/question:add', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:viewmine', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:movemine', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:tagmine', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:commentmine', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:editmine', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:viewall', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:moveall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:tagall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:commentall', CAP_ALLOW, $roleid, $context);
    //assign_capability('moodle/question:editall', CAP_ALLOW, $roleid, $context);
    assign_capability('moodle/question:useall', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'beurteilungsmitwirkende2'])) {
        $roleid = create_role('beurteilungsmitwirkende2', 'beurteilungsmitwirkende2', '');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'beurteilungsmitwirkende2'])->id;
    }
    assign_capability('block/exaquest:beurteilungsmitwirkende', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);

    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:checkexamsgrading', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:gradequestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:gradeexam', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'fachlicherpruefer2'])) {
        $roleid = create_role('fachlicher Prüfer2', 'fachlicherpruefer2', '');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fachlicherpruefer2'])->id;
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
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:createexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:dofachlichreviewexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:checkexamsgrading', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:gradequestion', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:assignaddquestions', $roleid,
            $context->id); // accidentally added, should be deleted. ONLY allow this for your own exams. Only pk and mover can do it generally
    // addquestion will be added in the output/exams.php for every exam the FP is FP of and for every PMW that has been assigned.
    assign_capability('block/exaquest:gradeexam', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'pruefungsmitwirkende2'])) {
        $roleid = create_role('Prüfungsmitwirkende2', 'pruefungsmitwirkende2', '');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'pruefungsmitwirkende2'])->id;
    }
    assign_capability('block/exaquest:pruefungsmitwirkende', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:assignaddquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:assignaddquestions', $roleid, $context->id); // accidentally added, should be deleted

    //assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    //unassign_capability('block/exaquest:createexam', $roleid, $context->id); // accidentally added, should be deleted

    // TODO: is this even a needed role? Or is it actually just "fachlicherpruefer" but assignes as a zweitpruefer to an exam? I guess so...
    if (!$DB->record_exists('role', ['shortname' => 'fachlicherzweitpruefer2'])) {
        $roleid = create_role('Fachlicherzweitpruefer2', 'fachlicherzweitpruefer2', '');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fachlicherzweitpruefer2'])->id;
    }
    assign_capability('block/exaquest:fachlicherzweitpruefer', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('block/exaquest:viewsimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewexamstab', CAP_ALLOW, $roleid, $context);
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);

    assign_capability('block/exaquest:viewnewexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewcreatedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewactiveexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewfinishedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewgradesreleasedexamscard', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:gradeexam', CAP_ALLOW, $roleid, $context);

    // ---
    if (!$DB->record_exists('role', ['shortname' => 'fragenerstellerlight2'])) {
        $roleid = create_role('Fragenerstellerlight2', 'fragenerstellerlight2', '');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fragenerstellerlight2'])->id;
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

    if (!$DB->record_exists('role', ['shortname' => 'fachlfragenreviewerlight2'])) {
        $roleid = create_role('Fachlicher Fragenreviewerlight2', 'fachlfragenreviewerlight2', '');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'fachlfragenreviewerlight2'])->id;
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
    //assign_capability('block/exaquest:viewcategorytab', CAP_ALLOW, $roleid, $context);
    unassign_capability('block/exaquest:viewcategorytab', $roleid, $context->id);
    assign_capability('block/exaquest:viewdashboardtab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionbanktab', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewdashboardoutsidecourse', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewownquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:exaquestuser', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:viewquestionstorevise', CAP_ALLOW, $roleid, $context);

    if (!$DB->record_exists('role', ['shortname' => 'sekretariat2'])) {
        $roleid = create_role('Sekretariat2', 'sekretariat2', '');
        $archetype = 0; // completely clean, no capabilities
        $definitiontable = new core_role_define_role_table_advanced($context, $roleid); //
        $definitiontable->force_duplicate($archetype,
                $options); // overwrites everything that is set in the options. The rest stays.
        $definitiontable->read_submitted_permissions(); // just to not throw a warning because some array is null
        $definitiontable->save_changes();
        $sourcerole = new \stdClass();
        $sourcerole->id = $archetype;
        role_cap_duplicate($sourcerole, $roleid);
        // allow setting role at context level "course category" and "course"
        set_role_contextlevels($roleid, array(CONTEXT_COURSECAT, CONTEXT_COURSE));
    } else {
        $roleid = $DB->get_record('role', ['shortname' => 'sekretariat2'])->id;
    }
    assign_capability('block/exaquest:sekretariat', CAP_ALLOW, $roleid, $context);
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
    assign_capability('block/exaquest:changeowner', CAP_ALLOW, $roleid, $context);

    //
    //role_assign($roleid, $USER->id, $contextid);

    //if ($roleid = $DB->get_field('role', 'id', array('shortname' => 'custom_role')){
    //$context = \context_system::instance(){;
    //assign_capability('block/custom_block:custom_capability', CAP_ALLOW,
    //    $roleid, $context);
    //}
    // now that every role exists:
    // set the allowassign, allowoverride, allowswitch and allowview for pk, mover and fp
    // get all roleids that are allowed to assign

    $allowedroles = array();
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'admintechnpruefungsdurchf2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'pruefungskoordination2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'pruefungsstudmis2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'modulverantwortlicher2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fragenersteller2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlfragenreviewer2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'beurteilungsmitwirkende2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlicherpruefer2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'pruefungsmitwirkende2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlicherzweitpruefer2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fragenerstellerlight2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlfragenreviewerlight2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'sekretariat2'])->id;

    // for every role, allow VIEWING of role, since this is not a problem and NOT seeing the role leads to confusion
    foreach ($allowedroles as $roleid) {
        foreach ($allowedroles as $allowedrole) {
            if (!$DB->get_record('role_allow_view', array('roleid' => $roleid, 'allowview' => $allowedrole))) {
                core_role_set_view_allowed($roleid, $allowedrole);
            }
        }
    }

    // for pk: allow every role
    $roleid = $DB->get_record('role', ['shortname' => 'pruefungskoordination2'])->id;

    foreach ($allowedroles as $allowedrole) {
        if (!$DB->get_record('role_allow_override', array('roleid' => $roleid, 'allowoverride' => $allowedrole))) {
            core_role_set_override_allowed($roleid, $allowedrole);
            core_role_set_assign_allowed($roleid, $allowedrole);
            core_role_set_switch_allowed($roleid, $allowedrole);
            //core_role_set_view_allowed($roleid, $allowedrole);
        }
    }

    $allowedroles = array();
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fragenersteller2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlfragenreviewer2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fragenerstellerlight2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'fachlfragenreviewerlight2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'sekretariat2'])->id;
    $roleid = $DB->get_record('role', ['shortname' => 'modulverantwortlicher2'])->id;
    foreach ($allowedroles as $allowedrole) {
        if (!$DB->get_record('role_allow_override', array('roleid' => $roleid, 'allowoverride' => $allowedrole))) {
            core_role_set_override_allowed($roleid, $allowedrole);
            core_role_set_assign_allowed($roleid, $allowedrole);
            core_role_set_switch_allowed($roleid, $allowedrole);
            //core_role_set_view_allowed($roleid, $allowedrole);
        }
    }

    $allowedroles = array();
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'beurteilungsmitwirkende2'])->id;
    $allowedroles[] = $DB->get_record('role', ['shortname' => 'pruefungsmitwirkende2'])->id;
    $roleid = $DB->get_record('role', ['shortname' => 'fachlicherpruefer'])->id;
    foreach ($allowedroles as $allowedrole) {
        if (!$DB->get_record('role_allow_override', array('roleid' => $roleid, 'allowoverride' => $allowedrole))) {
            core_role_set_override_allowed($roleid, $allowedrole);
            core_role_set_assign_allowed($roleid, $allowedrole);
            core_role_set_switch_allowed($roleid, $allowedrole);
            //core_role_set_view_allowed($roleid, $allowedrole);
        }
    }
    // REWORK END

}

//function block_exaquest_set_role_allow_assign($roleid, $allowedroles){
//    global $DB;
//
//    $record = new stdClass();
//    $record->roleid      = $fromroleid;
//    $record->allowassign = $targetroleid;
//    $DB->insert_record('role_allow_assign', $record);
//
//}
//
//function block_exaquest_set_role_allow_override($roleid, $allowedroles){
//
//}
//
//function block_exaquest_set_role_allow_switch($roleid, $allowedroles){
//
//}
//
//function block_exaquest_set_role_allow_view($roleid, $allowedroles){
//
//}

/**
 * Checks the active exams and changes status to finished, according to timing.
 */
function block_exaquest_check_active_exams() {
    $activeexams = block_exaquest_exams_by_status(null, BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE);
    $timeoverdueexams = array_filter($activeexams, function($exam, $key) {
        return $exam->timeclose != 0 && $exam->timeclose < time();
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

// this is used to get the contexts of the (question!!)category in the questionbank. The default question category of the coursecategory is used.

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
    // in the context table the instanceid is the id of the coursecategory. So contextid = coursecategorycontextid
    // this way, the questioncontextid for the coursecategory of the current course can be found.

    return [$categoryid, $contextid];

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
function block_exaquest_get_relevant_courses_for_user($userid = null) {
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
                        $exaquest_courses[$context->instanceid] = $course;
                    }
                } else {
                    $exaquest_courses[$context->instanceid] = $course;
                }
            }
        }
    }

    // order by "category" attribute of the objects in $exaquest_courses
    usort($exaquest_courses, function($a, $b) {
        return $a->category <=> $b->category;
        // spaceship operator (<=>). This operator returns -1 if the left-hand side ($a->category) is less than the right-hand side ($b->category),
        // 1 if it is greater, and 0 if they are equal.

    });

    return $exaquest_courses;
}

/**
 * @param $userid
 * Returns the summed up count of todos. E.g. "2 questions for me to create + 2 questions for me to submit = 4 todos"
 * questions_for_me_to_create_count + my_questions_to_submit_count + questions_for_me_to_review_count + questions_finalised_count +
 *     questions_for_me_to_revise_count + exams_for_me_to_create_count. Depending on your role some of those todos may not exist,
 *     but this will lead to a count of 0 for those todos, which means, it does not matter. No capabilities check needed.
 */
function block_exaquest_get_todo_count($userid, $coursecategoryid, $questioncategoryid, $context, $courseid) {
    // question todos
    $questions_for_me_to_create_count =
            block_exaquest_get_questions_for_me_to_create_count($coursecategoryid, $userid);
    $questions_for_me_to_review_count =
            block_exaquest_get_questions_for_me_to_review_count($questioncategoryid, $userid);
    $questions_for_me_to_revise_count =
            block_exaquest_get_questions_for_me_to_revise_count($questioncategoryid, $userid);

    // there is no "for me to release, which is why we take the finalised questionbankentries count. This is not available for everyone ==> check capability
    $questions_finalised_count = 0;
    if (has_capability('block/exaquest:viewquestionstorelease', $context, $userid)) {
        $questions_finalised_count = block_exaquest_get_finalised_questionbankentries_count($questioncategoryid);
    }

    $my_questions_to_submit_count =
            block_exaquest_get_my_questionbankentries_to_submit_count($questioncategoryid, $userid);

    // exams todos

    //$exams_for_me_to_create_count =
    //    block_exaquest_get_exams_for_me_to_create_count($coursecategoryid, $userid);
    $exams_for_me_to_fill_count = block_exaquest_get_assigned_quizzes_by_assigntype_count($courseid, $userid,
            BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS);

    $exams_for_me_to_check_grading_count = block_exaquest_get_assigned_quizzes_by_assigntype_count($courseid, $userid,
            BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHECK_EXAM_GRADING);
    $exams_for_me_to_grade_count = block_exaquest_get_assigned_quizzes_by_assigntype_count($courseid, $userid,
            BLOCK_EXAQUEST_QUIZASSIGNTYPE_GRADE_EXAM);
    $exams_for_me_to_change_grading_count = block_exaquest_get_assigned_quizzes_by_assigntype_count($courseid, $userid,
            BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHANGE_EXAM_GRADING);

    // there is no direct assignment to fachlich release, only to be the FACHLICHERPRUEFER and when the status is CREATED it means the FP has a todoo.
    $exams_to_fachlich_release = count(block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid,
            BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER, BLOCK_EXAQUEST_QUIZSTATUS_CREATED, $courseid));

    return $questions_for_me_to_create_count + $questions_for_me_to_review_count + $questions_for_me_to_revise_count +
            $questions_finalised_count + $exams_for_me_to_fill_count + $my_questions_to_submit_count +
            $exams_for_me_to_check_grading_count + $exams_for_me_to_grade_count + $exams_for_me_to_change_grading_count +
            $exams_to_fachlich_release;
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
function block_exaquest_exams_by_status($courseid = null, $status = BLOCK_EXAQUEST_QUIZSTATUS_NEW) {
    global $DB, $USER;

    if ($courseid) {
        $sql = "SELECT q.id as quizid, q.name as name,  cm.id as coursemoduleid, quizstatus.creatorid as creatorid
			FROM {" . BLOCK_EXAQUEST_DB_QUIZSTATUS . "} quizstatus
			JOIN {quiz} q on q.id = quizstatus.quizid 
			JOIN {course_modules} cm on cm.instance = q.id
			JOIN {modules} m on m.id = cm.module
			WHERE quizstatus.status = :status
			AND q.course = :courseid
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
                array("status" => $status, "courseid" => $courseid));
    } else {
        $sql = "SELECT q.id as quizid, q.name as name,  cm.id as coursemoduleid, q.timeclose as timeclose, quizstatus.creatorid as creatorid
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
 * Use this instead of the update_record function, because it also deletes quizassigns that no longer make sense.
 *
 * @param $quizid
 * @param $status
 */
function block_exaquest_exams_set_status($quizid, $status) {
    global $DB, $COURSE;
    $record = $DB->get_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, array("quizid" => $quizid));
    $record->status = $status;
    // if the status is changed to BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED ==> remove all quizassigns for this quiz, as they no longer make any sense
    if ($status == BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED) {
        $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN,
                array("quizid" => $quizid, "assigntype" => BLOCK_EXAQUEST_QUIZASSIGNTYPE_GRADE_EXAM));
        $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN,
                array("quizid" => $quizid, "assigntype" => BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHECK_EXAM_GRADING));
    } else if ($status == BLOCK_EXAQUEST_QUIZSTATUS_FINISHED) {
        // if the status changes to finished, todoos for PK should be sent out
        // BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_OPEN
        // BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_CLOSED
        // depending on if every question is graded or not
        $ungraded_questions_count = block_exaquest_get_ungraded_questions_count($quizid);
        // $userfrom is the system
        $userfrom = \core_user::get_support_user();
        // $userto is the pk
        $courseid = $DB->get_record('quiz', array('id' => $quizid))->course;
        $pks =
                block_exaquest_get_pk_by_courseid($courseid); // courseid is the course of the quiz. not $COURSE as this would not work in cronjob
        if ($ungraded_questions_count > 0) {
            // create quizassign for pk with type BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_OPEN
            foreach ($pks as $userto) {
                block_exaquest_assign_quiz_done_to_pk($userfrom->id, $userto->id, '', $quizid, null,
                        BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_OPEN);
            }
        } else {
            foreach ($pks as $userto) {
                block_exaquest_assign_quiz_done_to_pk($userfrom->id, $userto->id, '', $quizid, null,
                        BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_DONE);
            }
        }

    }
    //else if($status == BLOCK_EXAQUEST_QUIZSTATUS_CREATED){
    //    // create assignment for the fp of the quiz BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICH_RELEASE
    //
    //}
    $DB->update_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, $record);
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
    $capabilities["modulverantwortlicher"] = has_capability("block/exaquest:modulverantwortlicher", $context, $USER);
    $capabilities["fragenersteller"] = has_capability("block/exaquest:fragenersteller", $context, $USER);
    $capabilities["fachlfragenreviewer"] = has_capability("block/exaquest:fachlfragenreviewer", $context, $USER);
    $capabilities["pruefungskoordination"] = has_capability("block/exaquest:pruefungskoordination", $context, $USER);
    $capabilities["admintechnpruefungsdurchf"] = has_capability("block/exaquest:admintechnpruefungsdurchf", $context, $USER);
    $capabilities["pruefungsstudmis"] = has_capability("block/exaquest:pruefungsstudmis", $context, $USER);
    $capabilities["beurteilungsmitwirkende"] = has_capability("block/exaquest:beurteilungsmitwirkende", $context, $USER);
    $capabilities["fachlicherpruefer"] = has_capability("block/exaquest:fachlicherpruefer", $context, $USER);
    $capabilities["pruefungsmitwirkende"] = has_capability("block/exaquest:pruefungsmitwirkende", $context, $USER);
    $capabilities["fachlicherzweitpruefer"] = has_capability("block/exaquest:fachlicherzweitpruefer", $context, $USER);

    // capabilities defined by ZML
    $capabilities["viewquestionstorevise"] = has_capability("block/exaquest:viewquestionstorevise", $context, $USER);
    $capabilities["createquestion"] = has_capability("block/exaquest:createquestion", $context, $USER);
    $capabilities["releasequestion"] = has_capability("block/exaquest:releasequestion", $context, $USER);
    $capabilities["readallquestions"] = has_capability("block/exaquest:readallquestions", $context, $USER);
    $capabilities["readquestionstatistics"] = has_capability("block/exaquest:readquestionstatistics", $context, $USER);
    $capabilities["changestatusofreleasedquestions"] =
            has_capability("block/exaquest:changestatusofreleasedquestions", $context, $USER);
    $capabilities["setstatustoreview"] = has_capability("block/exaquest:setstatustoreview", $context, $USER);
    $capabilities["reviseownquestion"] = has_capability("block/exaquest:reviseownquestion", $context, $USER);
    $capabilities["setstatustofinalised"] = has_capability("block/exaquest:setstatustofinalised", $context, $USER);
    $capabilities["viewownrevisedquestions"] = has_capability("block/exaquest:viewownrevisedquestions", $context, $USER);
    $capabilities["viewquestionstoreview"] = has_capability("block/exaquest:viewquestionstoreview", $context, $USER);
    $capabilities["editquestiontoreview"] = has_capability("block/exaquest:editquestiontoreview", $context, $USER);
    $capabilities["viewfinalisedquestions"] = has_capability("block/exaquest:viewfinalisedquestions", $context, $USER);
    $capabilities["editallquestions"] = has_capability("block/exaquest:editallquestions", $context, $USER);
    $capabilities["addquestiontoexam"] = has_capability("block/exaquest:addquestiontoexam", $context, $USER);
    $capabilities["releaseexam"] = has_capability("block/exaquest:releaseexam", $context, $USER);
    $capabilities["doformalreview"] = has_capability("block/exaquest:doformalreview", $context, $USER);
    $capabilities["executeexam"] = has_capability("block/exaquest:executeexam", $context, $USER);
    $capabilities["assignsecondexaminator"] = has_capability("block/exaquest:assignsecondexaminator", $context, $USER);
    $capabilities["definequestionblockingtime"] = has_capability("block/exaquest:definequestionblockingtime", $context, $USER);
    $capabilities["viewexamresults"] = has_capability("block/exaquest:viewexamresults", $context, $USER);
    $capabilities["gradeexam"] = has_capability("block/exaquest:gradeexam", $context, $USER);
    $capabilities["createexamstatistics"] = has_capability("block/exaquest:createexamstatistics", $context, $USER);
    $capabilities["viewexamstatistics"] = has_capability("block/exaquest:viewexamstatistics", $context, $USER);
    $capabilities["correctexam"] = has_capability("block/exaquest:correctexam", $context, $USER);
    $capabilities["acknowledgeexamcorrection"] = has_capability("block/exaquest:acknowledgeexamcorrection", $context, $USER);
    $capabilities["releaseexamgrade"] = has_capability("block/exaquest:releaseexamgrade", $context, $USER);
    $capabilities["releasecommissionalexamgrade"] = has_capability("block/exaquest:releasecommissionalexamgrade", $context, $USER);
    $capabilities["exportgradestokusss"] = has_capability("block/exaquest:exportgradestokusss", $context, $USER);
    $capabilities["executeexamreview"] = has_capability("block/exaquest:executeexamreview", $context, $USER);
    $capabilities["addparticipanttomodule"] = has_capability("block/exaquest:addparticipanttomodule", $context, $USER);
    $capabilities["assignroles"] = has_capability("block/exaquest:assignroles", $context, $USER);
    $capabilities["changerolecapabilities"] = has_capability("block/exaquest:changerolecapabilities", $context, $USER);
    $capabilities["createroles"] = has_capability("block/exaquest:createroles", $context, $USER);

    // created during development
    //$capabilities["viewstatistic"] = has_capability("block/exaquest:viewstatistic", $context, $USER);
    $capabilities["viewquestionstorelease"] = has_capability("block/exaquest:viewquestionstorelease", $context, $USER);
    $capabilities["viewquestionstorevise"] = has_capability("block/exaquest:viewquestionstorevise", $context, $USER);
    $capabilities["createexam"] = has_capability("block/exaquest:viewquestionstorevise", $context, $USER);
    $capabilities["setquestioncount"] = has_capability("block/exaquest:setquestioncount", $context, $USER);
    $capabilities["checkexamsgrading"] = has_capability("block/exaquest:checkexamsgrading", $context, $USER);
    $capabilities["gradequestion"] = has_capability("block/exaquest:gradequestion", $context, $USER);
    $capabilities["changeexamsgrading"] = has_capability("block/exaquest:changeexamsgrading", $context, $USER);

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
    return get_enrolled_users($context, 'block/exaquest:pruefungskoordination', 0, 'u.*', null, 0, 0, true);
}

function block_exaquest_create_daily_notifications() {
    global $USER;

    $users = block_exaquest_get_all_exaquest_users();

    foreach ($users as $user) {
        // get the todocount and create the todos notification
        $courses = block_exaquest_get_relevant_courses_for_user($user->id);
        // "relevant" is relative... it creates todos for this course, and a course that was active in the last 6 months. Could lead to often produce two similar sentences,
        // but it is summed up into ONE notification anyway
        $todosmessage = '';
        foreach ($courses as $c) {
            $context = \context_course::instance($c->id);
            $questioncategoryid = get_question_category_and_context_of_course($c->id)[0];
            $todocount = block_exaquest_get_todo_count($user->id, $c->category, $questioncategoryid, $context, $c->id);
            if ($todocount) {
                // create the message
                $messageobject = new stdClass();
                $messageobject->todoscount = $todocount;
                $messageobject->fullname = $c->fullname;
                $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $c->id]);
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

            // only create a message, when the todos count differs from the message of the previous day. careful: if it is the first notification --> null problems
            // check if there exist a notifications with the same eventtype (dailytodos) and the same userid get the most current one and compare the content
            // if the content is the same, don't send it again. if there is not notifaction or the content is not the same --> send it
            // content = message
            // get the notifcations with eventtype="dailtytodos" and userid=$user->id
            $notifications = block_exaquest_get_notifications_by_eventtype_and_userid("dailytodos", $user->id);
            if ($notifications) {
                $notification = reset($notifications); // get the first element of the array
                if ($notification->fullmessage == $message) {
                    // do not send the notification again
                    continue;
                }
            }
            block_exaquest_send_moodle_notification("dailytodos", $USER->id, $user->id, $subject, $message,
                    "TODOs", $url_to_moodle_dashboard);
        }
    }

    // get the PK of all courses and send notification about released questions
    $pks = block_exaquest_get_all_pruefungskoordination_users();
    foreach ($pks as $pk) {
        $courses = block_exaquest_get_relevant_courses_for_user($pk->id);
        $daily_released_questions_message = '';
        foreach ($courses as $c) {
            if (has_capability('block/exaquest:pruefungskoordination', \context_course::instance($c->id),
                    $pk->id)) { // could have another role in this course ==> skip
                $questioncategoryid = get_question_category_and_context_of_course($c->id)[0];
                $daily_released_questions = get_daily_released_questions($questioncategoryid);
                if ($daily_released_questions) {
                    // create the message
                    $messageobject = new stdClass();
                    $messageobject->daily_released_questions = $daily_released_questions;
                    $messageobject->fullname = $c->fullname;
                    $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $c->id]);
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
}

/**
 * @param $eventtype
 * @param $useridto
 * @return notifications with the given eventtype and userid ordered by timecreated (element with highest timecreated first /
 *     newest notification first)
 */
function block_exaquest_get_notifications_by_eventtype_and_userid($eventtype, $useridto) {
    global $DB;
    $notifications = $DB->get_records('notifications',
            array('eventtype' => $eventtype, 'useridto' => $useridto), 'timecreated DESC');
    return $notifications;
}

/**
 * @param $courseid
 * returns the count of how many questions have been released in which course
 */
function get_daily_released_questions($questioncategoryid) {
    global $DB;

    $time_last_day = time() - 86400; // current time - 24*60*60 to have time of 24h hours ago.
    // anything that has a timestamp larger than 24h ago has been done yesterday

    $sql = 'SELECT qs.id
			FROM {' . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . '} qs
			JOIN {question_bank_entries} qbe ON qbe.id = qs.questionbankentryid
			WHERE qbe.questioncategoryid = :questioncategoryid
			AND qs.timestamp > :timelastday
			AND qs.status = ' . BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED;

    $questions = $DB->get_records_sql($sql, array('questioncategoryid' => $questioncategoryid, 'timelastday' => $time_last_day));
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
            BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE . ' OR status = ' .
            BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE .
            ')';

    $DB->execute($sql);
}

function block_exaquest_assign_quiz_addquestions($userfrom, $userto, $comment, $quizid, $quizname = null,
        $assigntype = null) {
    global $DB, $COURSE;

    block_exaquest_quizassign($userfrom, $userto, $comment, $quizid, $assigntype);

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

//function block_exaquest_assign_quiz_fachlich_release_exam($userfrom, $userto, $comment, $quizid, $quizname = null,
//        $assigntype = null) {
//    global $DB, $COURSE;
//
//    block_exaquest_quizassign($userfrom, $userto, $comment, $quizid, $assigntype);
//
//    // create the message
//    $messageobject = new stdClass;
//    $messageobject->fullname = $quizname;
//    $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $COURSE->id]);
//    $messageobject->url = $messageobject->url->raw_out(false);
//    $messageobject->requestcomment = $comment;
//    $message = get_string('please_fill_exam', 'block_exaquest', $messageobject);
//    $subject = get_string('please_fill_exam_subject', 'block_exaquest', $messageobject);
//    block_exaquest_send_moodle_notification("fillexam", $userfrom->id, $userto, $subject, $message,
//            "fillexam", $messageobject->url);
//}

function block_exaquest_assign_check_exam_grading($userfrom, $userto, $comment, $quizid, $quizname = null,
        $assigntype = null) {
    global $COURSE;

    block_exaquest_quizassign($userfrom, $userto, $comment, $quizid, $assigntype);

    // create the message
    $messageobject = new stdClass;
    $messageobject->fullname = $quizname;
    $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $COURSE->id]);
    $messageobject->url = $messageobject->url->raw_out(false);
    $messageobject->requestcomment = $comment;
    $message = get_string('please_check_exam_grading', 'block_exaquest', $messageobject);
    $subject = get_string('please_check_exam_grading_subject', 'block_exaquest', $messageobject);
    block_exaquest_send_moodle_notification("checkexamgrading", $userfrom->id, $userto, $subject, $message,
            "checkexamgrading", $messageobject->url);
}

function block_exaquest_assign_kommissionell_check_exam_grading($userfrom, $userto, $comment, $quizid, $quizname = null,
        $assigntype = null, $selectedstudents = null) {
    global $COURSE, $DB;

    //add the $selectedstudents to the comment
    $comment .= "\n\n" . get_string('selected_student', 'block_exaquest') . "\n";
    foreach ($selectedstudents as $selectedstudent) {
        $single_comment = $comment; // a comment per assigned student
        $student = $DB->get_record('user', array('id' => $selectedstudent));
        $single_comment .= $student->firstname . " " . $student->lastname . "\n";
        block_exaquest_quizassign($userfrom, $userto, $single_comment, $quizid, $assigntype, $selectedstudent);

        // create the message
        $messageobject = new stdClass;
        $messageobject->fullname = $quizname;
        $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $COURSE->id]);
        $messageobject->url = $messageobject->url->raw_out(false);
        $messageobject->requestcomment = $single_comment;
        $message = get_string('please_kommissionell_check_exam_grading', 'block_exaquest', $messageobject);
        $subject = get_string('please_kommissionell_check_exam_grading_subject', 'block_exaquest', $messageobject);
        block_exaquest_send_moodle_notification("kommissionellcheckexamgrading", $userfrom->id, $userto, $subject, $message,
                "kommissionellcheckexamgrading", $messageobject->url);
    }
}

function block_exaquest_assign_change_exam_grading($userfrom, $userto, $comment, $quizid, $quizname = null,
        $assigntype = null) {
    global $COURSE;

    block_exaquest_quizassign($userfrom, $userto, $comment, $quizid, $assigntype);

    // create the message
    $messageobject = new stdClass;
    $messageobject->fullname = $quizname;
    $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $COURSE->id]);
    $messageobject->url = $messageobject->url->raw_out(false);
    $messageobject->requestcomment = $comment;
    $messageobject->requester = $userfrom->firstname . ' ' . $userfrom->lastname;
    $message = get_string('please_change_exam_grading', 'block_exaquest', $messageobject);
    $subject = get_string('please_change_exam_grading_subject', 'block_exaquest', $messageobject);
    block_exaquest_send_moodle_notification("changeexamgrading", $userfrom->id, $userto, $subject, $message,
            "changeexamgrading", $messageobject->url);
}

function block_exaquest_assign_gradeexam($userfrom, $userto, $comment, $quizid, $quizname = null,
        $assigntype = null, $selectedquestions = null) {
    global $COURSE, $DB;

    // add the $selectedquestions to the comment
    $comment .= "\n\n" . get_string('selected_questions', 'block_exaquest') . "\n";
    foreach ($selectedquestions as $selectedquestion) {
        $comment .= $DB->get_record('question', array('id' => $selectedquestion))->name . "\n";
    }

    block_exaquest_quizassign($userfrom, $userto, $comment, $quizid, $assigntype);

    // create the message
    $messageobject = new stdClass;
    $messageobject->fullname = $quizname;
    $messageobject->url = new moodle_url('/blocks/exaquest/dashboard.php', ['courseid' => $COURSE->id]);
    $messageobject->url = $messageobject->url->raw_out(false);
    $messageobject->requestcomment = $comment;
    $message = get_string('please_grade_exam', 'block_exaquest', $messageobject);
    $subject = get_string('please_grade_exam_subject', 'block_exaquest', $messageobject);
    block_exaquest_send_moodle_notification("gradeexam", $userfrom->id, $userto, $subject, $message,
            "gradeexam", $messageobject->url);
}

/**
 * @param $userfrom
 * @param $userto
 * @param $comment
 * @param $quizid
 * @param $assigntype
 * @return bool true if a new assignment is made, false if not (if it is just reset to done=false)
 * @throws dml_exception
 */
function block_exaquest_quizassign($userfrom, $userto, $comment, $quizid, $assigntype = null, $customdata = null) {
    global $DB;

    // check $userto and $userfrom if they are objects or the ids
    if (is_object($userto)) {
        $usertoid = $userto->id;
    } else {
        $usertoid = $userto;
    }
    if (is_object($userfrom)) {
        $userfromid = $userfrom->id;
    } else {
        $userfromid = $userfrom;
    }

    // enter data into the exaquest tables
    $assigndata = new stdClass;
    $assigndata->quizid = $quizid;
    $assigndata->assigneeid = $usertoid;
    $assigndata->assignerid = $userfromid;
    $assigndata->assigntype = $assigntype;
    $assigndata->customdata = $customdata;
    //if that assignment does not exist yet, create it
    $quizassignid = $DB->get_record(BLOCK_EXAQUEST_DB_QUIZASSIGN,
            array('quizid' => $quizid, 'assigneeid' => $usertoid, 'assigntype' => $assigntype, 'customdata' => $customdata))->id;
    if (!$quizassignid) {
        $quizassignid = $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZASSIGN, $assigndata);
        $newlyassigned = true;
    } else {
        // already exists, reset the "done" field
        $DB->set_field(BLOCK_EXAQUEST_DB_QUIZASSIGN, 'done', 0,
                array('id' => $quizassignid));
        $newlyassigned = false;
    }

    // insert comment into BLOCK_EXAQUEST_DB_QUIZCOMMENT
    if ($comment != '') {
        $commentdata = new stdClass;
        $commentdata->quizid = $quizid;
        $commentdata->commentorid = $userfromid;
        $commentdata->quizassignid = $quizassignid;
        $commentdata->comment = $comment;
        $commentdata->timestamp = time();
        $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZCOMMENT, $commentdata);
    }
    return $newlyassigned;
}

function block_exaquest_assign_quiz_done_to_pk($userfrom, $userto, $comment, $quizid, $quizname = null,
        $assigntype = null) {
    global $COURSE, $DB, $CFG;

    // check $userto and $userfrom if they are objects or the ids
    if (is_object($userto)) {
        $usertoid = $userto->id;
    } else {
        $usertoid = $userto;
    }
    if (is_object($userfrom)) {
        $userfromid = $userfrom->id;
    } else {
        $userfromid = $userfrom;
    }
    // Retrieve the user record
    if (!(is_object($userto)) || !empty($userto->lang)) {
        $userto = $DB->get_record('user', array('id' => $usertoid));
    }

    // Check if the user record was found and the lang field is set
    if ($userto) {
        $preferred_language = $userto->lang;
    } else {
        // Fallback to the site's default language if user record is not found or lang is not set
        $preferred_language = $CFG->lang;
    }

    // block_exaquest_quizassign returns true if a new assignment is made, returns false if not. Only create a notification if a new assignment is done to avoid spam.
    if (block_exaquest_quizassign($userfromid, $usertoid, $comment, $quizid, $assigntype)) {
        // create the message
        $messageobject = new stdClass;
        $messageobject->fullname = $quizname;
        $messageobject->url = new moodle_url('/blocks/exaquest/exams.php', ['courseid' => $COURSE->id]);
        $messageobject->url = $messageobject->url->raw_out(false);
        if ($assigntype == BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_OPEN) {
            $messageobject->requestcomment =
                    new lang_string('quiz_finished_grading_open_comment', 'block_exaquest', null, $preferred_language);
            $messageobject->requestcomment = $messageobject->requestcomment->__toString();
            $message = new lang_string('quiz_finished_grading_open', 'block_exaquest', $messageobject, $preferred_language);
            $message = $message->__toString();
            $subject = new lang_string('quiz_finished_grading_open_subject', 'block_exaquest', $messageobject, $preferred_language);
            $subject = $subject->__toString();
            block_exaquest_send_moodle_notification("quizfinishedgradingopen", $userfromid, $usertoid, $subject, $message,
                    "quizfinishedgradingopen", $messageobject->url);
        } else if ($assigntype == BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_DONE) {
            $messageobject->requestcomment =
                    new lang_string('quiz_finished_grading_done_comment', 'block_exaquest', null, $preferred_language);
            $messageobject->requestcomment = $messageobject->requestcomment->__toString();
            $message = new lang_string('quiz_finished_grading_done', 'block_exaquest', $messageobject, $preferred_language);
            $message = $message->__toString();
            $subject = new lang_string('quiz_finished_grading_done_subject', 'block_exaquest', $messageobject, $preferred_language);
            $subject = $subject->__toString();
            block_exaquest_send_moodle_notification("quizfinishedgradingdone", $userfromid, $usertoid, $subject, $message,
                    "quizfinishedgradingdone", $messageobject->url);
        }
    }
}

/** deprecated, use block_exaquest_quizassign() instead */
//function block_exaquest_assign_quiz_fp($userto, $quizid) {
//    global $DB, $COURSE;
//
//    // delete existing entries in BLOCK_EXAQUEST_DB_QUIZASSIGN for that quizid and assigntype, as it should be overridden (there can only be one)
//    $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN,
//            array('quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER));
//
//    // enter data into the exaquest tables
//    $assigndata = new stdClass;
//    $assigndata->quizid = $quizid;
//    $assigndata->assigneeid = $userto;
//    $assigndata->assigntype = BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER;
//    $quizassignid = $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZASSIGN, $assigndata);
//}

/** deprecated, use block_exaquest_quizassign() instead */
//function block_exaquest_assign_quiz_fzp($userto, $quizid) {
//    global $DB, $COURSE;
//
//    // delete existing entries in BLOCK_EXAQUEST_DB_QUIZASSIGN for that quizid and assigntype, as it should be overridden (there can only be one)
//    $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN,
//            array('quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERZWEITPRUEFER));
//
//    // enter data into the exaquest tables
//    $assigndata = new stdClass;
//    $assigndata->quizid = $quizid;
//    $assigndata->assigneeid = $userto;
//    $assigndata->assigntype = BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERZWEITPRUEFER;
//    $quizassignid = $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZASSIGN, $assigndata);
//}

/** deprecated, use block_exaquest_quizassign() instead */
//function block_exaquest_assign_quiz_fdp($userto, $quizid) {
//    global $DB, $COURSE;
//
//    // delete existing entries in BLOCK_EXAQUEST_DB_QUIZASSIGN for that quizid and assigntype, as it should be overridden (there can only be one)
//    $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN,
//            array('quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERDRITTPRUEFER));
//
//    // enter data into the exaquest tables
//    $assigndata = new stdClass;
//    $assigndata->quizid = $quizid;
//    $assigndata->assigneeid = $userto;
//    $assigndata->assigntype = BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERDRITTPRUEFER;
//    $quizassignid = $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZASSIGN, $assigndata);
//}

function block_exaquest_get_fragefaecher_by_courseid_and_quizid($courseid, $quizid, $show_deleted = false) {
    global $DB;

    // get course
    $course = $DB->get_record('course', array('id' => $courseid));

    if ($show_deleted) {
        $sql = 'SELECT cat.*, qc.questioncount, qc.quizid
            FROM {' . BLOCK_EXAQUEST_DB_CATEGORIES . '} cat
            LEFT JOIN {' . BLOCK_EXAQUEST_DB_QUIZQCOUNT . '} qc ON qc.exaquestcategoryid = cat.id AND qc.quizid = :quizid
            WHERE cat.coursecategoryid = :coursecategoryid
            AND cat.categorytype = ' . BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH;
    } else {
        $sql = 'SELECT cat.*, qc.questioncount, qc.quizid
            FROM {' . BLOCK_EXAQUEST_DB_CATEGORIES . '} cat
            LEFT JOIN {' . BLOCK_EXAQUEST_DB_QUIZQCOUNT . '} qc ON qc.exaquestcategoryid = cat.id AND qc.quizid = :quizid
            WHERE cat.coursecategoryid = :coursecategoryid
            AND cat.deleted = 0
            AND cat.categorytype = ' . BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH;
    }

    $fragefaecher = $DB->get_records_sql($sql, array('coursecategoryid' => $course->category, 'quizid' => $quizid));

    return $fragefaecher;
}

//function block_exaquest_get_current_questioncount_for_category_and_quizid($quizid, $categoryid){
//
//}

function block_exaquest_get_category_names_by_ids($categoryoptionidkeys, $onlyfragefaecher = false) {
    global $DB;
    $query = "('" . implode("','", $categoryoptionidkeys) . "')";
    if ($onlyfragefaecher) {
        $categoryoptions = $DB->get_records_sql("SELECT eqc.id, eqc.categoryname, eqc.categorytype
                                    FROM {" . BLOCK_EXAQUEST_DB_CATEGORIES . "} eqc
                                   WHERE eqc.categorytype = " . BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH . " AND eqc.id IN " . $query);
    } else {
        // after creating query it retrieves all categories which are contained in any of the questions
        $categoryoptions = $DB->get_records_sql("SELECT eqc.id, eqc.categoryname, eqc.categorytype
                                    FROM {" . BLOCK_EXAQUEST_DB_CATEGORIES . "} eqc
                                   WHERE eqc.id IN " . $query);
    }
    return $categoryoptions;
}

function block_exaquest_get_missing_questions_count($quizid, $courseid) {
    $categorys_required_counts = block_exaquest_get_fragefaecher_by_courseid_and_quizid($courseid, $quizid);
    $categorys_current_counts = block_exaquest_get_category_question_count($quizid);
    $categoryoptionidkeys = array_keys($categorys_required_counts);
    $fragefaecher = block_exaquest_get_category_names_by_ids($categoryoptionidkeys, true);
    $missingquestionscount = 0;
    foreach ($fragefaecher as $key => $option) {
        $fragefaecher[$key]->requiredquestioncount = $categorys_required_counts[$key]->questioncount;
        $fragefaecher[$key]->currentquestioncount = $categorys_current_counts[$key] ?: 0;
        if ($fragefaecher[$key]->currentquestioncount < $fragefaecher[$key]->requiredquestioncount) {
            $missingquestionscount += $fragefaecher[$key]->requiredquestioncount -
                    $fragefaecher[$key]->currentquestioncount;
        }
    }
    return $missingquestionscount;
}

// returns an array with key: categoryid and value: count of how many questions in this quiz have this category
// the categoryid is the id of the exaquestcategories table. THe value is the count of how many questions in this quiz have this category
function block_exaquest_get_category_question_count($quizid) {
    global $DB;
    // sql retrieves all categories for each questions inside this view
    $customfieldvalues = $DB->get_records_sql("SELECT *
                              FROM {quiz_slots} qusl
                              JOIN {question_references} qref ON qusl.id = qref.itemid
                              JOIN {question_versions} qv ON qv.questionbankentryid = qref.questionbankentryid
                              JOIN {customfield_data} cfd ON cfd.instanceid = qv.questionid
                              WHERE qv.version = (SELECT Max(v.version)
                                                    FROM   {question_versions} v
                                                    JOIN {question_bank_entries} be
                                                    ON be.id = v.questionbankentryid
                                                    WHERE  be.id = qref.questionbankentryid) AND qusl.quizid=:quizid",
            array('quizid' => $quizid));

    $categoryoptionidarray = array();
    foreach ($customfieldvalues as $categoryoptionid) {
        $mrg = explode(',', $categoryoptionid->value);
        $categoryoptionidarray = array_merge($categoryoptionidarray, $mrg);
    }
    // counts how often each category was used in each question
    $categoryoptionidcount = array();
    foreach ($categoryoptionidarray as $categoryoptionid) {
        if (array_key_exists($categoryoptionid, $categoryoptionidcount)) {
            $categoryoptionidcount[$categoryoptionid] += 1;
        } else {
            $categoryoptionidcount[$categoryoptionid] = 1;
        }
    }
    return $categoryoptionidcount;
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

function block_exaquest_get_assigned_fachlicherzweitpruefer($quizid) {
    global $DB;
    $sql = 'SELECT qa.assigneeid
			FROM {' . BLOCK_EXAQUEST_DB_QUIZASSIGN . '} qa
			WHERE qa.quizid = :quizid
			AND qa.assigntype = ' . BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERZWEITPRUEFER;
    $fachlicherpreufer = $DB->get_record_sql($sql,
            array('quizid' => $quizid));
    return $fachlicherpreufer;
}

function block_exaquest_get_assigned_fachlicherdrittpruefer($quizid) {
    global $DB;
    $sql = 'SELECT qa.assigneeid
			FROM {' . BLOCK_EXAQUEST_DB_QUIZASSIGN . '} qa
			WHERE qa.quizid = :quizid
			AND qa.assigntype = ' . BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERDRITTPRUEFER;
    $fachlicherpreufer = $DB->get_record_sql($sql,
            array('quizid' => $quizid));
    return $fachlicherpreufer;
}

function block_exaquest_get_assigned_persons_by_quizid_and_assigntype($quizid,
        $assigntype = BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS) {
    global $DB;
    $sql = 'SELECT qa.assigneeid, u.*, qa.done
			FROM {' . BLOCK_EXAQUEST_DB_QUIZASSIGN . '} qa
			JOIN {user} u ON u.id = qa.assigneeid
			WHERE qa.quizid = :quizid
			AND qa.assigntype = :assigntype';

    $assignedpersons = $DB->get_records_sql($sql,
            array('quizid' => $quizid, 'assigntype' => $assigntype));
    return $assignedpersons;
}

function block_exaquest_get_fragefaecher_by_courseid($courseid, $show_deleted = false) {
    global $DB;

    // get course
    $course = $DB->get_record('course', array('id' => $courseid));

    if ($show_deleted) {
        $sql = 'SELECT cat.*
            FROM {' . BLOCK_EXAQUEST_DB_CATEGORIES . '} cat
            WHERE cat.coursecategoryid = :coursecategoryid
            AND cat.categorytype = ' . BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH;
    } else {
        $sql = 'SELECT cat.*
            FROM {' . BLOCK_EXAQUEST_DB_CATEGORIES . '} cat
            WHERE cat.coursecategoryid = :coursecategoryid
            AND cat.deleted = 0
            AND cat.categorytype = ' . BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH;
    }

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
    }
    // or it has been created manually --> everything can stay as it is
}

function block_exaquest_check_if_exam_is_ready($quizid) {
    global $DB;
    // check if every assignment of this kind is done for this quiz
    if ($DB->get_records(BLOCK_EXAQUEST_DB_QUIZASSIGN,
            array('quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS, 'done' => 0))) {
        // records still exist and "done" = 0 ==> not every todoo is done
        return false;
    } else {
        // no records exist ==> every assignment is done
        // set the quizstatus from new to BLOCK_EXAQUEST_QUIZSTATUS_CREATED
        //$quizstatus = $DB->get_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, array('quizid' => $quizid));
        //$quizstatus->status = BLOCK_EXAQUEST_QUIZSTATUS_CREATED;
        //$DB->update_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, $quizstatus);
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
        return true;
    }

    // TODO: also check when all questions are added ?

}

/** checks if every assignment of CHECK_EXAM_GRADING has been done as well as every assignment of KOMMISSIONELL_CHECK_EXAM_GRADING */
function block_exaquest_check_if_grades_should_be_released($quizid) {
    global $DB;
    // check if every assignment of this kind is done for this quiz
    if ($DB->get_records(BLOCK_EXAQUEST_DB_QUIZASSIGN,
            array('quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHECK_EXAM_GRADING, 'done' => 0))) {
        // records still exist and "done" = 0 ==> not every todoo is done
        return false;
    } else if ($DB->get_records(BLOCK_EXAQUEST_DB_QUIZASSIGN,
            array('quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_KOMMISSIONELL_CHECK_EXAM_GRADING,
                    'done' => 0))) {
        return false;
    } else {

        // no records exist ==> every assignment is done
        // set the quizstatus from BLOCK_EXAQUEST_QUIZSTATUS_FINISHED to BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED and release the grades
        // TODO: release the grades, this has to trigger sometghing from moodle?
        // ACTUALLY release the grades...
        //$quizstatus = $DB->get_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, array('quizid' => $quizid));
        //$quizstatus->status = BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED;
        //$DB->update_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, $quizstatus);
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED);

        return true;
    }
}

// TODO: check if all questions have been graded, or if everyone has marked as done? For now: if every question has been graded
function block_exaquest_check_if_all_gradings_have_been_done($quizid) {
    global $DB;
    $ungraded_questions_count = block_exaquest_get_ungraded_questions_count($quizid);
    if ($ungraded_questions_count > 0) {
        // not done
        return false;
    } else {
        // done
        // delete the grading todoos and inform the PK
        // this keeps the status at FINISHED but still informs the PK
        // remove all entries in BLOCK_EXAQUEST_DB_QUIZASSIGN for this quizid and the assigntype BLOCK_EXAQUEST_QUIZASSIGNTYPE_GRADE_EXAM

        // $userfrom is the system
        $userfrom = \core_user::get_support_user();
        // $userto is the pk
        $courseid = $DB->get_record('quiz', array('id' => $quizid))->course;
        $pks =
                block_exaquest_get_pk_by_courseid($courseid); // courseid is the course of the quiz. not $COURSE as this would not work in cronjob
        foreach ($pks as $userto) {
            block_exaquest_assign_quiz_done_to_pk($userfrom->id, $userto->id,
                    '',
                    $quizid, null, BLOCK_EXAQUEST_QUIZASSIGNTYPE_EXAM_FINISHED_GRADING_DONE);
        }
        $DB->delete_records(BLOCK_EXAQUEST_DB_QUIZASSIGN,
                array('quizid' => $quizid, 'assigntype' => BLOCK_EXAQUEST_QUIZASSIGNTYPE_GRADE_EXAM));
        return true;
    }
}

function block_exaquest_check_if_question_contains_categories($questionid) {
    global $DB;

    $categoryoptionids = $DB->get_records_sql("SELECT cfd.value
                                    FROM {question} q
                                    JOIN {customfield_data} cfd ON q.id = cfd.instanceid
                                   WHERE q.id = ?", array($questionid));

    $categoryoptionidarray = array();
    foreach ($categoryoptionids as $categoryoptionid) {
        $mrg = explode(',', $categoryoptionid->value);
        $categoryoptionidarray = array_merge($categoryoptionidarray, $mrg);
    }
    $query = "('" . implode("','", $categoryoptionidarray) . "')";

    $categoryoptions = $DB->get_records_sql("SELECT eqc.id, eqc.categoryname, eqc.categorytype
                                    FROM {" . BLOCK_EXAQUEST_DB_CATEGORIES . "} eqc
                                   WHERE eqc.deleted = 0
                                       AND eqc.id IN " . $query);

    $categorytypes = array();
    foreach ($categoryoptions as $categoryoption) {
        $categorytypes[] = intval($categoryoption->categorytype);
    }
    return in_array(0, $categorytypes) && in_array(1, $categorytypes) && in_array(2, $categorytypes) && in_array(3, $categorytypes);
}

// RENDER... maybe put in separate file
function block_exaquest_render_questioncount_per_category() {
    global $DB, $COURSE, $OUTPUT, $SESSION;
    $quizid = optional_param('quizid', null, PARAM_INT);
    if ($quizid == null) {
        $quizid = $SESSION->quizid;
    }

    if ($quizid != null) {
        $quizname = $DB->get_field("quiz", "name", array("id" => $quizid));
    }
    $categoryoptionidcount = block_exaquest_get_category_question_count($quizid);
    $categoryoptionidkeys = array_keys($categoryoptionidcount);
    $categoryoptions = block_exaquest_get_category_names_by_ids($categoryoptionidkeys);
    $options = [];
    foreach ($categoryoptions as $categoryoption) {
        $options[$categoryoption->categorytype][$categoryoption->id] = $categoryoption->categoryname;
    }
    $categorys_required_counts = block_exaquest_get_fragefaecher_by_courseid_and_quizid($COURSE->id, $quizid);

    // if there are no questions of a category, this category will now show up in the options. ==> add the required fragefaecher so they always show up
    foreach ($categorys_required_counts as $cat_required) {
        // first check if it is even an array, or if it is completely empty still
        // then check, if for THIS specific cat there is already an entry in the array or not
        if (!is_array($options[BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH]) ||
                !array_key_exists($cat_required->id, $options[BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH])) {
            $options[BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH][$cat_required->id] = $cat_required->categoryname;
        }
    }

    $content = array('', '', '', '');
    foreach ($options as $key => $option) {
        foreach ($option as $categoryid => $name) {
            if ($key == BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH) {
                $qcount = array_key_exists($categoryid, $categoryoptionidcount) ? $categoryoptionidcount[$categoryid] : 0;
                $content[$key] .= '<div class="col-lg-12"><span>' . $name . ': ' . $qcount .
                        ' von ' . $categorys_required_counts[$categoryid]->questioncount . '</span></div>';
            } else {
                $content[$key] .= '<div class="col-lg-12"><span>' . $name . ': ' . $categoryoptionidcount[$categoryid] .
                        '</span></div>';
            }
        }
    }
    echo $OUTPUT->heading(get_string('questionbank_selected_quiz', 'block_exaquest') . '' . $quizname, 2);

    // get the questioncounts from questionqcount table for this quiz
    $html = '<div class="container-fluid">
                    <div class="row">
                         <div class="col-lg-3">
                         <div class="col-lg-12 border-bottom exaquest-category-tag-fragencharakter"><h6>Fragencharakter</h6></div>
                            ' . $content[0] . '
                        </div>
                        <div class="col-lg-3">
                        <div class="col-lg-12 border-bottom exaquest-category-tag-klassifikation"><h6>Klassifikation</h6></div>
                            ' . $content[1] . '
                        </div>
                        <div class="col-lg-3">
                        <div class="col-lg-12 border-bottom exaquest-category-tag-fragefach"><h6>Fragefach</h6></div>
                            ' . $content[2] . '
                        </div>
                        <div class="col-lg-3">
                        <div class="col-lg-12 border-bottom exaquest-category-tag-lerninhalt"><h6>Lerninhalt</h6></div>
                            ' . $content[3] . '
                        </div>
                    </div>
                </div>';
    echo "<br/>";
    echo $html;
    echo "<br/>";
}

function block_exaquest_render_buttons_for_exam_questionbank() {
    global $SESSION, $COURSE, $OUTPUT;
    $quizid = optional_param('quizid', null, PARAM_INT);
    if ($quizid == null) {
        $quizid = $SESSION->quizid;
    }
    $catAndCont = get_question_category_and_context_of_course();
    $courseid = $COURSE->id;

    $buttons = block_exaquest_render_check_similiarity_button($quizid, $courseid, $catAndCont);
    $buttons .= ' ';
    //$buttons .= block_exaquest_render_go_to_exam_view_button($quizid, $courseid, $catAndCont);
    // use the mustache template to render this button
    $go_to_exam_view_button = new \block_exaquest\output\button_go_to_exam_view($quizid, $courseid, $catAndCont);
    $buttons .= $OUTPUT->render($go_to_exam_view_button);
    block_exaquest_render_buttons_div_for_exam_questionbank($buttons);
}

function block_exaquest_render_check_similiarity_button($quizid, $courseid, $catAndCont) {
    // add a button that links to exaquest/similarity_comparison.php with questioncategoryid, courseid and examid
    $url_check_similarity = new moodle_url('/blocks/exaquest/similarity_comparison.php',
            array("courseid" => $courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1], "examid" => $quizid));
    return '<a href="' . $url_check_similarity . '" class="btn btn-secondary">'. get_string("exaquest:similarity_title", "block_exaquest") .'</a>';
}

// not needed, use mustache instead
//function block_exaquest_render_go_to_exam_view_button($quizid, $courseid, $catAndCont){
//    // add a button that links to exam_view where the user checks the added questions
//    $url_go_to_exam_view = new moodle_url('/blocks/exaquest/finished_exam_questionbank.php',
//            array('courseid' => $courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1], "quizid" => $quizid));
//    return '<a href="' . $url_go_to_exam_view . '" class="btn btn-primary">'. get_string("check_added_questions", "block_exaquest") .'</a>';
//}

function block_exaquest_render_buttons_for_finished_exam_questionbank() {
    global $SESSION, $COURSE, $OUTPUT, $USER, $DB;
    $quizid = optional_param('quizid', null, PARAM_INT);
    if ($quizid == null) {
        $quizid = $SESSION->quizid;
    }
    $catAndCont = get_question_category_and_context_of_course();
    $courseid = $COURSE->id;

    $buttons = block_exaquest_render_check_similiarity_button($quizid, $courseid, $catAndCont);
    $buttons .= ' ';

    // sendexamtoreview when you are an assigned to add questions
    // fachlichreleaseexam when you are the FP of this quiz

    $assigned_to_fill =
            block_exaquest_get_assigned_quizzes_by_assigntype_and_status($USER->id, BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS,
                    BLOCK_EXAQUEST_QUIZSTATUS_NEW);
    if (array_key_exists($quizid, $assigned_to_fill) && $assigned_to_fill[$quizid]->done == 0) {
        $sendexamtoreview = new \block_exaquest\output\button_send_exam_to_review($quizid, $courseid);
        $buttons .= $OUTPUT->render($sendexamtoreview);
        $buttons .= ' ';
    }

    // if the quiz is new or created, then the FP can release it
    $quizstatus = $DB->get_field(BLOCK_EXAQUEST_DB_QUIZSTATUS, "status", array("quizid" => $quizid));
    if ($quizstatus == BLOCK_EXAQUEST_QUIZSTATUS_NEW || $quizstatus == BLOCK_EXAQUEST_QUIZSTATUS_CREATED) {
        $assigned_as_fp =
                block_exaquest_get_assigned_exams_by_assigntype($courseid, $USER->id,
                        BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER);
        $assigned_as_fp_filter_quizid = array_filter($assigned_as_fp, function($exam) use ($quizid) {
            return $exam->quizid == $quizid;
        });
        if (!empty($assigned_as_fp_filter_quizid)) {
            $missingquestionscount = block_exaquest_get_missing_questions_count($quizid, $courseid);
            $fachlichreleaseexam =
                    new \block_exaquest\output\button_fachlich_release_exam($quizid, $courseid, $missingquestionscount);
            $buttons .= $OUTPUT->render($fachlichreleaseexam);
        }
    }
    block_exaquest_render_buttons_div_for_exam_questionbank($buttons);
}

function block_exaquest_render_buttons_div_for_exam_questionbank($buttons) {
    $html = '<br>';
    $html .= '<div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            ' . $buttons . '
                        </div>
                    </div>';
    echo "<br/>";
    echo $html;
    echo "<br/>";
}

function block_exaquest_array_intersect_field($array1, $array2, $field) {
    $intersected = array();
    foreach ($array1 as $item1) {
        $value1 = $item1->$field;
        foreach ($array2 as $item2) {
            $value2 = $item2->$field;
            if ($value1 === $value2) {
                $intersected[] = $item1;
                break;
            }
        }
    }
    return $intersected;
}

function block_exaquest_get_ungraded_questions_count($quiz) {
    global $DB;

    // check if $quiz is an object with $quiz->id or if it is the id itself
    if (is_object($quiz)) {
        $quizid = $quiz->id;
    } else {
        $quizid = $quiz;
    }

    // explanation of query:
    // quiz_attempts contains every attempt of quizes. use it to get the attempts for this quiz
    // join questions attempts and the details: questioN_attempt_steps
    // only get the most current of those steps, as there are multiple steps and we want to current one
    $sql = "SELECT count(question_attempt.id)
            FROM {quiz_attempts} quiz_attempt
            JOIN {question_attempts} question_attempt ON question_attempt.questionusageid = quiz_attempt.uniqueid
            JOIN {question_attempt_steps} qas ON qas.questionattemptid = question_attempt.id
            WHERE quiz_attempt.quiz = :quizid
            AND qas.state = 'needsgrading'
            AND qas.sequencenumber = (
                SELECT MAX(qas2.sequencenumber)
                FROM {question_attempt_steps} qas2
                WHERE qas2.questionattemptid = question_attempt.id
            )
        ";
    $ungraded_questions_count = $DB->get_field_sql($sql, array('quizid' => $quizid));
    return $ungraded_questions_count;
}



