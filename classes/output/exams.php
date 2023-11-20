<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class exams implements renderable, templatable {
    var $questions = null;
    private $capabilities;
    private $courseid;
    private $userid;

    public function __construct($userid, $courseid, $capabilities) {
        global $DB, $COURSE;

        $this->new_exams = [];
        $this->created_exams = [];
        $this->fachlich_released_exams = [];
        $this->formal_released_exams = [];
        $this->active_exams = [];
        $this->finished_exams = [];
        $this->grading_released_exams = [];

        $this->courseid = $courseid;
        //$this->coursecategoryid = block_exaquest_get_coursecategoryid_by_courseid($courseid); // coursecategoryid does not make sense here: use courseid instead
        $this->capabilities = $capabilities;
        $this->userid = $userid;
        //$this->exams = $DB->get_records("quiz", array("course" => $COURSE->id));
        $this->capabilities["createnewexam"] = has_capability('mod/quiz:addinstance', \context_course::instance($COURSE->id));
        if ($capabilities["viewnewexamscard"]) {
            $this->new_exams = block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_NEW);
            if ($capabilities["skipandreleaseexams"]) {
                foreach ($this->new_exams as $new_exam) {
                    $new_exam->skipandreleaseexam = $userid == intval($new_exam->creatorid);
                }
            }
            if ($capabilities["addquestiontoexam"]) {
                // TODO: should only PMWs and FPs that have been assigned be able to add questions and view questions? For now everyone can.

                // new exams can only be seen by PK and Mover, except if you are specifically assigned to an exam, e.g. as a FP or PMW
                // ==> give the viewnewexams capability to all users who are assigned to an exam, but filter the newexams according to users role
                $addquestionsassignments = block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid,
                        BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS,
                        BLOCK_EXAQUEST_QUIZSTATUS_NEW);

                // update the exams that have the addquestionassignment to have the sendexamtoreview property set to true
                foreach ($addquestionsassignments as $addquestionsassignment) {
                    $this->new_exams[$addquestionsassignment->quizid]->sendexamtoreview = true;
                    $this->new_exams[$addquestionsassignment->quizid]->assignaddquestions = true;
                }
                $fpexams = block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid,
                        BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER, BLOCK_EXAQUEST_QUIZSTATUS_NEW);
                // these are the exams for this FP ==> add the button for fachlich releasing
                foreach ($fpexams as $fpexam) {
                    $this->new_exams[$fpexam->quizid]->fachlichreleaseexam = true;
                    $this->new_exams[$fpexam->quizid]->assignaddquestions = true;
                    // add info if questions are missing
                    $this->new_exams[$fpexam->quizid]->missingquestionscount =
                            block_exaquest_get_missing_questions_count($fpexam->quizid, $courseid);
                    // this is not perfectly performant. It queries a few things that get queried again if the popup_assign_addquestions is also created for this exam
                    // this happens rarely though
                }
            }
        }

        if ($capabilities["viewcreatedexamscard"]) {
            $this->created_exams = block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
            $fpexams = block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid,
                    BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER, BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
            // these are the exams for this FP ==> add the button for fachlich releasing
            foreach ($fpexams as $fpexam) {
                $this->created_exams[$fpexam->quizid]->fachlichreleaseexam = true;
                // add info if questions are missing
                $this->created_exams[$fpexam->quizid]->missingquestionscount =
                        block_exaquest_get_missing_questions_count($fpexam->quizid, $courseid);
                // this is not perfectly performant. It queries a few things that get queried again if the popup_assign_addquestions is also created for this exam
                // this happens rarely though
            }
        }

        $this->fachlich_released_exams =
                block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED);
        //$this->formal_released_exams =
        //    block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_FORMAL_RELEASED);
        $this->active_exams = block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE);

        if ($capabilities["viewfinishedexamscard"]) {
            $finished_exams = block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_FINISHED);
            $exams_to_check_grading = block_exaquest_get_assigned_exams_by_assigntype($courseid, $userid,
                    BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHECK_EXAM_GRADING);
            foreach ($exams_to_check_grading as $exam_to_check_grading) {
                $finished_exams[$exam_to_check_grading->quizid]->assigned_to_check_grading = true;
            }

            // If exams_to_grade and exams_to_check_grading overlap it does not really make sense, but still it should be covered:
            $exams_to_grade =
                    block_exaquest_get_assigned_exams_by_assigntype($courseid, $userid, BLOCK_EXAQUEST_QUIZASSIGNTYPE_GRADE_EXAM);
            foreach ($exams_to_grade as $exam_to_grade) {
                $finished_exams[$exam_to_grade->quizid]->assigned_to_grade = true;
            }

            //$exams_to_change_grading =
            //        block_exaquest_get_assigned_exams_by_assigntype($courseid, $userid, BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHANGE_EXAM_GRADING);
            //foreach ($exams_to_change_grading as $exam_to_change_grading) {
            //    $finished_exams[$exam_to_change_grading->quizid]->assigned_to_change_grading = true;
            //}

            $this->finished_exams = $finished_exams;
        }

        if ($capabilities["viewgradesreleasedexamscard"]) {
            $this->grading_released_exams =
                    block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED);
        }

        $this->add_link_to_quiz($this->created_exams);
        $this->add_link_to_quiz($this->fachlich_released_exams);
        $this->add_link_to_quiz($this->formal_released_exams);
        $this->add_link_to_quiz($this->active_exams);
        $this->add_link_to_quiz($this->finished_exams);
        $this->add_link_to_quiz($this->grading_released_exams);

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
                array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1]));
        $data->go_to_exam_view = new moodle_url('/blocks/exaquest/finished_exam_questionbank.php',
                array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1]));
        $data->go_to_exam_questionbank = $data->go_to_exam_questionbank->raw_out(false);
        if ($this->new_exams) {
            $data->new_exams = array_values($this->new_exams);
        } else {
            $data->new_exams = array();
        }
        // add the assignment popups for every new_exam:
        foreach ($data->new_exams as $new_exam) {
            $popup = new popup_assign_addquestions($this->courseid, $new_exam->quizid);
            $new_exam->popup_assign_addquestions = $popup->export_for_template($output);
            $new_exam->link_to_exam =
                    new moodle_url('/course/modedit.php', array('update' => $new_exam->coursemoduleid, 'return' => '1'));
            $new_exam->link_to_exam = $new_exam->link_to_exam->raw_out(false);
        }

        $data->created_exams = array_values($this->created_exams);
        $data->fachlich_released_exams = array_values($this->fachlich_released_exams);
        //$data->formal_released_exams = array_values($this->formal_released_exams);
        $data->active_exams = array_values($this->active_exams);
        $data->finished_exams = array_values($this->finished_exams);
        $data->grading_released_exams = array_values($this->grading_released_exams);

        $data->create_exam_link = new moodle_url('/course/modedit.php',
                array('add' => 'quiz', 'course' => $COURSE->id, 'section' => 0, 'return' => 0, 'sr' => 0));
        $data->create_exam_link = $data->create_exam_link->raw_out(false);

        $data->courseid = $this->courseid;

        // add popup_assign_gradeexam to every finished exam:
        foreach ($data->finished_exams as $finished_exam) {
            $popup = new popup_assign_gradeexam($this->courseid, $finished_exam->quizid);
            $finished_exam->popup_assign_gradeexam = $popup->export_for_template($output);
        }

        // add popup_assign_check_exam_grading to every finished exam:
        foreach ($data->finished_exams as $finished_exam) {
            $popup = new popup_assign_check_exam_grading($this->courseid, $finished_exam->quizid);
            $finished_exam->popup_assign_check_exam_grading = $popup->export_for_template($output);
        }

        // add popup_assign_check_exam_grading to every finished exam:
        foreach ($data->finished_exams as $finished_exam) {
            $popup = new popup_assign_change_exam_grading($finished_exam->quizid);
            $finished_exam->popup_assign_change_exam_grading = $popup->export_for_template($output);
        }

        return $data;
    }

    private function add_link_to_quiz($exams) {
        foreach ($exams as $exam) {
            $exam->link_to_exam =
                    new moodle_url('/course/modedit.php', array('update' => $exam->coursemoduleid, 'return' => '1'));
            $exam->link_to_exam = $exam->link_to_exam->raw_out(false);
        }
    }

}
