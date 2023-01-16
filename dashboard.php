<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $PAGE, $OUTPUT, $USER;

require_once($CFG->dirroot . '/question/editlib.php');
require_once(__DIR__ . '/questionbank_extensions/exaquest_view.php');

$courseid = required_param('courseid', PARAM_INT);
require_login($courseid);

//$course = $DB->get_record('course', array('id' => $courseid));
$context = context_course::instance($courseid);

$coursecategoryid = block_exaquest_get_coursecontextid_by_courseid($courseid);


    if (is_enrolled($context, $USER, "block/exaquest:createquestion")) {
    list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) =
        question_edit_setup('questions', '/question/edit.php');
}

$page_params = array('courseid' => $courseid);

$url = new moodle_url('/blocks/exaquest/dashboard.php', $page_params);
$PAGE->set_url($url);
$PAGE->set_heading(get_string('dashboard', 'block_exaquest'));
$PAGE->set_title(get_string('dashboard', 'block_exaquest'));

block_exaquest_init_js_css();

$output = $PAGE->get_renderer('block_exaquest');

echo $output->header($context, $courseid, get_string('dashboard', 'block_exaquest'));
$frageneersteller = array();
$action = optional_param('action', "", PARAM_ALPHAEXT);
if ($action == 'request_questions') {
    // get all the users with role "fragesteller" and send them a notification
    $allfragenersteller = block_exaquest_get_fragenersteller_by_courseid($courseid); // TODO by courseid or coursecategoryid?
    if (array_key_exists("selectedusers", $_POST)) {
        if(is_array($_POST["selectedusers"])){
            $selectedfragenersteller = clean_param_array($_POST["selectedusers"],PARAM_INT);
        }else{
            $selectedfragenersteller = clean_param($_POST["selectedusers"],PARAM_INT);
        }
        $requestcomment = clean_param($_POST["requestcomment"],PARAM_TEXT);

        if ($selectedfragenersteller) {
            $frageneersteller = array_intersect_key($allfragenersteller, $selectedfragenersteller);
            foreach ($frageneersteller as $ersteller) {
                block_exaquest_create_questionrequest($USER->id, $ersteller->id, $requestcomment);
            }
        }
    }
}

// RENDER:
$capabilities = [];
$capabilities["createquestions"] = is_enrolled($context, $USER, "block/exaquest:createquestion");
$capabilities["modulverantwortlicher"] = is_enrolled($context, $USER, "block/exaquest:modulverantwortlicher");
$capabilities["fragenersteller"] = is_enrolled($context, $USER, "block/exaquest:fragenersteller");
$capabilities["fachlfragenreviewer"] = is_enrolled($context, $USER, "block/exaquest:fachlfragenreviewer");
$capabilities["pruefungskoordination"] = is_enrolled($context, $USER, "block/exaquest:pruefungskoordination");

// there is no logic in mustache ==> do it here. Often roles overlap.
$capabilities["fragenersteller_or_fachlfragenreviewer"] = $capabilities["fragenersteller"] || $capabilities["fachlfragenreviewer"];
$capabilities["modulverantwortlicher_or_pruefungskoordination"] = $capabilities["modulverantwortlicher"] || $capabilities["pruefungskoordination"];

if ($capabilities["modulverantwortlicher"] || $capabilities["pruefungskoordination"]) {
    if (!isset($frageneersteller) || empty($data->fragenersteller)) {
        $frageneersteller = block_exaquest_get_fragenersteller_by_courseid($courseid); // TODO: coursecategoryid?
    }
}

$questions_to_create = [];
if ($capabilities["fragenersteller"]) {
    $questions_to_create = block_exaquest_get_questions_for_me_to_create($courseid, $USER->id); // TODO: coursecategoryid?
}



$dashboard = new \block_exaquest\output\dashboard($USER->id, $courseid, $capabilities, $frageneersteller, $questions_to_create, $coursecategoryid);
echo $output->render($dashboard);

// This is the code for rendering the create-questions-button with moodle-core functions. It is moved to the correct position with javascript.
if (is_enrolled($context, $USER, "block/exaquest:createquestion")) {
    // ADD QUESTION
    echo "<div id='createnewquestion_button'>";
    $questionbank = new core_question\local\bank\exaquest_view($contexts, $url, $COURSE, $cm);
    $categoryandcontext = $pagevars["cat"];
    list($categoryid, $contextid) = explode(',', $categoryandcontext);
    $catcontext = \context::instance_by_id($contextid);
    $category = $questionbank->get_current_category_dashboard($categoryandcontext);
    $canadd = has_capability('moodle/question:add', $catcontext);
    $questionbank->create_new_question_form_dashboard($category, $canadd);
    echo "</div>";
}

//echo '</div>';
echo $output->footer();
