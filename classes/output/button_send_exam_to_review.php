<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class button_send_exam_to_review implements renderable, templatable {

    public function __construct($quizid, $courseid) {
        $this->quizid = $quizid;
        $this->courseid = $courseid;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $data = new stdClass();
        $data->quizid = $this->quizid;
        $data->courseid = $this->courseid;
        return $data;
    }

}
