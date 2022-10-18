<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $PAGE, $OUTPUT, $USER;

require_once($CFG->dirroot . '/question/editlib.php');
require_once(__DIR__ . '/questionbank_extensions/exaquest_view.php');

$courseid = required_param('courseid', PARAM_INT);
require_login($courseid);

//$course = $DB->get_record('course', array('id' => $courseid));
$context = context_course::instance($courseid);


$page_params = array('courseid' => $courseid);

$url = new moodle_url('/blocks/exaquest/quizzes.php', $page_params);
$PAGE->set_url($url);
$PAGE->set_heading(get_string('quizzes', 'block_exaquest'));
$PAGE->set_title(get_string('quizzes', 'block_exaquest'));

block_exaquest_init_js_css();

$output = $PAGE->get_renderer('block_exaquest');

echo $output->header($context, $courseid, get_string('quizzes_overview', 'block_exaquest'));


// RENDER:
$capabilities = [];
$capabilities["createquestions"] = is_enrolled($context, $USER, "block/exaquest:createquestion");

$quizzes = new \block_exaquest\output\quizzes($USER->id, $courseid, $capabilities);
echo $output->render($quizzes);

//echo '</div>';
echo $output->footer();
