<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

global $CFG;

use moodle_url;
use question_engine_data_mapper;
use quiz_grading_report;
use renderable;
use renderer_base;
use stdClass;
use templatable;

require_once($CFG->dirroot . '/mod/quiz/report/reportlib.php');
require_once($CFG->dirroot . '/mod/quiz/report/grading/report.php');

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
            //if ($capabilities["skipandreleaseexams"]) {
            //    foreach ($this->new_exams as $new_exam) {
            //        //$new_exam->skipandreleaseexam = $userid == intval($new_exam->creatorid); // why check if owner? This was never said I think. Maybe mixup with skipandrelease questions?
            //        $new_exam->skipandreleaseexam = true;
            //    }
            //}
            if ($capabilities["forcesendexamtoreview"]) {
                foreach ($this->new_exams as $new_exam) {
                    $new_exam->forcesendexamtoreview = true;
                    $new_exam->missingquestionscount = block_exaquest_get_missing_questions_count($new_exam->quizid, $courseid);
                }
            }
            if ($capabilities["addquestiontoexam"]) {
                // TODO: should only PMWs and FPs that have been assigned be able to add questions and view questions? For now everyone can.

                // new exams can only be seen by PK and Mover, except if you are specifically assigned to an exam, e.g. as a FP or PMW
                // ==> give the viewnewexams capability to all users who are assigned to an exam, but filter the newexams according to users role
                $addquestionsassignments = block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid,
                    BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS,
                    BLOCK_EXAQUEST_QUIZSTATUS_NEW);

                // TODO: does this make sense? The FP can "sendexamtoreview" AND simultaneously just simply fachlich review the exam themselves.
                // update the exams that have the addquestionassignment that is not yet finished (done == 0) to have the sendexamtoreview property set to true
                foreach ($addquestionsassignments as $addquestionsassignment) {
                    if ($addquestionsassignment->done == 0) {
                        $this->new_exams[$addquestionsassignment->quizid]->sendexamtoreview = true;
                        $this->new_exams[$addquestionsassignment->quizid]->assignaddquestions = true;
                    }
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
                if ($finished_exams[$exam_to_check_grading->quizid]) {
                    $finished_exams[$exam_to_check_grading->quizid]->assigned_to_check_grading = true;
                }
            }

            $exams_to_kommissionell_check_grading = block_exaquest_get_assigned_exams_by_assigntype($courseid, $userid,
                BLOCK_EXAQUEST_QUIZASSIGNTYPE_KOMMISSIONELL_CHECK_EXAM_GRADING);
            foreach ($exams_to_kommissionell_check_grading as $exam_to_kommissionell_check_grading) {
                if ($finished_exams[$exam_to_kommissionell_check_grading->quizid]) {
                    $finished_exams[$exam_to_kommissionell_check_grading->quizid]->assigned_to_kommissionell_check_grading = true;
                }
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

            if ($capabilities["checkgradingforfp"]) {
                foreach ($finished_exams as $finished_exam) {
                    // check if the FP is assigned to check grading. Only then, the PK should have the possibility to check grading FOR the FP
                    $exams_to_check_grading_for_fp = block_exaquest_get_assigned_exams_by_assigntype($courseid,
                        block_exaquest_get_assigned_fachlicherpruefer($finished_exam->quizid)->assigneeid,
                        BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHECK_EXAM_GRADING);
                    // if the $exams_to_check_grading_for_fp array holds an element with the field "quizid" which is the same as finished_exam->quizid, then the PK is assigned to check the grading for this FP
                    foreach ($exams_to_check_grading_for_fp as $exam_to_check_grading_for_fp) {
                        if ($exam_to_check_grading_for_fp->quizid == $finished_exam->quizid) {
                            $finished_exam->fp_assigned_to_check_grading = true;
                        }
                    }
                }
            }

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

        // TODO: always show the buttons? Or only when you were assigned for example?
        // --> it only makes sense to also show it to PK and StudMA / just anyone who can assign to check grading or to grade
        //$data->go_to_exam_report_overview = new moodle_url('/mod/quiz/report.php',
        //        array('mode' => 'overview',));
        $data->go_to_exam_report_overview = new moodle_url('/blocks/exaquest/report.php',
            array('mode' => 'exaqueststatistics', 'courseid' => $this->courseid));
        $data->go_to_exam_report_overview =
            $data->go_to_exam_report_overview->raw_out(false); // otherwise the &amp; is not converted to &
        $data->go_to_exam_report_grading = new moodle_url('/mod/quiz/report.php',
            array('mode' => 'grading',));

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

        // add popup_assign_gradeexam to every finished exam that has ungraded questions:
        // check if ungraded questions exist
        foreach ($data->finished_exams as $finished_exam) {
            $quiz = new stdClass();
            $quiz->id = $finished_exam->quizid;

            // get the information if there are ungraded questions by own query:
            $ungraded_questions_count = block_exaquest_get_ungraded_questions_count($quiz);
            if ($ungraded_questions_count > 0) {
                $popup = new popup_assign_gradeexam($this->courseid, $finished_exam->quizid);
                $finished_exam->popup_assign_gradeexam = $popup->export_for_template($output);
            } else {
                // add popup_assign_check_exam_grading to every finished exam that is graded:
                $popup = new popup_assign_check_exam_grading($this->courseid, $finished_exam->quizid);
                $finished_exam->popup_assign_check_exam_grading = $popup->export_for_template($output);

                // add popup_assign_check_exam_grading to every finished exam that is graded:
                $popup = new popup_assign_kommissionell_check_exam_grading($this->courseid, $finished_exam->quizid);
                $finished_exam->popup_assign_kommissionell_check_exam_grading = $popup->export_for_template($output);
            }

            // add popup_assign_change_exam_grading to every finished exam:
            $popup = new popup_assign_change_exam_grading($finished_exam->quizid);
            $finished_exam->popup_assign_change_exam_grading = $popup->export_for_template($output);

            // show the same as in dashboard, but not for every exam and student combination, but instead of every THIS exam and student combination
            // more performant solution possible... for now, get the exams like in dashboard
            if ($this->capabilities["checkexamsgrading"]) {
                $kommissionell_exams_to_check_grading =
                    block_exaquest_get_assigned_exams_by_assigntype($this->courseid, $this->userid,
                        BLOCK_EXAQUEST_QUIZASSIGNTYPE_KOMMISSIONELL_CHECK_EXAM_GRADING);
            }
            // filter for the current exam
            if ($kommissionell_exams_to_check_grading) {
                $kommissionell_exams_to_check_grading = array_filter($kommissionell_exams_to_check_grading,
                    function($exam) use ($finished_exam) {
                        return $exam->quizid == $finished_exam->quizid;
                    });
                $popup = new popup_kommissionell_exams_for_me_to_check_grading($kommissionell_exams_to_check_grading, true);
                $finished_exam->popup_kommissionell_exams_for_me_to_check_grading = $popup->export_for_template($output);
            }
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
