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
const BLOCK_EXAQUEST_DB_QUESTIONSTATUS = 'block_exaquestquestionstatus';
const BLOCK_EXAQUEST_DB_REVIEWASSIGN = 'block_exaquestreviewassign';
const BLOCK_EXAQUEST_DB_REQUESTQUEST = 'block_exaquestrequestquest';
const BLOCK_EXAQUEST_DB_QUIZSTATUS = 'block_exaquestquizstatus';

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

/**
 * Quiz/Pruefung/Exam Status
 */
const BLOCK_EXAQUEST_QUIZSTATUS_NEW = 0; // Questions are being added
const BLOCK_EXAQUEST_QUIZSTATUS_CREATED = 1; // Questions have been added
const BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED = 2; // Fachlicher Pruefer has released
const BLOCK_EXAQUEST_QUIZSTATUS_TECHNISCH_RELEASED = 3; // MUSSS released it
const BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE = 4; // ongoing exam?
const BLOCK_EXAQUEST_QUIZSTATUS_FINISHED = 5; // exam finished
const BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED = 6; // grades released

/**
 * Misc
 */
const BLOCK_EXAQUEST_REVIEWTYPE_FORMAL = 0;
const BLOCK_EXAQUEST_REVIEWTYPE_FACHLICH = 1;

/**
 * Filter Status
 */
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS = 0;
const BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS = 1;
const BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS_TO_SUBMIT = 2; // created but not submitted for review/assessment
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVIEW = 3;
const BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVIEW = 4;
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVISE = 5;
const BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVISE = 6;
const BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_RELEASE = 7;
const BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_RELEASE = 8;
const BLOCK_EXAQUEST_FILTERSTATUS_All_RELEASED_QUESTIONS = 9;

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

function block_exaquest_request_review($userfrom, $userto, $comment, $questionbankentryid, $questionname, $catAndCont, $courseid) {
    global $DB, $COURSE;
    // enter data into the exaquest tables
    $assigndata = new stdClass;
    $assigndata->questionbankentryid = $questionbankentryid;
    $assigndata->reviewerid = $userto;
    $assigndata->reviewtype = BLOCK_EXAQUEST_REVIEWTYPE_FORMAL;
    $DB->insert_record('block_exaquestreviewassign', $assigndata);
    $assigndata->reviewtype = BLOCK_EXAQUEST_REVIEWTYPE_FACHLICH;
    $DB->insert_record('block_exaquestreviewassign', $assigndata);

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

function block_exaquest_request_revision($userfrom, $userto, $comment, $questionbankentryid, $questionname, $catAndCont,
    $courseid) {
    global $DB, $COURSE;
    // enter data into the exaquest tables
    $assigndata = new stdClass;
    $assigndata->questionbankentryid = $questionbankentryid;
    $assigndata->reviewerid = $userto;
    $assigndata->reviewtype = BLOCK_EXAQUEST_REVIEWTYPE_FORMAL;
    $DB->insert_record('block_exaquestreviewassign', $assigndata);
    $assigndata->reviewtype = BLOCK_EXAQUEST_REVIEWTYPE_FACHLICH;
    $DB->insert_record('block_exaquestreviewassign', $assigndata);

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
    return get_enrolled_users($context, 'block/exaquest:fragenersteller');
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
    $userarray = array_merge($userarray, get_enrolled_users($context, 'block/exaquest:modulverantwortlicher'));
    $userarray = array_merge($userarray, get_enrolled_users($context, 'block/exaquest:fachlfragenreviewer'));
    $userarray = array_merge($userarray, get_enrolled_users($context,
        'block/exaquest:pruefungskoordination')); // TODO: according to 20230112 Feedbackliste. But should they really have the right to review?
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
function block_exaquest_get_questionbankentries_to_formal_review_count($courseid, $userid) {
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
function block_exaquest_get_questionbankentries_to_fachlich_review_count($courseid, $userid) {
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

    $questions = count($DB->get_records_sql($sql, array("coursecategoryid" => $coursecategoryid, "ownerid" => $userid, "newquestion" => BLOCK_EXAQUEST_QUESTIONSTATUS_NEW)));

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
    $sql = "SELECT DISTINCT ra.questionbankentryid
			FROM {" . BLOCK_EXAQUEST_DB_REVIEWASSIGN . "} ra
			WHERE ra.reviewerid = :userid";

    $questions = count($DB->get_records_sql($sql,
        array("userid" => $userid)));

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
    $sql = "SELECT *
			FROM {" . BLOCK_EXAQUEST_DB_REQUESTQUEST . "} req
			WHERE req.userid = :userid";

    $questions = $DB->get_records_sql($sql,
        array("userid" => $userid));

    return $questions;
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

    $sql = "SELECT qs.id
			FROM {" . BLOCK_EXAQUEST_DB_QUESTIONSTATUS . "} qs
			JOIN {question_bank_entries} qe ON qs.questionbankentryid = qe.id
			WHERE qe.ownerid = :ownerid
			AND qs.status = :status";

    $questions =
        count($DB->get_records_sql($sql, array("ownerid" => $userid, "status" => BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE)));

    return $questions;
}

/**
 * Returns count of questions for me to release. TODO: what should that mean? In which case is there a specific person responsible for releasing?
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
    assign_capability('block/exaquest:technicalreview', CAP_ALLOW, $roleid, $context);
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
    assign_capability('block/exaquest:technicalreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:executeexam', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('enrol/category:seesimilaritytab', CAP_ALLOW, $roleid, $context);

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
    assign_capability('block/exaquest:showquestionstoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:editquestiontoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:showfinalisedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:showquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:editallquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:assignsecondexaminator', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:definequestionblockingtime', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('enrol/category:seesimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seeexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seecategorytab', CAP_ALLOW, $roleid, $context);

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
    assign_capability('block/exaquest:showquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:addquestiontoexam', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('enrol/category:seesimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seeexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seecategorytab', CAP_ALLOW, $roleid, $context);

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
    assign_capability('block/exaquest:showfinalisedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:showquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:releasequestion', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:editallquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('enrol/category:seesimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seeexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seecategorytab', CAP_ALLOW, $roleid, $context);

    //added during development
    assign_capability('block/exaquest:showquestionstoreview', CAP_ALLOW, $roleid, $context);

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
    assign_capability('block/exaquest:showownrevisedquestions', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:showquestionstorevise', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:


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
    assign_capability('block/exaquest:showquestionstoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('block/exaquest:editquestiontoreview', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:synchronised', CAP_ALLOW, $roleid, $context);
    //added during development:
    assign_capability('enrol/category:seesimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seeexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seecategorytab', CAP_ALLOW, $roleid, $context);

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
    assign_capability('enrol/category:seesimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seeexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seecategorytab', CAP_ALLOW, $roleid, $context);

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
    assign_capability('enrol/category:seesimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seeexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seecategorytab', CAP_ALLOW, $roleid, $context);

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
    assign_capability('enrol/category:seesimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seeexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seecategorytab', CAP_ALLOW, $roleid, $context);

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
    assign_capability('enrol/category:seesimilaritytab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seeexamstab', CAP_ALLOW, $roleid, $context);
    assign_capability('enrol/category:seecategorytab', CAP_ALLOW, $roleid, $context);

    //
    //role_assign($roleid, $USER->id, $contextid);

    //if ($roleid = $DB->get_field('role', 'id', array('shortname' => 'custom_role')){
    //$context = \context_system::instance(){;
    //assign_capability('block/custom_block:custom_capability', CAP_ALLOW,
    //    $roleid, $context);
    //}
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



    $rows[] = new tabobject('tab_dashboard',
        new moodle_url('/blocks/exaquest/dashboard.php', array("courseid" => $courseid)),
        get_string('dashboard', 'block_exaquest'), null, true);

    if (has_capability('block/exaquest:createquestion', \context_course::instance($COURSE->id))) {
        $rows[] = new tabobject('tab_get_questions',
            new moodle_url('/blocks/exaquest/questbank.php',
                array("courseid" => $courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1])),
            get_string('get_questionbank', 'block_exaquest'), null, true);
    }

    if (has_capability('block/exaquest:seesimilaritytab', \context_course::instance($COURSE->id))) {
        $rows[] = new tabobject('tab_similarity_comparison',
            new moodle_url('/blocks/exaquest/similarity_comparison.php', array("courseid" => $courseid)),
            get_string('similarity', 'block_exaquest'), null, true);
    }


    if (has_capability('block/exaquest:seeexamstab', \context_course::instance($COURSE->id))) {
        $rows[] = new tabobject('tab_exams',
            new moodle_url('/blocks/exaquest/exams.php', array("courseid" => $courseid)),
            get_string('exams', 'block_exaquest'), null, true);
    }

    if (has_capability('block/exaquest:seecategorytab', \context_course::instance($COURSE->id))) {
        $rows[] = new tabobject('tab_category_settings',
            new moodle_url('/blocks/exaquest/category_settings.php', array("courseid" => $courseid)),
            get_string('category_settings', 'block_exaquest'), null, true);
    }



    return $rows;
}

// this is used to get the contexts of the category in the questionbank
function get_question_category_and_context_of_course($courseid = null) {
    global $COURSE, $DB;
    if ($courseid == null) {
        $courseid = $COURSE->id;
    }
    // this is used to get the contexts of the category in the questionbank
    $context = context_course::instance($courseid);
    $contexts = explode('/', $context->path);
    $questioncategory = $DB->get_records('question_categories', ['contextid' => $contexts[2]]);
    $category =
        end($questioncategory); // an actual array, not a returnvalue of a function has to be passed, since it sets the internal pointer of the array, so there has to be a real array
    if ($category) {
        return [$category->id, $contexts[2]];
    } else {
        return false;
    }

}

/**block_instances
 *
 * Returns all course ids where an instance of Exabis question management tool is installed
 */
function block_exaquest_get_courseids() {
    global $DB, $USER;
    $instances = $DB->get_records('block_instances', array('blockname' => 'exaquest'));

    $exaquest_courses = array();

    foreach ($instances as $instance) {
        $context = $DB->get_record('context', array('id' => $instance->parentcontextid, 'contextlevel' => CONTEXT_COURSE));
        if ($context) {
            if (block_exaquest_is_user_in_course($USER->id, $context->instanceid)) {
                $exaquest_courses[$context->instanceid] = $context->instanceid;
            }
        }
    }

    return $exaquest_courses;
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
function block_exaquest_exams_by_status($coursecategoryid, $status) {
    global $DB, $USER;

    $sql = "SELECT *
			FROM {" . BLOCK_EXAQUEST_DB_QUIZSTATUS . "} quizstatus
			JOIN {quiz} q on q.id = quizstatus.quizid 
			WHERE quizstatus.status = :status
			AND quizstatus.coursecategoryid = :coursecategoryid";

    $quizzes = $DB->get_records_sql($sql,
        array("status" => $status, "coursecategoryid" => $coursecategoryid));

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
    $capabilities["showquestionstorevise"] = is_enrolled($context, $USER, "block/exaquest:showquestionstorevise");
    $capabilities["createquestions"] = is_enrolled($context, $USER, "block/exaquest:createquestion");
    $capabilities["releasequestion"] = is_enrolled($context, $USER, "block/exaquest:releasequestion");
    $capabilities["readallquestions"] = is_enrolled($context, $USER, "block/exaquest:readallquestions");
    $capabilities["readquestionstatistics"] = is_enrolled($context, $USER, "block/exaquest:readquestionstatistics");
    $capabilities["changestatusofreleasedquestions"] =
        is_enrolled($context, $USER, "block/exaquest:changestatusofreleasedquestions");
    $capabilities["setstatustoreview"] = is_enrolled($context, $USER, "block/exaquest:setstatustoreview");
    $capabilities["reviseownquestion"] = is_enrolled($context, $USER, "block/exaquest:reviseownquestion");
    $capabilities["setstatustofinalised"] = is_enrolled($context, $USER, "block/exaquest:setstatustofinalised");
    $capabilities["showownrevisedquestions"] = is_enrolled($context, $USER, "block/exaquest:showownrevisedquestions");
    $capabilities["showquestionstoreview"] = is_enrolled($context, $USER, "block/exaquest:showquestionstoreview");
    $capabilities["editquestiontoreview"] = is_enrolled($context, $USER, "block/exaquest:editquestiontoreview");
    $capabilities["showfinalisedquestions"] = is_enrolled($context, $USER, "block/exaquest:showfinalisedquestions");
    $capabilities["showquestionstorevise"] = is_enrolled($context, $USER, "block/exaquest:showquestionstorevise");
    $capabilities["editallquestions"] = is_enrolled($context, $USER, "block/exaquest:editallquestions");
    $capabilities["addquestiontoexam"] = is_enrolled($context, $USER, "block/exaquest:addquestiontoexam");
    $capabilities["releaseexam"] = is_enrolled($context, $USER, "block/exaquest:releaseexam");
    $capabilities["technicalreview"] = is_enrolled($context, $USER, "block/exaquest:technicalreview");
    $capabilities["executeexam"] = is_enrolled($context, $USER, "block/exaquest:executeexam");
    $capabilities["assignsecondexaminator"] = is_enrolled($context, $USER, "block/exaquest:assignsecondexaminator");
    $capabilities["definequestionblockingtime"] = is_enrolled($context, $USER, "block/exaquest:definequestionblockingtime");
    $capabilities["showexamresults"] = is_enrolled($context, $USER, "block/exaquest:showexamresults");
    $capabilities["gradeexam"] = is_enrolled($context, $USER, "block/exaquest:gradeexam");
    $capabilities["createexamstatistics"] = is_enrolled($context, $USER, "block/exaquest:createexamstatistics");
    $capabilities["showexamstatistics"] = is_enrolled($context, $USER, "block/exaquest:showexamstatistics");
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
    //$capabilities["seestatistic"] = is_enrolled($context, $USER, "block/exaquest:seestatistic");

    // there is no logic in mustache ==> do it here. Often roles overlap.
    //$capabilities["fragenersteller_or_fachlfragenreviewer"] = $capabilities["fragenersteller"] || $capabilities["fachlfragenreviewer"];
    //$capabilities["modulverantwortlicher_or_pruefungskoordination"] = $capabilities["modulverantwortlicher"] || $capabilities["pruefungskoordination"];
    // TODO: do not do it like this, but write e.g. "release_questions" capability and give it to modulverantwrotlicher and pruefungskoordination
    return $capabilities;
}

