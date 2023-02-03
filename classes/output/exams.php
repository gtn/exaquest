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
        $this->coursecategoryid = block_exaquest_get_coursecategoryid_by_courseid($courseid);
        $this->capabilities = $capabilities;
        $this->userid = $userid;
        //$this->exams = $DB->get_records("quiz", array("course" => $COURSE->id));
        $this->new_exams = block_exaquest_exams_by_status($this->coursecategoryid,BLOCK_EXAQUEST_QUIZSTATUS_NEW);
        $this->created_exams = block_exaquest_exams_by_status($this->coursecategoryid,BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
        $this->fachlich_released_exams = block_exaquest_exams_by_status($this->coursecategoryid,BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED);
        $this->teachnisch_released_exams = block_exaquest_exams_by_status($this->coursecategoryid,BLOCK_EXAQUEST_QUIZSTATUS_TECHNISCH_RELEASED);
        $this->active_exams = block_exaquest_exams_by_status($this->coursecategoryid,BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE);
        $this->finished_exams = block_exaquest_exams_by_status($this->coursecategoryid,BLOCK_EXAQUEST_QUIZSTATUS_FINISHED);
        $this->grading_released_exams = block_exaquest_exams_by_status($this->coursecategoryid,BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED);

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
        $data->new_exams = array_values($this->new_exams);
        $data->created_exams = array_values($this->created_exams); // TODO rw: test if they are shown with current mustache (no way to create them in moodle yet --> create one manually)
        $data->fachlich_released_exams = array_values($this->fachlich_released_exams);
        $data->teachnisch_released_exams = array_values($this->teachnisch_released_exams);
        $data->active_exams = array_values($this->active_exams);
        $data->finished_exams = array_values($this->finished_exams);
        $data->grading_released_exams = array_values($this->grading_released_exams);
        return $data;
    }
}