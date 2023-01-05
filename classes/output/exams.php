<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;
use moodle_url;

class exams implements renderable, templatable {
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
        $this->exams = $DB->get_records("quiz", array("course" => $COURSE->id));
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
        $data->action =
            $PAGE->url->out(false, array('action' => 'create', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));

        $catAndCont = get_question_category_and_context_of_course();

        $data->go_to_exam_questionbank = new moodle_url('/blocks/exaquest/exam_questionbank.php',
            array('courseid' => $this->courseid,"category" => $catAndCont[0].','. $catAndCont[1]));
        $data->go_to_exam_questionbank = $data->go_to_exam_questionbank->raw_out(false);

        $data->exams = array_values($this->exams);

        return $data;
    }
}