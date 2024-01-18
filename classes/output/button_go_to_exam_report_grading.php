<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

// not needed since mustache is directly used in other mustache, no specific object is created in php code
//class button_go_to_exam_report_overview implements renderable, templatable {
//
//    public function __construct($quizid, $courseid, $catAndCont) {
//        // goes here when new \block_exaquest\output\button_go_to_exam_view(); is called. NOT when the mustache is used inside of another mustache, e.g. exam.mustache
//        $this->quizid = $quizid;
//        $this->courseid = $courseid;
//        $this->catAndCont = $catAndCont;
//    }
//
//    /**
//     * Export this data so it can be used as the context for a mustache template.
//     *
//     * @return stdClass
//     */
//    public function export_for_template(renderer_base $output) {
//        $data = new stdClass();
//        $data->go_to_exam_report_overview = new moodle_url('/blocks/exaquest/finished_exam_questionbank.php',
//                array('courseid' => $this->courseid, "category" => $this->catAndCont[0] . ',' . $this->catAndCont[1]));
//        $data->quizid = $this->quizid;
//        return $data;
//    }
//
//}
