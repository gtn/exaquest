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
$editedName = optional_param('editedname', null, PARAM_TEXT);
$editedNameId = optional_param('editednameid', null, PARAM_INT);
$categorytype = optional_param('categorytype', null, PARAM_INT);
$addcategory = optional_param('addcategory', null, PARAM_TEXT);
$deleteid = optional_param('deleteid', null, PARAM_INT);


require_login($courseid);
require_capability('block/exaquest:viewexamstab', context_course::instance($courseid));

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

if( $action == "edit"){
    $currcat = $DB->get_records("block_exaquestcategories", array("coursecategoryid" => $COURSE->category, "deleted" => 0));
    foreach($currcat as $cat){
        if(strcmp($editedName[intval($cat->id)], $cat->categoryname)){
            $obj = new stdClass();
            $obj->categoryname = $editedName[$cat->id];
            $obj->id = $cat->id;
            $DB->update_record("block_exaquestcategories", $obj);
            break;
        }
    }
}


if( $action == "delete"){
    $obj = new stdClass();
    $obj->deleted = 1;
    $obj->id = $deleteid;
    $DB->update_record("block_exaquestcategories", $obj);

    $cat = get_question_category_and_context_of_course($courseid)[0];

    $questions = $DB->get_records_sql('SELECT cfd.id, qbe.id AS qbeid, cfd.value
                                               FROM {question_versions} qv
                                               JOIN {question} q ON qv.questionid = q.id
                                               JOIN {customfield_data} cfd ON q.id = cfd.instanceid
                                               JOIN {question_bank_entries} qbe ON qv.questionbankentryid = qbe.id
                                               WHERE qbe.questioncategoryid = '.$cat.' AND qv.version =
                                                                                                (SELECT MAX(v.version)
                                                                                                FROM {question_versions} v
                                                                                                JOIN {question_bank_entries} be ON be.id = v.questionbankentryid
                                                                                              WHERE be.id = qbe.id)');
    foreach($questions as $question){
        if (in_array(strval($deleteid), explode(',',$question->value))){
            $id = $DB->get_field("block_exaquestquestionstatus","id", array("questionbankentryid" => $question->qbeid));
            $obj = new stdClass();
            $obj->status = BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED;
            $obj->id = $id;
            $DB->update_record("block_exaquestquestionstatus",$obj);
        }
    }

}

if( $action == "add") {
    if(! $DB->record_exists("block_exaquestcategories", array("coursecategoryid" => $COURSE->category, "categoryname" => $addcategory, "categorytype"=> $categorytype))) {
        $DB->insert_record("block_exaquestcategories", array("coursecategoryid" => $COURSE->category, "categoryname" => $addcategory, "categorytype" => $categorytype));
    }
}


// RENDER:
$capabilities = [];
$capabilities["createquestion"] = is_enrolled($context, $USER, "block/exaquest:createquestion");

$exams = new \block_exaquest\output\category_settings($USER->id, $courseid, $capabilities);
echo $output->render($exams);

//echo '</div>';
echo $output->footer();
