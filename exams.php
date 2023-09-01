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
$PAGE->set_heading(get_string('exams_of_course', 'block_exaquest', $COURSE->fullname));
$PAGE->set_title(get_string('exams_of_course', 'block_exaquest', $COURSE->fullname));

block_exaquest_init_js_css();

$output = $PAGE->get_renderer('block_exaquest');

echo $output->header($context, $courseid, get_string('exams_overview', 'block_exaquest'));

$action = optional_param('action', "", PARAM_ALPHAEXT);
if ($action == 'assign_quiz_addquestions') {
    $comment = optional_param('assignaddquestionscomment', '', PARAM_TEXT);
    $quizid = required_param('quizid', PARAM_INT);
    // get quiz for quizname
    $quizname = $DB->get_field('quiz', 'name', array('id' => $quizid));
    //$selectedfpkey = "selectedusersfp".$quizid."popup_assign_addquestions";
    //if (array_key_exists($selectedfpkey, $_POST)) {
    //    $selectedfp = clean_param($_POST[$selectedfpkey], PARAM_INT);
    //    block_exaquest_assign_quiz_addquestions($courseid, $USER, $selectedfp, $comment, $quizid, $quizname, BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS);
    //}
    // not needed anymore, since FP is set in quiz settings
    // check if selecteduserspmw is set and an array
    $selectedpmwkey = "selecteduserspmw".$quizid."popup_assign_addquestions";
    if (array_key_exists($selectedpmwkey, $_POST) && is_array($_POST[$selectedpmwkey])) {
        $selectedpmw = clean_param_array($_POST[$selectedpmwkey], PARAM_INT);
        foreach ($selectedpmw as $pmw) {
            block_exaquest_assign_quiz_addquestions($courseid, $USER, $pmw, $comment, $quizid, $quizname, BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS);
        }
    }
}else if ($action == 'set_questioncount_per_quiz_and_fragefach'){
    $quizid = required_param('quizid', PARAM_INT);
    if (array_key_exists("fragefaecher", $_POST)) {
        $fragefaecher = clean_param_array($_POST["fragefaecher"], PARAM_INT);
        foreach ($fragefaecher as $exaquestcategoryid => $count) {
            block_exaquest_set_questioncount_for_exaquestcategory($quizid, $exaquestcategoryid, $count);
        }
    }
}

// RENDER:
$capabilities = [];
$capabilities["createquestion"] = is_enrolled($context, $USER, "block/exaquest:createquestion");
$capabilities["viewnewexams"] = is_enrolled($context, $USER, "block/exaquest:viewnewexams");
$capabilities["viewcreatedexams"] = is_enrolled($context, $USER, "block/exaquest:viewcreatedexams");
$capabilities["viewreleasedexams"] = is_enrolled($context, $USER, "block/exaquest:viewreleasedexams");
$capabilities["viewactiveexams"] = is_enrolled($context, $USER, "block/exaquest:viewactiveexams");
$capabilities["viewfinishedexams"] = is_enrolled($context, $USER, "block/exaquest:viewfinishedexams");
$capabilities["viewgradesreleasedexams"] = is_enrolled($context, $USER, "block/exaquest:viewgradesreleasedexams");
$capabilities["viewgradesreleasedexams"] = is_enrolled($context, $USER, "block/exaquest:viewgradesreleasedexams");
$capabilities["addquestiontoexam"] = has_capability('block/exaquest:addquestiontoexam', $context); // has_capability actually makes more sense than is_enrolled, even though the outcome is the same
$capabilities["assignaddquestions"] = has_capability('block/exaquest:assignaddquestions', $context);
$capabilities["createexam"] = has_capability('block/exaquest:createexam', $context);
$capabilities["setquestioncount"] = has_capability('block/exaquest:setquestioncount', $context);

$exams = new \block_exaquest\output\exams($USER->id, $courseid, $capabilities);
echo $output->render($exams);

//echo '</div>';
echo $output->footer();
