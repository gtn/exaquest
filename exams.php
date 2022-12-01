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

$url = new moodle_url('/blocks/exaquest/exams.php', $page_params);
$PAGE->set_url($url);
$PAGE->set_heading(get_string('exams', 'block_exaquest'));
$PAGE->set_title(get_string('exams', 'block_exaquest'));

block_exaquest_init_js_css();

// create quiz needs to call this: create_module($moduleinfo)

/* adding questions to quiz:
question_bank::get_qtype($qtype)->save_question($question, $fromform);
$question needs to look like

        $question = new stdClass();
        $question->category  = $fromform->category;
        $question->qtype     = $qtype;
        $question->createdby = $USER->id;
*/

$output = $PAGE->get_renderer('block_exaquest');

echo $output->header($context, $courseid, get_string('exams_overview', 'block_exaquest'));


// RENDER:
$capabilities = [];
$capabilities["createquestions"] = is_enrolled($context, $USER, "block/exaquest:createquestion");

$exams = new \block_exaquest\output\exams($USER->id, $courseid, $capabilities);
echo $output->render($exams);

//echo '</div>';
echo $output->footer();
