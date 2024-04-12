<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;

global $CFG;

class popup_setquizquestioncount implements renderable, templatable {
    public function __construct($courseid, $quizid) {
        $this->fragefaecher = block_exaquest_get_fragefaecher_by_courseid_and_quizid($courseid, $quizid);
        $this->quizid = $quizid;
    }

    /**
     * Export this data, so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE, $CFG, $OUTPUT;
        $data = new stdClass();
        $data->fragefaecher = array_values($this->fragefaecher);
        $data->action =
            $PAGE->url->out(false, array('action' => 'set_questioncount_per_quiz_and_fragefach', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->sesskey = sesskey();
        $data->quizid = $this->quizid;
        return $data;
    }
}



