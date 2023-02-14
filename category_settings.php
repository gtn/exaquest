<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $PAGE, $OUTPUT, $USER;

require_once($CFG->dirroot . '/question/editlib.php');
require_once(__DIR__ . '/questionbank_extensions/exaquest_view.php');

$courseid = required_param('courseid', PARAM_INT);
$action = optional_param('action', "", PARAM_ALPHAEXT);
$fragencharakter  = optional_param('fragencharakter', null, PARAM_TEXT);
$klassifikation  = optional_param('klassifikation', null, PARAM_TEXT);
$fragefach  = optional_param('fragefach', null, PARAM_TEXT);
$lehrinhalt  = optional_param('lehrinhalt', null, PARAM_TEXT);
require_login($courseid);


//$course = $DB->get_record('course', array('id' => $courseid));
$context = context_course::instance($courseid);


$page_params = array('courseid' => $courseid);

$url = new moodle_url('/blocks/exaquest/category_settings.php', $page_params);
$PAGE->set_url($url);
$PAGE->set_heading(get_string('category_settings', 'block_exaquest'));
$PAGE->set_title(get_string('category_settings', 'block_exaquest'));

block_exaquest_init_js_css();

$output = $PAGE->get_renderer('block_exaquest');

echo $output->header($context, $courseid, get_string('exams_overview', 'block_exaquest'));

if( $action == "submit"){
    $check = $DB->get_fieldset_select("block_exaquestcategories", "categoryname", 'coursecategoryid = :coursecategoryid',
        ['coursecategoryid' => $COURSE->category]);
    if($fragencharakter != null){
        $fragencharakterarray = explode(PHP_EOL, $fragencharakter);
        if(end($fragencharakterarray) == ""){
            array_pop($fragencharakterarray);
        }
        foreach($fragencharakterarray as $fragencharakter){
            if(! $DB->record_exists("block_exaquestcategories", array("coursecategoryid" => $COURSE->category, "categoryname" => $fragencharakter, "categorytype"=> 0))) {
                $DB->insert_record("block_exaquestcategories", array("coursecategoryid" => $COURSE->category, "categoryname" => $fragencharakter, "categorytype" => 0));
            }
        }
    }
    if($klassifikation != null){
        $klassifikationarray = explode(PHP_EOL, $klassifikation);
        if(end($klassifikationarray) == ""){
            array_pop($klassifikationarray);
        }
        foreach($klassifikationarray as $klassifikation){
            if(! $DB->record_exists("block_exaquestcategories", array("coursecategoryid" => $COURSE->category, "categoryname" => $klassifikation, "categorytype"=> 1))){
                $DB->insert_record("block_exaquestcategories", array("coursecategoryid" => $COURSE->category, "categoryname" => $klassifikation, "categorytype"=> 1));
            }
        }
    }
    if($fragefach != null){
        $fragefacharray = explode(PHP_EOL, $fragefach);
        if(end($fragefacharray) == ""){
            array_pop($fragefacharray);
        }
        foreach($fragefacharray as $fragefach) {
            if (!$DB->record_exists("block_exaquestcategories", array("coursecategoryid" => $COURSE->category, "categoryname" => $fragefach, "categorytype" => 2))) {
                $DB->insert_record("block_exaquestcategories", array("coursecategoryid" => $COURSE->category, "categoryname" => $fragefach, "categorytype" => 2));
            }
        }
    }
    if($lehrinhalt != null){
        $lehrinhaltarray = explode(PHP_EOL, $lehrinhalt);
        if(end($lehrinhaltarray) == ""){
            array_pop($lehrinhaltarray);
        }
        foreach($lehrinhaltarray as $lehrinhalt) {
            if (!$DB->record_exists("block_exaquestcategories", array("coursecategoryid" => $COURSE->category, "categoryname" => $lehrinhalt, "categorytype" => 3))) {
                $DB->insert_record("block_exaquestcategories", array("coursecategoryid" => $COURSE->category, "categoryname" => $lehrinhalt, "categorytype" => 3));
            }
        }
    }
    $newcontent = array_merge($fragencharakterarray, $klassifikationarray, $fragefacharray, $lehrinhaltarray);

    foreach($check as $cat){
        if(!in_array($cat, $newcontent)){
            $DB->delete_records("block_exaquestcategories", array("categoryname" => $cat));
        }
    }
}


// RENDER:
$capabilities = [];
$capabilities["createquestions"] = is_enrolled($context, $USER, "block/exaquest:createquestion");

$exams = new \block_exaquest\output\category_settings($USER->id, $courseid, $capabilities);
echo $output->render($exams);

//echo '</div>';
echo $output->footer();
