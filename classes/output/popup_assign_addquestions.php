<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

global $CFG;
require_once($CFG->dirroot . '/blocks/exaquest/classes/form/autofill_helper_form.php');

class popup_assign_addquestions implements renderable, templatable {
    public function __construct($courseid, $quizid) {
        $this->pmw = block_exaquest_get_pmw_by_courseid($courseid);
        $this->fp = block_exaquest_get_fachlichepruefer_by_courseid($courseid);
        $this->quizid = $quizid;
        // get all pmw and fp that have already been assigned to add questions to this quiz
        $this->assigned_persons = block_exaquest_get_assigned_persons_by_quizid_and_assigntype($quizid, BLOCK_EXAQUEST_QUIZASSIGNTYPE_ADDQUESTIONS);


        $categorys_required_counts = block_exaquest_get_fragefaecher_by_courseid_and_quizid($courseid, $quizid);
        $categorys_current_counts = block_exaquest_get_category_question_count($quizid);
        $categoryoptionidkeys = array_keys($categorys_required_counts);
        $this->fragefaecher = block_exaquest_get_category_names_by_ids($categoryoptionidkeys, true);
        $this->missingquestionscount = 0;
        foreach($this->fragefaecher as $key => $option){
            $this->fragefaecher[$key]->requiredquestioncount = $categorys_required_counts[$key]->questioncount;
            $this->fragefaecher[$key]->currentquestioncount = $categorys_current_counts[$key] ? : 0;
            //if($this->fragefaecher[$key]->currentquestioncount < $this->fragefaecher[$key]->requiredquestioncount) {
            //    $this->missingquestionscount += $this->fragefaecher[$key]->requiredquestioncount -
            //            $this->fragefaecher[$key]->currentquestioncount;
            //}
        }


    }

    /**
     * Export this data, so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE;
        $data = new stdClass();

        $data->pmw = $this->pmw;
        $data->fp = $this->fp;
        $data->quizid = $this->quizid;
        $data->assigned_persons = array_values($this->assigned_persons); // ARRAY_VALUES is needed, so the array is not indexed by the id of the person. Otherwise in mustache they are not shown
        $data->fragefaecher = array_values($this->fragefaecher); // ARRAY_VALUES is needed, so the array is not indexed by the id of the person. Otherwise in mustache they are not shown

        //// create the fp autocomplete field with the help of an mform
        //$mform = new autofill_helper_form($data->fp);
        //// the choosable options need to be an array of strings
        //$autocompleteoptions = [];
        //foreach ($data->fp as $fp) {
        //    $autocompleteoptions[$fp->id] = $fp->firstname . ' ' . $fp->lastname;
        //}
        //$fp_autocomplete_html = $mform->create_autocomplete_single_select_html($autocompleteoptions, "fp".$data->quizid);
        //$data->fp_autocomplete_html = $fp_autocomplete_html;

        // create the pmw autocomplete field with the help of an mform
        $mform = new autofill_helper_form($data->pmw);
        // the choosable options need to be an array of strings
        $autocompleteoptions = [];
        foreach ($data->pmw as $pmw) {
            $autocompleteoptions[$pmw->id] = $pmw->firstname . ' ' . $pmw->lastname;
        }
        // add the fp to this multiselect as well
        foreach ($data->fp as $fp) {
            $autocompleteoptions[$fp->id] = $fp->firstname . ' ' . $fp->lastname;
        }
        $pmw_autocomplete_html = $mform->create_autocomplete_multi_select_html($autocompleteoptions, "pmw".$data->quizid, 'popup_assign_addquestions');
        $data->pmw_autocomplete_html = $pmw_autocomplete_html;


        $data->action =
            $PAGE->url->out(false, array('action' => 'assign_quiz_addquestions', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->sesskey = sesskey();


        return $data;
    }
}



