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
    public function __construct($courseid) {
        $this->pmw = block_exaquest_get_pmw_by_courseid($courseid);
        $this->fp = block_exaquest_get_fachlichepruefer_by_courseid($courseid);
    }

    /**
     * Export this data, so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE, $CFG, $OUTPUT;
        $data = new stdClass();

        $data->pmw = $this->pmw;
        $data->fp = $this->fp;

        // create the fp autocomplete field with the help of an mform
        $mform = new autofill_helper_form($data->fp);
        // the choosable options need to be an array of strings
        $autocompleteoptions = [];
        foreach ($data->fp as $fp) {
            $autocompleteoptions[$fp->id] = $fp->firstname . ' ' . $fp->lastname;
        }
        $fp_autocomplete_html = $mform->create_autocomplete_single_select_html($autocompleteoptions, "fp");
        $data->fp_autocomplete_html = $fp_autocomplete_html;

        // create the pmw autocomplete field with the help of an mform
        $mform = new autofill_helper_form($data->pmw);
        // the choosable options need to be an array of strings
        $autocompleteoptions = [];
        foreach ($data->pmw as $pmw) {
            $autocompleteoptions[$pmw->id] = $pmw->firstname . ' ' . $pmw->lastname;
        }
        $pmw_autocomplete_html = $mform->create_autocomplete_multi_select_html($autocompleteoptions, "pmw");
        $data->pmw_autocomplete_html = $pmw_autocomplete_html;


        $data->action =
            $PAGE->url->out(false, array('action' => 'assign_quiz_addquestions', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->sesskey = sesskey();


        return $data;
    }
}



