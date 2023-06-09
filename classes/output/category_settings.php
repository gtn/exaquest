<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;
use moodle_url;

require_once(__DIR__ . '/popup_edit_category.php');
class category_settings implements renderable, templatable {
    var $questions = null;
    private $capabilities;
    private $courseid;
    private $userid;
    /**
     * @var popup_request_questions
     */
    private $request_questions_popup;

    public function __construct($userid, $courseid, $capabilities) {
        global $DB, $COURSE;

        $this->courseid = $courseid;
        $this->capabilities = $capabilities;
        $this->userid = $userid;
        $records = $DB->get_records("block_exaquestcategories", array("coursecategoryid" => $COURSE->category), 'categoryname');
        $categories = array();
        foreach($records as $key => $record){
                $categories[$record->categorytype][] = $record;
        }
        $this->categories = $categories;
        $this->popup_edit_category = new popup_edit_category( 'revise_question', "asdfdas", "2");
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE;
        $output = $PAGE->get_renderer('block_exaquest');
        $data = new stdClass();
        $data->capabilities = $this->capabilities;
        $data->fragencharakter = $this->categories[0];
        $data->klassifikation = $this->categories[1];
        $data->fragefach = $this->categories[2];
        $data->lehrinhalt = $this->categories[3];
        $data->action = $PAGE->url->out(false, array('action' => 'submit', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->popup_edit_category =  new popup_edit_category( 'revise_question', "asdfdas", "2");
        $data->name = "Edit";
        $data->edit = $PAGE->url->out(false, array('action' => 'edit', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->add = $PAGE->url->out(false, array('action' => 'add', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));


        return $data;
    }
}
