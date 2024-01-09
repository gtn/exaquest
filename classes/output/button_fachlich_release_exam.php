<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class button_fachlich_release_exam implements renderable, templatable {

    public function __construct($quizid, $courseid, $missingquestionscount) {
        $this->quizid = $quizid;
        $this->courseid = $courseid;
        $this->missingquestionscount = $missingquestionscount;
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
        $data->missingquestionscount = $this->missingquestionscount;
        return $data;
    }

}
