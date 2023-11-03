<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;

global $CFG;
require_once($CFG->dirroot . '/blocks/exaquest/classes/form/autofill_helper_form.php');
require_once($CFG->dirroot . '/mod/quiz/report/reportlib.php');

class popup_assign_gradeexam implements renderable, templatable {
    public function __construct($courseid, $quizid) {
        $this->bmw = block_exaquest_get_bmw_by_courseid($courseid);
        $this->fp = block_exaquest_get_fachlichepruefer_by_courseid($courseid);
        $this->quizid = $quizid;

        $quiz = new stdClass();
        $quiz->id = $quizid;
        $this->significant_questions = quiz_report_get_significant_questions($quiz);
    }

    /**
     * Export this data, so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE, $CFG, $OUTPUT, $DB;
        $data = new stdClass();

        $data->bmw = $this->bmw;
        $data->fp = $this->fp;
        $data->quizid = $this->quizid;

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
        $bmw_autocomplete_html = $mform->create_autocomplete_multi_select_html($autocompleteoptions, "bmw" . $data->quizid,
                'popup_assign_gradeexam');
        $data->bmw_autocomplete_html = $bmw_autocomplete_html;

        $significant_questions = $this->significant_questions;
        // get the question names

        // create the bmw autocomplete field with the help of an mform
        $mform = new autofill_helper_form($significant_questions);
        // the choosable options need to be an array of strings
        $autocompleteoptions = [];
        foreach ($significant_questions as $key => $question) {
            $autocompleteoptions[$question->id] = $DB->get_record('question', array('id' => $question->id))->name;
        }
        $questions_autocomplete_html =
                $mform->create_autocomplete_multi_select_html($autocompleteoptions, "questions" . $data->quizid,
                        'popup_assign_gradeexam');
        $data->questions_autocomplete_html = $questions_autocomplete_html;

        $data->action =
                $PAGE->url->out(false, array('action' => 'assign_gradeexam', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->sesskey = sesskey();

        return $data;
    }
}



