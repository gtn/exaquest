<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

global $CFG;
require_once($CFG->dirroot . '/blocks/exaquest/classes/form/autofill_helper_form.php');

class popup_assign_gradeexam implements renderable, templatable {
    public function __construct($courseid, $quizid) {
        $this->bmw = block_exaquest_get_bmw_by_courseid($courseid);
        $this->fp = block_exaquest_get_fachlichepruefer_by_courseid($courseid);
        $this->quizid = $quizid;
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
        }
        $bmw_autocomplete_html = $mform->create_autocomplete_multi_select_html($autocompleteoptions, "bmw".$data->quizid, 'popup_assign_gradeexam');
        $data->bmw_autocomplete_html = $bmw_autocomplete_html;

        // TODO: add questions to select?

        $data->action =
            $PAGE->url->out(false, array('action' => 'assign_gradeexam', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->sesskey = sesskey();


        return $data;
    }
}



