<?php
namespace block_exaquest\output;
global $DB, $CFG, $COURSE, $PAGE, $OUTPUT, $USER;
require_once($CFG->libdir . '/formslib.php');



// I want to use the autocomplete form element from moodle, but cannot use it in mustache like that. This is why I have the workaround with this form.
class autofill_helper_form extends \moodleform {
    /**
     * @inheritDoc
     */
    protected function definition() {

    }

    public function create_autocomplete_html($autocompleteoptions) {
        $options = array(
            'multiple' => true,
        );
        // moodle/lib/form/autocomplete.php
        $element = $this->_form->addElement('autocomplete', 'selectedusers', 'Fragenersteller', $autocompleteoptions, $options);
        return $element->toHtml();

        // how to use: enrol_users_form has this code:
        //$options = array(
        //    'ajax' => 'enrol_manual/form-potential-user-selector',
        //    'multiple' => true,
        //    'courseid' => 7,
        //    'enrolid' => 7,
        //    'perpage' => 7,
        //    'userfields' => 'email'
        //);
        //$this->_form->addElement('autocomplete', 'userlist', get_string('selectusers', 'enrol_manual'), array(), $options);
    }
}
