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
    public function __construct($persons, $assign_button_string) {
        //$this->pmw = block_exaquest_get_pmw_by_courseid($courseid);
        //$this->fp = block_exaquest_get_fachlichepruefer_by_courseid($courseid);
        $this->persons = $persons;
        $this->assign_button_string = $assign_button_string;
    }

    /**
     * Export this data, so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE, $CFG, $OUTPUT;
        $data = new stdClass();

        //$data->pmw = $this->pmw;
        //$data->fp = $this->fp;
        $data->persons = $this->persons;
        $data->assign_string = $this->assign_button_string;

        // create the fp autocomplete field with the help of an mform
        $mform = new autofill_helper_form($data->persons);
        // the choosable options need to be an array of strings
        $autocompleteoptions = [];
        foreach ($data->persons as $person) {
            $autocompleteoptions[$person->id] = $person->firstname . ' ' . $person->lastname;
        }
        $autocomplete_html = $mform->create_autocomplete_multi_select_html($autocompleteoptions);
        $data->autocomplete_html = $autocomplete_html;

        //// create the pmw autocomplete field with the help of an mform
        //$mformpmw = new autofill_helper_form($data->pmw);
        //// the choosable options need to be an array of strings
        //$autocompleteoptionspmw = [];
        //foreach ($data->pmw as $pmw) {
        //    $autocompleteoptionspmw[$pmw->id] = $pmw->firstname . ' ' . $pmw->lastname;
        //}
        //$pmw_autocomplete_html = $mformpmw->create_autocomplete_multi_select_html($autocompleteoptionspmw);
        //$data->pmw_autocomplete_html = $pmw_autocomplete_html;


        $data->action =
            $PAGE->url->out(false, array('action' => 'request_questions', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->sesskey = sesskey();


        return $data;
    }
}



