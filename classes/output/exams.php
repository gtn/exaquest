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

        $this->courseid = $courseid;
        //$this->coursecategoryid = block_exaquest_get_coursecategoryid_by_courseid($courseid); // coursecategoryid does not make sense here: use courseid instead
        $this->capabilities = $capabilities;
        $this->userid = $userid;
        //$this->exams = $DB->get_records("quiz", array("course" => $COURSE->id));
        $this->capabilities["createnewexam"] = has_capability('mod/quiz:addinstance', \context_course::instance($COURSE->id));
        if ($capabilities["viewnewexams"]) {
            $this->new_exams = block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_NEW);
            foreach ($this->new_exams as $new_exam) {
                $new_exam->skipandreleaseexam = $userid == intval($new_exam->creatorid);
            }
        } else if ($capabilities["addquestiontoexam"]) {
            // new exams can only be seen by PK and Mover, except if you are specifically assigned to an exam, e.g. as a FP or PMW
            // ==> give the viewnewexams capability to all users who are assigned to an exam, but filter the newexams according to users role
            $this->new_exams =
                block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid, BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS,
                    BLOCK_EXAQUEST_QUIZSTATUS_NEW);
            // add the exams that have been assigned to the FP when creating the exam ( BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER)
            $this->new_exams = array_merge($this->new_exams, block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid,
                BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER, BLOCK_EXAQUEST_QUIZSTATUS_NEW));
            // $this->new_exams is now an array filled with sdtClass objects
            // filter new_exams so that every quizid is only once in the array (quizid is a property of the objects)
            // it could otherwise happen, that a quiz is shown twice, because e.g. the fp is assigned as fp and also for adding questions
            $this->new_exams = array_unique($this->new_exams, SORT_REGULAR);
            if ($this->new_exams) {
                $this->capabilities["viewnewexams"] = true;
            }
        }
        if ($capabilities["viewcreatedexams"]) {
            $this->created_exams = block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
        } else {
            $this->created_exams = block_exaquest_get_assigned_quizzes_by_assigntype_and_status($userid,
                BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER,
                BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
            if ($this->created_exams) {
                $this->capabilities["viewcreatedexams"] = true;
            }

        }
        $this->created_exams = block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_CREATED);
        $this->fachlich_released_exams =
            block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED);
        //$this->formal_released_exams =
        //    block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_FORMAL_RELEASED);
        $this->active_exams = block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_ACTIVE);
        $this->finished_exams = block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_FINISHED);
        $this->grading_released_exams =
            block_exaquest_exams_by_status($this->courseid, BLOCK_EXAQUEST_QUIZSTATUS_GRADING_RELEASED);

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
        $data->got_to_exam_view = new moodle_url('/blocks/exaquest/finished_exam_questionbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1]));
        $data->go_to_exam_questionbank = $data->go_to_exam_questionbank->raw_out(false);
        if($this->new_exams){
            $data->new_exams = array_values($this->new_exams);
        }else{
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

        $data->created_exams =
            array_values($this->created_exams); // TODO rw: test if they are shown with current mustache (no way to create them in moodle yet --> create one manually)
        $data->fachlich_released_exams = array_values($this->fachlich_released_exams);
        //$data->formal_released_exams = array_values($this->formal_released_exams);
        $data->active_exams = array_values($this->active_exams);
        $data->finished_exams = array_values($this->finished_exams);
        $data->grading_released_exams = array_values($this->grading_released_exams);

        $data->create_exam_link = new moodle_url('/course/modedit.php',
            array('add' => 'quiz', 'course' => $COURSE->id, 'section' => 0, 'return' => 0, 'sr' => 0));
        $data->create_exam_link = $data->create_exam_link->raw_out(false);

        //if ($this->capabilities["releasequestion"]) {
        //    $data->request_exams_popup = $this->popup_assign_addquestions->export_for_template($output);
        //}
        //$data->popup_assign_addquestions = $this->popup_assign_addquestions->export_for_template($output);

        foreach ($data->new_exams as $new_exam) {
            $popup = new popup_assign_addquestions($this->courseid, $new_exam->quizid);
            $new_exam->popup_assign_addquestions = $popup->export_for_template($output);
            //$popup = new popup_setquizquestioncount($this->courseid, $new_exam->quizid);
            //$new_exam->popup_setquizquestioncount = $popup->export_for_template($output);
        }

        $data->courseid = $this->courseid;


        // TODO: later, now the check_exam_grading is more important
//        // add popup_assign_gradeexam to every finished exam:
//        foreach ($data->finished_exams as $finished_exam) {
//            $popup = new popup_assign_gradeexam($this->courseid, $finished_exam->quizid);
//            $finished_exam->popup_assign_gradeexam = $popup->export_for_template($output);
//        }


        // add popup_assign_check_exam_grading to every finished exam:
        foreach ($data->finished_exams as $finished_exam) {
            $popup = new popup_assign_check_exam_grading($this->courseid, $finished_exam->quizid);
            $finished_exam->popup_assign_check_exam_grading = $popup->export_for_template($output);
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
