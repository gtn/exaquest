<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $PAGE, $OUTPUT, $USER;

require_once($CFG->dirroot . '/question/editlib.php');
require_once(__DIR__ . '/questionbank_extensions/exaquest_view.php');



$courseid = required_param('courseid', PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);
$name = optional_param('name', '', PARAM_TEXT);
require_login($courseid);

//$course = $DB->get_record('course', array('id' => $courseid));
$context = context_course::instance($courseid);

require_capability('block/exaquest:viewexamstab', $context);

$page_params = array('courseid' => $courseid);

$url = new moodle_url('/blocks/exaquest/exams.php', $page_params);
$PAGE->set_url($url);
$PAGE->set_heading(get_string('exams', 'block_exaquest'));
$PAGE->set_title(get_string('exams', 'block_exaquest'));

block_exaquest_init_js_css();

$output = $PAGE->get_renderer('block_exaquest');

echo $output->header($context, $courseid, get_string('exams_overview', 'block_exaquest'));

// RENDER:
$capabilities = [];
$capabilities["createquestions"] = is_enrolled($context, $USER, "block/exaquest:createquestion");
$capabilities["viewnewexams"] = is_enrolled($context, $USER, "block/exaquest:viewnewexams");
$capabilities["viewcreatedexams"] = is_enrolled($context, $USER, "block/exaquest:viewcreatedexams");
$capabilities["viewreleasedexams"] = is_enrolled($context, $USER, "block/exaquest:viewreleasedexams");
$capabilities["viewactiveexams"] = is_enrolled($context, $USER, "block/exaquest:viewactiveexams");
$capabilities["viewfinishedexams"] = is_enrolled($context, $USER, "block/exaquest:viewfinishedexams");
$capabilities["viewgradesreleasedexams"] = is_enrolled($context, $USER, "block/exaquest:viewgradesreleasedexams");

$exams = new \block_exaquest\output\exams($USER->id, $courseid, $capabilities);
echo $output->render($exams);

//echo '</div>';
echo $output->footer();
