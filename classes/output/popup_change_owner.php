<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;

global $CFG;
require_once($CFG->dirroot . '/blocks/exaquest/classes/form/autofill_helper_form.php');


class popup_change_owner implements renderable, templatable {
    var $selectusers = null;
    var $name = null;
    var $questionbankentryid = null;
    var $action = null;

    public function __construct($selectusers, $action, $name, $question, $hidden) {
        $this->selectusers = $selectusers;
        $this->name = $name;
        $this->questionbankentryid = $question->questionbankentryid;
        $this->questionname = $question->name;
        $this->action = $action;
        $this->hidden = $hidden;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE, $DB, $OUTPUT;
        $data = new stdClass();
        $data->questionname = $this->questionname;
        $readonlyusers = [];

        $data->readonlyusers = $readonlyusers;
        $data->selectusers = array_values($this->selectusers);

        $data->questionbankentryid = $this->questionbankentryid;
        $data->require = true;
        $data->text = get_string('change_owner_text', 'block_exaquest');
        $data->title = get_string('change_owner_title', 'block_exaquest');
        //$data->title = $OUTPUT->pix_icon('i/grades', 'Grades', 'core', ['class' => 'custom-icon-class', 'title' => 'Grades']);
        //$data->title = $OUTPUT->image_url('i/siteevent', 'core');


        $data->selectusers = array_merge($DB->get_records_sql('SELECT DISTINCT u.id, u.firstname, u.lastname
                                           FROM {question_bank_entries} qbe
                                           JOIN {user} u ON qbe.ownerid = u.id
                                           WHERE qbe.id = ' . $this->questionbankentryid), $data->selectusers);

        // create the selectusers autocomplete field with the help of an mform
        $mform = new autofill_helper_form();
        // the choosable options need to be an array of strings
        $autocompleteoptions = [];
        foreach ($data->selectusers as $selectuser) {
            $autocompleteoptions[$selectuser->id] = $selectuser->firstname . ' ' . $selectuser->lastname;
        }
        $selectusers_autocomplete_html = $mform->create_autocomplete_single_select_html($autocompleteoptions, $this->questionbankentryid, 'popup_change_owner');


        $data->selectusers_autocomplete_html = $selectusers_autocomplete_html;

        $data->action = $this->action;
        $data->sesskey = sesskey();

        $data->hidden = $this->hidden;
        return $data;
    }
}
