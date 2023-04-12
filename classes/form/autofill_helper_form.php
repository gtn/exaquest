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

    public function create_autocomplete_html($autocompleteoptions, $questionbankentryid) {
        $options = array(
            'multiple' => true,
        );
        // moodle/lib/form/autocomplete.php
        // if I keep 'selectedusers' for every mfrom it breaks. Only the first will be displayed correctly, the rest will show a simple select with options, without the added fields.
        // ==> needs to be unique
        $element = $this->_form->addElement('autocomplete', 'selectedusers'.$questionbankentryid, 'Fragenersteller', $autocompleteoptions, $options);
        return $element->toHtml();
        // how to use example: enrol_users_form has this code:
    }
}
