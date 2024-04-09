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
    //$selectedfpkey = "selectedfp".$quizid."popup_assign_addquestions";
    //if (array_key_exists($selectedfpkey, $_POST)) {
    //    $selectedfp = clean_param($_POST[$selectedfpkey], PARAM_INT);
    //    block_exaquest_assign_quiz_addquestions($courseid, $USER, $selectedfp, $comment, $quizid, $quizname, BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS);
    //}
    // not needed anymore, since FP is set in quiz settings
    // check if selectedpmw is set and an array
    $selectedpmwkey = "selectedpmw" . $quizid . "popup_assign_addquestions";
    if (array_key_exists($selectedpmwkey, $_POST) && is_array($_POST[$selectedpmwkey])) {
        $selectedpmw = clean_param_array($_POST[$selectedpmwkey], PARAM_INT);
        foreach ($selectedpmw as $pmw) {
            block_exaquest_assign_quiz_addquestions($USER, $pmw, $comment, $quizid, $quizname,
                    BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS);
        }
    }
} else if ($action == 'set_questioncount_per_quiz_and_fragefach') {
    $quizid = required_param('quizid', PARAM_INT);
    if (array_key_exists("fragefaecher", $_POST)) {
        $fragefaecher = clean_param_array($_POST["fragefaecher"], PARAM_INT);
        foreach ($fragefaecher as $exaquestcategoryid => $count) {
            block_exaquest_set_questioncount_for_exaquestcategory($quizid, $exaquestcategoryid, $count);
        }
    }
} else if ($action == 'assign_check_exam_grading') {
    $comment = optional_param('assign_check_exam_grading_comment', '', PARAM_TEXT);
    $quizid = required_param('quizid', PARAM_INT);
    $quizname = $DB->get_field('quiz', 'name', array('id' => $quizid));

    // FP and BMW have the same behaviour ==> both are in selectedbmw
    $selectedbmwkey = "selectedbmw" . $quizid . "popup_assign_check_exam_grading";
    if (array_key_exists($selectedbmwkey, $_POST) && is_array($_POST[$selectedbmwkey])) {
        $selectedusers = clean_param_array($_POST[$selectedbmwkey], PARAM_INT);
        foreach ($selectedusers as $selecteduser) {
            block_exaquest_assign_check_exam_grading($USER, $selecteduser, $comment, $quizid, $quizname,
                    BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHECK_EXAM_GRADING);
        }
    }
} else if ($action == 'assign_gradeexam') {
    $comment = optional_param('assign_grade_exam_comment', '', PARAM_TEXT);
    $quizid = required_param('quizid', PARAM_INT);
    $quizname = $DB->get_field('quiz', 'name', array('id' => $quizid));

    //selectedusersquestions15popup_assign_gradeexam
    $selectedquestionskey = "selectedquestions" . $quizid . "popup_assign_gradeexam";
    if (array_key_exists($selectedquestionskey, $_POST) && is_array($_POST[$selectedquestionskey])) {
        $selectedquestions = clean_param_array($_POST[$selectedquestionskey], PARAM_INT);
    }

    $selectedbmwkey = "selectedbmw" . $quizid . "popup_assign_gradeexam";
    if (array_key_exists($selectedbmwkey, $_POST) && is_array($_POST[$selectedbmwkey])) {
        $selectedbmw = clean_param_array($_POST[$selectedbmwkey], PARAM_INT);
        foreach ($selectedbmw as $bmw) {
            block_exaquest_assign_gradeexam($USER, $bmw, $comment, $quizid, $quizname, BLOCK_EXAQUEST_QUIZASSIGNTYPE_GRADE_EXAM,
                    $selectedquestions);
        }
    }
} else if ($action == 'assign_kommissionell_check_exam_grading') {
    $comment = optional_param('assign_kommissionell_check_exam_grading_comment', '', PARAM_TEXT);
    $quizid = required_param('quizid', PARAM_INT);
    $quizname = $DB->get_field('quiz', 'name', array('id' => $quizid));

    //selectedusersquestions15popup_assign_kommissionell_check_exam_grading
    $selectedstudentskey = "selectedstudent" . $quizid . "popup_assign_kommissionell_check_exam_grading";
    if (array_key_exists($selectedstudentskey, $_POST) && is_array($_POST[$selectedstudentskey])) {
        $selectedstudents = clean_param_array($_POST[$selectedstudentskey], PARAM_INT);
    }

    $selectedfpkey = "selectedfp" . $quizid . "popup_assign_kommissionell_check_exam_grading";
    if (array_key_exists($selectedfpkey, $_POST) && is_array($_POST[$selectedfpkey])) {
        $selectedfp = clean_param_array($_POST[$selectedfpkey], PARAM_INT);
        foreach ($selectedfp as $fp) {
            block_exaquest_assign_kommissionell_check_exam_grading($USER, $fp, $comment, $quizid, $quizname, BLOCK_EXAQUEST_QUIZASSIGNTYPE_KOMMISSIONELL_CHECK_EXAM_GRADING,
                    $selectedstudents);
        }
    }
} else if ($action == 'assign_change_exam_grading') {
    $comment = optional_param('assign_change_exam_grading_comment', '', PARAM_TEXT);
    $quizid = required_param('quizid', PARAM_INT);
    $quizname = $DB->get_field('quiz', 'name', array('id' => $quizid));
    $pk = block_exaquest_get_pk_by_courseid($courseid);
    foreach ($pk as $pruefungskoordination) {
        block_exaquest_assign_change_exam_grading($USER, $pruefungskoordination->id, $comment, $quizid, $quizname,
                BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHANGE_EXAM_GRADING);
    }
}

// RENDER:
$capabilities = [];
$capabilities["createquestion"] = is_enrolled($context, $USER, "block/exaquest:createquestion");
$capabilities["viewnewexamscard"] = is_enrolled($context, $USER, "block/exaquest:viewnewexamscard");
$capabilities["viewcreatedexamscard"] = is_enrolled($context, $USER, "block/exaquest:viewcreatedexamscard");
$capabilities["viewreleasedexamscard"] = is_enrolled($context, $USER, "block/exaquest:viewreleasedexamscard");
$capabilities["viewactiveexamscard"] = is_enrolled($context, $USER, "block/exaquest:viewactiveexamscard");
$capabilities["viewfinishedexamscard"] = is_enrolled($context, $USER, "block/exaquest:viewfinishedexamscard");
$capabilities["viewgradesreleasedexamscard"] = is_enrolled($context, $USER, "block/exaquest:viewgradesreleasedexamscard");
$capabilities["addquestiontoexam"] = has_capability('block/exaquest:addquestiontoexam',
        $context); // has_capability actually makes more sense than is_enrolled, even though the outcome is the same
$capabilities["assignaddquestions"] = has_capability('block/exaquest:assignaddquestions', $context);
$capabilities["createexam"] = has_capability('block/exaquest:createexam', $context);
$capabilities["setquestioncount"] = has_capability('block/exaquest:setquestioncount', $context);
$capabilities["doformalreviewexam"] = has_capability('block/exaquest:doformalreviewexam', $context);
$capabilities["dofachlichreviewexam"] = has_capability('block/exaquest:dofachlichreviewexam', $context);
$capabilities["assigncheckexamgrading"] = has_capability("block/exaquest:assigncheckexamgrading", $context, $USER);
//$capabilities["skipandreleaseexam"] = has_capability("block/exaquest:skipandreleaseexam", $context, $USER);
$capabilities["forcesendexamtoreview"] = has_capability("block/exaquest:forcesendexamtoreview", $context, $USER);
$capabilities["assigngradeexam"] = has_capability("block/exaquest:assigngradeexam", $context, $USER);
$capabilities["checkgradingforfp"] = has_capability("block/exaquest:checkgradingforfp", $context, $USER);
$capabilities["pruefungskoordination"] = has_capability("block/exaquest:pruefungskoordination", $context, $USER);
$capabilities["checkexamsgrading"] = has_capability("block/exaquest:checkexamsgrading", $context, $USER);

$exams = new \block_exaquest\output\exams($USER->id, $courseid, $capabilities);
echo $output->render($exams);

//echo '</div>';
echo $output->footer();
