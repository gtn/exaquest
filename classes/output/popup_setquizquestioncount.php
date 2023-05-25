<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

global $CFG;

class popup_setquizquestioncount implements renderable, templatable {
    public function __construct($courseid) {
        $this->fragefaecher = block_exaquest_get_fragefaecher_by_courseid($courseid);
    }

    /**
     * Export this data, so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE, $CFG, $OUTPUT;
        $data = new stdClass();

        $data->fragefaecher = $this->fragefaecher;
        $data->action =
            $PAGE->url->out(false, array('action' => 'assign_quiz_addquestions', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->sesskey = sesskey();
        return $data;
    }
}



