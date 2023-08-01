<?php
/*
 * Exaquest similarity comparison extension
 *
 * @package    block_exaquest
 * @copyright  2022 Stefan Swerk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
global $DB, $CFG, $COURSE, $PAGE, $OUTPUT, $USER;
require_once($CFG->libdir . '/formslib.php');

class similarity_comparison_form extends moodleform {
    public static array $sortBy = array("similarityDesc", "similarityAsc");
    public array $exams = array();

    /**
     * @inheritDoc
     */
    protected function definition() {
        global $DB;
        $mform = $this->_form;

        // get all quizzes of this course
        $exams = $DB->get_records_sql("SELECT * FROM {quiz} WHERE course = ?", array($this->_customdata['courseid']));

        $this->exams = array();
        $this->exams[0] = get_string("exaquest:similarity_exam_select_default", "block_exaquest");
        foreach ($exams as $exam) {
            $this->exams[$exam->id] = $exam->name;
        }

        /*
        $mform->addElement('text','email','email');
        $mform->setType('email', PARAM_NOTAGS);
        $mform->setDefault('email','Please enter email');

        $mform->addElement('button', 'show', 'Show');
        */

        $similarityButtonGroup = array();
        $similarityButtonGroup[] = $mform->createElement('submit', 'showSimilarityOverviewButton',
            get_string("exaquest:similarity_update_button_label", "block_exaquest"));
        $similarityButtonGroup[] = $mform->createElement('submit', 'computeSimilarityButton',
            get_string("exaquest:similarity_compute_button_label", "block_exaquest"));
        $similarityButtonGroup[] = $mform->createElement('submit', 'computeSimilarityStoreButton',
            get_string("exaquest:similarity_persist_button_label", "block_exaquest"));

        $mform->addGroup($similarityButtonGroup, 'similarityButtonGroup', '', ' ', false);

        $optionCheckboxGroup = array();
        $optionCheckboxGroup[] = $mform->createElement('advcheckbox', 'substituteid', '',
            get_string("exaquest:similarity_substitute_checkbox_label", "block_exaquest"), array('group' => 1), array(0, 1));
        $optionCheckboxGroup[] = $mform->createElement('advcheckbox', 'hidepreviousq', '',
            get_string("exaquest:similarity_hide_checkbox_label", "block_exaquest"), array('group' => 1), array(0, 1));
        $mform->setDefault('substituteid', $this->_customdata['substituteid']);
        $mform->setDefault('hidepreviousq', $this->_customdata['hidepreviousq']);

        $optionCheckboxGroup[] =
            $mform->createElement('select', 'sort', get_string("exaquest:similarity_sort_select_label", "block_exaquest"),
                self::$sortBy);
        $mform->setDefault('sort', array_search($this->_customdata['sort'], self::$sortBy, false));

        // add all exams in dropdown select
        $mform->addElement('select', 'examid', get_string("exaquest:similarity_exam_select_label", "block_exaquest"), $this->exams);
        // check if the examid is set
        if (in_array('examid', array_keys($this->_customdata))) {
            $mform->setDefault('examid', $this->_customdata['examid']);
        }

        $mform->addGroup($optionCheckboxGroup, 'optionCheckboxGroup', '', ' ', false);

        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
        $mform->setType('courseid', PARAM_INT);
        //$this->add_action_buttons();
    }

    public function validation($data, $files) {
        return parent::validation($data, $files);
    }
}