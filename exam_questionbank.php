<?php
global $DB;
require __DIR__ . '/inc.php';

global $CFG, $COURSE, $PAGE, $OUTPUT, $SESSION;

use core\event\question_category_viewed;

require_once($CFG->dirroot . '/question/editlib.php');
require_once($CFG->dirroot . '/blocks/exaquest/classes/questionbank_extensions/exaquest_exam_view.php');

block_exaquest_init_js_css();

list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) =
    question_edit_setup('questions', '/blocks/exaquest/exam_questionbank.php');

$courseid = required_param('courseid', PARAM_INT);
$filterstatus = optional_param('filterstatus', 0, PARAM_INT);
$fragencharakter = optional_param('fragencharakter', -2, PARAM_INT);
$klassifikation = optional_param('klassifikation', -2, PARAM_INT);
$fragefach = optional_param('fragefach', -2, PARAM_INT);
$lehrinhalt = optional_param('lehrinhalt', -2, PARAM_INT);
$quizid = required_param('quizid', PARAM_INT);

require_login($courseid);
//require_capability('block/exaquest:viewcategorytab', context_course::instance($courseid));

// check the status of the quiz. If it is not BLOCK_EXAQUEST_QUIZSTATUS_CREATED or BLOCK_EXAQUEST_QUIZSTATUS_NEW or BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED it is not allowed to edit the questions
if ($quizid != null) {
    $quiz = $DB->get_record_sql('SELECT qs.status
        FROM {' . BLOCK_EXAQUEST_DB_QUIZSTATUS . '} qs
         WHERE qs.quizid = ?',
        array($quizid));

    if (!($quiz->status == BLOCK_EXAQUEST_QUIZSTATUS_CREATED || $quiz->status == BLOCK_EXAQUEST_QUIZSTATUS_NEW || $quiz->status == BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED)) {
        // throw exception
        throw new moodle_exception('block_exaquest:quiz_not_editable', 'block_exaquest');
    }
}

if (!property_exists($SESSION, 'filterstatus')) {
    $SESSION->filterstatus = 0;
}
if (!property_exists($SESSION, 'fragencharakter')) {
    $SESSION->fragencharakter = -1;
}
if (!property_exists($SESSION, 'klassifikation')) {
    $SESSION->klassifikation = -1;
}
if (!property_exists($SESSION, 'fragefach')) {
    $SESSION->fragefach = -1;
}
if (!property_exists($SESSION, 'lehrinhalt')) {
    $SESSION->lehrinhalt = -1;
}

if ($filterstatus != -1) {
    $SESSION->filterstatus = $filterstatus;
}
if ($fragencharakter != -2) {
    $SESSION->fragencharakter = $fragencharakter;
}
if ($klassifikation != -2) {
    $SESSION->klassifikation = $klassifikation;
}
if ($fragefach != -2) {
    $SESSION->fragefach = $fragefach;
}
if ($lehrinhalt != -2) {
    $SESSION->lehrinhalt = $lehrinhalt;
}
if ($quizid != null) {
    $SESSION->quizid = $quizid;
}

$pagevars['filterstatus'] = $SESSION->filterstatus;
$pagevars['fragencharakter'] = $SESSION->fragencharakter;
$pagevars['klassifikation'] = $SESSION->klassifikation;
$pagevars['fragefach'] = $SESSION->fragefach;
$pagevars['lehrinhalt'] = $SESSION->lehrinhalt;

$catAndCont = get_question_category_and_context_of_course();
$pagevars['cat'] = $catAndCont[0] . ',' . $catAndCont[1];

$page_params = array('courseid' => $courseid, "category" => $pagevars['cat']);

$url = new moodle_url('/blocks/exaquest/exam_questionbank.php', $page_params);

$PAGE->set_url($url);
$PAGE->set_heading('showQuestionBank');
//$streditingquestions = get_string('editquestions', 'question');
//$PAGE->set_title(block_exacomp_get_string($streditingquestions));
$PAGE->set_title('showQuestionBank');

$context = context_course::instance($courseid);
$output = $PAGE->get_renderer('block_exaquest');
echo $output->header($context, $courseid, get_string('get_questionbank', 'block_exaquest'));

if (($lastchanged = optional_param('lastchanged', 0, PARAM_INT)) !== 0) {
    $url->param('lastchanged', $lastchanged);
}

$questionbank = new \block_exaquest\questionbank_extensions\exaquest_exam_view($contexts, $url, $COURSE, $cm);

echo '<div class="questionbankwindow boxwidthwide boxaligncenter">';
$questionbank->display($pagevars, 'editq');
echo "</div>\n";

echo $output->footer();
