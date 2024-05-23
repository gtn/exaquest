<?php
require __DIR__ . '/inc.php';

global $CFG, $COURSE, $PAGE, $OUTPUT, $SESSION;


require_once($CFG->dirroot . '/question/editlib.php');
require_once(__DIR__ . '/questionbank_extensions/exaquest_view.php');
use qbank_managecategories\helper;

list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) =
    question_edit_setup('questions', '/question/edit.php');


$courseid = required_param('courseid', PARAM_INT);
$filterstatus = optional_param('filterstatus', -1, PARAM_INT);
$fragencharakter = optional_param('fragencharakter', -2, PARAM_INT);
$klassifikation = optional_param('klassifikation', -2, PARAM_INT);
$fragefach = optional_param('fragefach', -2, PARAM_INT);
$lehrinhalt = optional_param('lehrinhalt', -2, PARAM_INT);
$category = optional_param('category', '', PARAM_TEXT);

require_login($courseid);
require_capability('block/exaquest:viewquestionbanktab', context_course::instance($courseid));

// this inititialises the categories in the $SESSION
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

//this updates the $SESSION if it is not the value for unseletiong the filter
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

// this adds it to the pagevars that are passed to the view.display() function
$pagevars['filterstatus'] = $SESSION->filterstatus;
$pagevars['fragencharakter'] = $SESSION->fragencharakter;
$pagevars['klassifikation'] = $SESSION->klassifikation;
$pagevars['fragefach'] = $SESSION->fragefach;
$pagevars['lehrinhalt'] = $SESSION->lehrinhalt;

// not sure if this is actually used anywhere, becasue we jsut call this function
$catAndCont = get_question_category_and_context_of_course();
$pagevars['cat'] = $catAndCont[0] . ',' . $catAndCont[1];

$page_params = array('courseid' => $courseid);

$url = new moodle_url('/blocks/exaquest/questbank.php', $page_params);

$PAGE->set_url($url);
$PAGE->set_heading(get_string('questionbank_of_course', 'block_exaquest', $COURSE->fullname));
$PAGE->set_title(get_string('questionbank_of_course', 'block_exaquest', $COURSE->fullname));

$context = context_course::instance($courseid);
$output = $PAGE->get_renderer('block_exaquest');

// call to display view
$questionbank = new core_question\local\bank\exaquest_view($contexts, $url, $COURSE, $cm);

if (@$_REQUEST['table_sql']) {
    $questionbank_table = new \block_exaquest\tables\questionbank_table(
        $questionbank,
        preg_replace('!,.*!', '', $category) ?: 0 // TODO: gibts da mehrere ids?!?. Die erste Zahl ist die question_cateogry_id, die zweite die context_id. Kommt von "get_question_category_and_context_of_course", da ist es genauer beschrieben
    );

    echo $output->header($context, $courseid, get_string('get_questionbank', 'block_exaquest'));

    // print the create_question_button
    $coursecategorycontextid = $catAndCont[1];
    $coursecategory = helper::get_categories_for_contexts($coursecategorycontextid, 'id', false);
    $coursecategory = array_pop($coursecategory);
    $coursecategorycontext = \context::instance_by_id($coursecategorycontextid);
    $canadd = has_capability('moodle/question:add', $coursecategorycontext);
    $questionbank->create_new_question_form_dashboard($coursecategory, $canadd);


    $questionbank_table->out();

    echo $output->footer();

    exit;
}

echo $output->header($context, $courseid, get_string('get_questionbank', 'block_exaquest'));

if (($lastchanged = optional_param('lastchanged', 0, PARAM_INT)) !== 0) {
    $url->param('lastchanged', $lastchanged);
}

echo '<div class="questionbankwindow boxwidthwide boxaligncenter">';
$questionbank->display($pagevars, 'editq');
echo "</div>\n";

echo $output->footer();
