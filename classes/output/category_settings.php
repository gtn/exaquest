<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;
use moodle_url;

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
                $categories[$record->categorytype][] = $record->categoryname . "\n";
        }
        $this->categories = $categories;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE;
        $data = new stdClass();
        $data->capabilities = $this->capabilities;
        $data->fragencharakter = $this->categories[0];
        $data->klassifikation = $this->categories[1];
        $data->fragefach = $this->categories[2];
        $data->lehrinhalt = $this->categories[3];
        $data->action = $PAGE->url->out(false, array('action' => 'submit', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));


        return $data;
    }
}
