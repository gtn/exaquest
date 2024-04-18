<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;

global $CFG;
require_once($CFG->dirroot . '/blocks/exaquest/classes/form/autofill_helper_form.php');

class popup_assign_check_exam_grading implements renderable, templatable {
    public function __construct($courseid, $quizid) {
        $this->bmw = block_exaquest_get_bmw_by_courseid($courseid);
        $this->fp = block_exaquest_get_fachlichepruefer_by_courseid($courseid);
        // get the FP that has been assigned to this quiz
        //$this->fp = block_exaquest_get_assigned_fachlicherpruefer($quizid);
        $this->quizid = $quizid;
        $this->assigned_persons = block_exaquest_get_assigned_persons_by_quizid_and_assigntype($quizid, BLOCK_EXAQUEST_QUIZASSIGNTYPE_CHECK_EXAM_GRADING);

        // remove the bmw and fp that have already be assigned:
        foreach ($this->assigned_persons as $assigned_person) {
            // $this->bmw is not an associative array, but simply from 0 to x
            if (isset($this->bmw[$assigned_person->id])) {
                unset($this->bmw[$assigned_person->id]);
            }
            if (isset($this->fp[$assigned_person->id])) {
                unset($this->fp[$assigned_person->id]);
            }
        }
    }

    /**
     * Export this data, so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE, $CFG, $OUTPUT;
        $data = new stdClass();

        $data->bmw = $this->bmw;
        $data->fp = $this->fp;
        $data->quizid = $this->quizid;
        $data->assigned_persons = array_values($this->assigned_persons); // ARRAY_VALUES is needed, so the array is not indexed by the id of the person. Otherwise in mustache they are not shown


        //// create the fp autocomplete field with the help of an mform
        //$mform = new autofill_helper_form($data->fp);
        //// the choosable options need to be an array of strings
        //$autocompleteoptions = [];
        //foreach ($data->fp as $fp) {
        //    $autocompleteoptions[$fp->id] = $fp->firstname . ' ' . $fp->lastname;
        //}
        //$fp_autocomplete_html = $mform->create_autocomplete_single_select_html($autocompleteoptions, "fp".$data->quizid);
        //$data->fp_autocomplete_html = $fp_autocomplete_html;

        // create the bmw autocomplete field with the help of an mform
        $mform = new autofill_helper_form($data->bmw);
        // the choosable options need to be an array of strings
        $autocompleteoptions = [];
        foreach ($data->bmw as $bmw) {
            $autocompleteoptions[$bmw->id] = $bmw->firstname . ' ' . $bmw->lastname;
        }
        // add the fp to this multiselect as well
        foreach ($data->fp as $fp) {
            $autocompleteoptions[$fp->id] = $fp->firstname . ' ' . $fp->lastname;
            $preselectedoption = $fp->id;
        }
        $bmw_autocomplete_html = $mform->create_autocomplete_multi_select_html($autocompleteoptions, "bmw" . $data->quizid, 'popup_assign_check_exam_grading', $preselectedoption);
        $data->bmw_autocomplete_html = $bmw_autocomplete_html;


        $data->action =
            $PAGE->url->out(false, array('action' => 'assign_check_exam_grading', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->sesskey = sesskey();


        return $data;
    }
}



