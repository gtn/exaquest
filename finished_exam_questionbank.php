<?php
global $DB;
require __DIR__ . '/inc.php';

global $CFG, $COURSE, $PAGE, $OUTPUT;

use core\event\question_category_viewed;

require_once($CFG->dirroot . '/question/editlib.php');
require_once($CFG->dirroot . '/blocks/exaquest/classes/questionbank_extensions/exaquest_finished_exam_view.php');

list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) =
    question_edit_setup('questions', '/blocks/exaquest/finished_exam_questionbank.php');

$courseid = required_param('courseid', PARAM_INT);
$filterstatus = optional_param('filterstatus', 0, PARAM_INT);

require_login($courseid);
//require_capability('block/exaquest:viewcategorytab', context_course::instance($courseid));

$quizid = required_param('quizid', PARAM_INT);
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

$pagevars['filterstatus'] = $filterstatus;
$catAndCont = get_question_category_and_context_of_course();
$pagevars['cat'] = $catAndCont[0] . ',' . $catAndCont[1];

$page_params = array('courseid' => $courseid);

$url = new moodle_url('/blocks/exaquest/finished_exam_questionbank.php', $page_params);

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

$questionbank = new \block_exaquest\questionbank_extensions\exaquest_finished_exam_view($contexts, $url, $COURSE, $cm);

echo '<div class="questionbankwindow boxwidthwide boxaligncenter">';
$questionbank->display($pagevars, 'editq');
echo "</div>\n";

echo $output->footer();
