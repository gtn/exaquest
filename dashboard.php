<?php

use qbank_managecategories\helper;

require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $PAGE, $OUTPUT, $USER;

require_once($CFG->dirroot . '/question/editlib.php');
require_once($CFG->dirroot . '/blocks/exaquest/classes/questionbank_extensions/exaquest_view.php');

$courseid = required_param('courseid', PARAM_INT);
require_login($courseid);
require_capability('block/exaquest:viewdashboardtab', context_course::instance($courseid));

//$course = $DB->get_record('course', array('id' => $courseid));
$context = context_course::instance($courseid);

$coursecategoryid = block_exaquest_get_coursecategoryid_by_courseid($courseid);
[$questioncategoryid, $coursecategorycontextid] = get_question_category_and_context_of_course($courseid);


// if (is_enrolled($context, $USER, "block/exaquest:createquestion")) {
//     list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) =
//         question_edit_setup('questions', '/question/edit.php'); // this sets the "Question bank" in the navbar... check out what that does
// }
if (is_enrolled($context, $USER, "block/exaquest:createquestion")) {
    list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) =
        question_edit_setup('questions', '/blocks/exaquest/dashboard.php'); // baseurl has to be correct! Otherwise "Question bank" in the navbar will be selected
    // "questions" is used for checking the capabilities
    // it checks if the user has any capability of the question: capabilities... e.g. question:add or question:editmine
}

$page_params = array('courseid' => $courseid);

$url = new moodle_url('/blocks/exaquest/dashboard.php', $page_params);
$PAGE->set_url($url);
$PAGE->set_heading(get_string('dashboard_of_course', 'block_exaquest', $COURSE->fullname));
$PAGE->set_title(get_string('dashboard_of_course', 'block_exaquest', $COURSE->fullname));

block_exaquest_init_js_css();

$output = $PAGE->get_renderer('block_exaquest');

echo $output->header($context, $courseid, get_string('dashboard', 'block_exaquest'));
$fragenersteller = array();
$fachlichepruefer = array();
$action = optional_param('action', "", PARAM_ALPHAEXT);
if($action){
    require_sesskey();
}
if ($action == 'request_questions') {
    // get all the users with role "fragesteller" and send them a notification

    $allfragenersteller = block_exaquest_get_fragenersteller_by_courseid($courseid);
    // by courseid or coursecategoryid? --> does not matter, as the role from the category will be used to enrol the user into the course
    if (array_key_exists("selectedpopup_request_questions", $_POST)) {
        if (is_array($_POST["selectedpopup_request_questions"])) {
            $selectedfragenersteller = clean_param_array($_POST["selectedpopup_request_questions"], PARAM_INT);
        } else {
            $selectedfragenersteller = clean_param($_POST["selectedpopup_request_questions"], PARAM_INT);
        }
        $requestcomment = clean_param($_POST["requestcomment"], PARAM_TEXT);

        if ($selectedfragenersteller) {
            $fragenersteller = array_intersect_key($allfragenersteller, array_flip($selectedfragenersteller));
            foreach ($fragenersteller as $ersteller) {
                block_exaquest_request_question($USER->id, $ersteller->id, $requestcomment);
            }
        }
    }
}
//else if ($action == 'request_exams') {
//// get all the users with role "fachlicherpruefer" and send them a notification
//$allfachlichepruefer = block_exaquest_get_fachlichepruefer_by_courseid($courseid);
//if (array_key_exists("selectedusers", $_POST)) {
//    if (is_array($_POST["selectedusers"])) {
//        $selectedfachlicherpruefer = clean_param_array($_POST["selectedusers"], PARAM_INT);
//    } else {
//        $selectedfachlicherpruefer = clean_param($_POST["selectedusers"], PARAM_INT);
//    }
//    $requestcomment = clean_param($_POST["requestcomment"], PARAM_TEXT);
//
//    if ($selectedfachlicherpruefer) {
//        $fachlichepruefer = array_intersect_key($allfachlichepruefer, $selectedfachlicherpruefer);
//        foreach ($fachlichepruefer as $pruefer) {
//            block_exaquest_request_exam($USER->id, $pruefer->id, $requestcomment);
//        }
//    }
//}
//}

// RENDER:
$capabilities = block_exaquest_get_capabilities($context);

if ($capabilities["modulverantwortlicher"] || $capabilities["pruefungskoordination"]) {
    if (!isset($fragenersteller) || empty($data->fragenersteller)) {
        $fragenersteller = block_exaquest_get_fragenersteller_by_courseid($courseid);
        $fachlichepruefer = block_exaquest_get_fachlichepruefer_by_courseid($courseid);
    }
}

$questions_to_create = [];
if ($capabilities["fragenersteller"]) {
    $questions_to_create = block_exaquest_get_questions_for_me_to_create($coursecategoryid, $USER->id);
}

$exams_to_fill = [];
if ($capabilities["addquestiontoexam"]) {
    $exams_to_fill =
        block_exaquest_get_assigned_exams_by_assigntype($courseid, $USER->id, BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS);
}

$exams_to_fachlich_release = [];
if ($capabilities["fachlicherpruefer"]) {
    //$exams_fp = block_exaquest_get_assigned_exams_by_assigntype($courseid, $USER->id, BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER);
    //$created_exams = block_exaquest_exams_by_status($courseid, BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
    //// created exams where the user is fp are the exams_to_fachlich_release. The intersection on the field "quizid" is the result.
    //$exams_to_fachlich_release = block_exaquest_array_intersect_field($created_exams, $exams_fp, 'quizid');
    $exams_to_fachlich_release =
        block_exaquest_get_assigned_quizzes_by_assigntype_and_status($USER->id, BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER,
            BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
}

$exams_to_check_grading = [];
$kommissionell_exams_to_check_grading = [];
if ($capabilities["checkexamsgrading"]) {
    $exams_to_check_grading =
        block_exaquest_get_assigned_exams_by_assigntype($courseid, $USER->id, BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHECK_EXAM_GRADING);
    $kommissionell_exams_to_check_grading =
        block_exaquest_get_assigned_exams_by_assigntype($courseid, $USER->id,
            BLOCK_EXAQUEST_QUIZASSIGNTYPE_KOMMISSIONELL_CHECK_EXAM_GRADING);
}

$exams_to_change_grading = [];
if ($capabilities["changeexamsgrading"]) {
    $exams_to_change_grading =
        block_exaquest_get_assigned_exams_by_assigntype($courseid, $USER->id,
            BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHANGE_EXAM_GRADING);
}

$exams_to_grade = [];
if ($capabilities["gradequestion"]) {
    $exams_to_grade =
        block_exaquest_get_assigned_exams_by_assigntype($courseid, $USER->id, BLOCK_EXAQUEST_QUIZASSIGNTYPE_GRADE_EXAM);
}

$dashboard = new \block_exaquest\output\dashboard($USER->id, $courseid, $capabilities, $fragenersteller, $questions_to_create,
    $coursecategoryid, $questioncategoryid, $fachlichepruefer, $exams_to_fill, $exams_to_check_grading, $exams_to_grade,
    $exams_to_change_grading, $exams_to_fachlich_release, $kommissionell_exams_to_check_grading);
echo $output->render($dashboard);

// This is the code for rendering the create-questions-button with moodle-core functions. It is moved to the correct position with javascript.
if (is_enrolled($context, $USER, "block/exaquest:createquestion")) {
    // ADD QUESTION
    echo "<div id='createnewquestion_button'>";
    $questionbank = new \block_exaquest\questionbank_extensions\exaquest_view($contexts, $url, $COURSE, $cm);
    //$categoryandcontext = $pagevars["cat"]; // not needed
    //list($categoryid, $contextid) = explode(',', $categoryandcontext); // not needed
    $coursecategorycontext = \context::instance_by_id($coursecategorycontextid);
    //$category = $questionbank->get_current_category_dashboard($categoryandcontext); // deprecated, we have [$questioncategoryid, $coursecategorycontextid] = get_question_category_and_context_of_course($courseid); already
    $category = helper::get_categories_for_contexts($coursecategorycontextid, 'id', false);
    $category = array_pop($category);
    $canadd = has_capability('moodle/question:add', $coursecategorycontext);
    $questionbank->create_new_question_form_dashboard($category, $canadd);
    echo "</div>";
}

//echo '</div>';
echo $output->footer();
