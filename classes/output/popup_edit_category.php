<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use phpDocumentor\Reflection\Types\Self_;
use renderable;
use renderer_base;
use stdClass;
use templatable;

global $CFG;
require_once($CFG->dirroot . '/blocks/exaquest/classes/form/autofill_helper_form.php');

class popup_edit_category implements renderable, templatable {
    var $selectusers = null;
    var $name = null;
    var $questionbankentryid = null;
    var $action = null;

    public function __construct($categoryid, $categoryname) {
        $this->id = $categoryid;
        $this->categoryname = $categoryname;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE;
        $data = new stdClass();
        $data->name = $this->name;
        $data->id = $this->id;
        $data->categoryname = $this->categoryname;
        $data->edit = $PAGE->url->out(false, array('action' => 'edit', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));


        $data->action = $this->action;
        $data->sesskey = sesskey();
        return $data;
    }
}
