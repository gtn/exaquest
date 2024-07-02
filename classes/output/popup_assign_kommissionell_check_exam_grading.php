<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;

global $CFG;
require_once($CFG->dirroot . '/blocks/exaquest/classes/form/autofill_helper_form.php');
require_once($CFG->dirroot . '/mod/quiz/report/reportlib.php');

class popup_assign_kommissionell_check_exam_grading implements renderable, templatable {
    public function __construct($courseid, $quizid) {
        global $DB;
        $this->fp = block_exaquest_get_fachlichepruefer_by_courseid($courseid);
        $this->quizid = $quizid;

        // get students who attempted this quiz
        // 'SELECT DISTINCT u.id, u.firstname, u.lastname FROM {quiz_attempts} qa JOIN {user} u ON qa.userid = u.id WHERE qa.quiz = :quizid'
        $sql = 'SELECT DISTINCT u.id, u.firstname, u.lastname
                FROM {quiz_grades} qg
                JOIN {user} u ON qg.userid = u.id
              WHERE qg.quiz = :quizid';
        $this->students = $DB->get_records_sql($sql, ['quizid' => $quizid]);

        $this->assigned_persons = block_exaquest_get_assigned_persons_by_quizid_and_assigntype($quizid,
            BLOCK_EXAQUEST_QUIZASSIGNTYPE_KOMMISSIONELL_CHECK_EXAM_GRADING);
    }

    /**
     * Export this data, so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE, $CFG, $OUTPUT, $DB;
        $data = new stdClass();
        $data->students = $this->students;
        $data->fp = $this->fp;
        $data->quizid = $this->quizid;
        $data->assigned_persons =
            array_values($this->assigned_persons); // ARRAY_VALUES is needed, so the array is not indexed by the id of the person. Otherwise, in mustache they are not shown

        // create the fp autocomplete field with the help of an mform
        $mform = new autofill_helper_form($data->fp);
        // the choosable options need to be an array of strings
        $autocompleteoptions = [];
        foreach ($data->fp as $fp) {
            $autocompleteoptions[$fp->id] = $fp->firstname . ' ' . $fp->lastname;
        }
        $fp_autocomplete_html = $mform->create_autocomplete_multi_select_html($autocompleteoptions, "fp" . $data->quizid,
            'popup_assign_kommissionell_check_exam_grading');
        $data->fp_autocomplete_html = $fp_autocomplete_html;

        // create the student autocomplete field with the help of an mform
        $mform = new autofill_helper_form($data->students);
        // the choosable options need to be an array of strings
        $autocompleteoptions = [];
        foreach ($data->students as $student) {
            $autocompleteoptions[$student->id] = $student->firstname . ' ' . $student->lastname;
        }
        $student_autocomplete_html = $mform->create_autocomplete_multi_select_html($autocompleteoptions, "student" . $data->quizid,
            'popup_assign_kommissionell_check_exam_grading');
        $data->student_autocomplete_html = $student_autocomplete_html;


        $data->action =
            $PAGE->url->out(false, array('action' => 'assign_kommissionell_check_exam_grading', 'sesskey' => sesskey(),
                'courseid' => $COURSE->id));
        $data->sesskey = sesskey();

        return $data;
    }
}
