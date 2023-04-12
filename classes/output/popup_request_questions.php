<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

global $CFG;
require_once($CFG->dirroot . '/blocks/exaquest/classes/form/autofill_helper_form.php');

class popup_request_questions implements renderable, templatable {
    /** @var string $fragenersteller Part of the data that should be passed to the template. */
    var $fragenersteller = null;

    public function __construct($fragenersteller) {
        $this->fragenersteller = $fragenersteller;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE, $CFG, $OUTPUT;
        $data = new stdClass();
        // https://www.sitepoint.com/community/t/help-accessing-deep-level-json-in-mustache-template-solved/290780
        // https://stackoverflow.com/questions/35999024/how-to-iterate-an-array-of-objects-in-mustache
        //$data->fragenersteller_selfmade = [(object)["username"  => array_pop($this->fragenersteller)->username], (object)["username"  =>array_pop($this->fragenersteller)->username]];
        // this would work, but is not feasable to write like this
        // The problem with $data->fragenersteller = $this->fragenersteller; is that there is an associative array, e.g. 3 => stdClass(), 10 => stdClass() etc.... it MUST start counting at 0, otherwise it will break mustache
        //$data->fragenersteller = array_values($this->fragenersteller);
        //foreach ($data->fragenersteller as $fragensteller) {
        //    $fragensteller->comma = true;
        //}
        //if (isset($data->fragenersteller) && !empty($data->fragenersteller)) {
        //    end($data->fragenersteller)->comma = false;
        //}
        // this was the code for the checkboxes. Now we do it with the autocomplete html

        $data->fragenersteller = $this->fragenersteller;

        // create the fragenersteller autocomplete field with the help of an mform
        $mform = new autofill_helper_form($data->fragenersteller);
        // the choosable options need to be an array of strings
        $autocompleteoptions = [];
        foreach ($data->fragenersteller as $fragenersteller) {
            $autocompleteoptions[$fragenersteller->id] = $fragenersteller->firstname . ' ' . $fragenersteller->lastname;
        }
        $fragenersteller_autocomplete_html = $mform->create_autocomplete_html($autocompleteoptions);
        $data->fragenersteller_autocomplete_html = $fragenersteller_autocomplete_html;

        $data->action =
            $PAGE->url->out(false, array('action' => 'request_questions', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->sesskey = sesskey();
        return $data;
    }
}



