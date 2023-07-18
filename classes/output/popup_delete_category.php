<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use phpDocumentor\Reflection\Types\Self_;
use renderable;
use renderer_base;
use templatable;
use stdClass;

global $CFG;
require_once($CFG->dirroot . '/blocks/exaquest/classes/form/autofill_helper_form.php');

class popup_delete_category implements renderable, templatable {
    var $selectusers = null;
    var $name = null;
    var $questionbankentryid = null;
    var $action = null;

    public function __construct($categoryid) {
        $this->id = $categoryid;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE;
        $data = new stdClass();
        $data->id = $this->id;
        $data->action = $PAGE->url->out(false, array('action' => 'delete', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));

        $data->sesskey = sesskey();
        return $data;
    }
}