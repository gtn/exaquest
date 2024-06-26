<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;

global $CFG;
require_once($CFG->dirroot . '/blocks/exaquest/classes/form/autofill_helper_form.php');

class popup_change_status_secondary_button implements renderable, templatable {
    var $selectusers = null;
    var $name = null;
    var $questionbankentryid = null;
    var $action = null;

    public function __construct($selectusers, $action, $name, $question) {
        global $USER;
        $this->selectusers = $selectusers;
        // you can never select yourself: remove yourself from the selectusers
        foreach ($this->selectusers as $key => $user) {
            if ($user->id == $USER->id) {
                unset($this->selectusers[$key]);
            }
        }
        $this->name = $name;
        $this->questionbankentryid = $question->questionbankentryid;
        $this->questionname = $question->name;
        $this->action = $action;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE, $DB;
        $data = new stdClass();
        $data->name = $this->name;
        $data->questionname = $this->questionname;
        $readonlyusers = [];
        $context = \context_course::instance($COURSE->id);
        // this is NOT needed anymore. The modulverantwortlicher should not be selectable, the PK should also not be selectable ==> keep code in case later it will be needed after all
        //foreach ($this->selectusers as $key => $user){
        //    if(is_enrolled($context, $user, "block/exaquest:modulverantwortlicher") || is_enrolled($context, $user, "block/exaquest:pruefungskoordination")){
        //        $readonlyusers[] =  $user;
        //        unset($this->selectusers[$key]);
        //    }
        //}
        $data->readonlyusers = $readonlyusers;
        $data->selectusers = array_values($this->selectusers);

        $data->questionbankentryid = $this->questionbankentryid;
        if ($this->action == 'revise_question') {
            $data->require = true;
            $data->text = get_string('revise_text', 'block_exaquest');
            $data->title = get_string('revise_title', 'block_exaquest');
            $data->commentplaceholder = get_string('comment_placeholder_mandatory', 'block_exaquest');
            // add all the users that have ever worked on this question
            // this allows e.g. the fragenerstellerlight to be assigned to revising the question (fragenerstellerlight is normally not selectable, as they can only see their own questions)
            $data->selectusers = array_merge($data->selectusers, $DB->get_records_sql('SELECT DISTINCT u.id, u.firstname, u.lastname
                                               FROM {question_versions} qv
                                               JOIN {question} q ON qv.questionid = q.id
                                               JOIN {user} u ON q.createdby = u.id
                                               WHERE qv.questionbankentryid = ' . $this->questionbankentryid . '
                                                AND u.deleted = 0'));
        } else if ($this->action == 'revise_question_from_quiz') {
            $data->require = true;
            $data->text = get_string('revise_text', 'block_exaquest');
            $data->title = get_string('revise_title', 'block_exaquest');
            $data->commentplaceholder = get_string('comment_placeholder_mandatory', 'block_exaquest');
            $data->change_status_and_remove_from_quiz = true;
            $data->selectusers = $data->selectusers;
        } else {
            $data->text = get_string('open_for_review_text', 'block_exaquest');
            $data->title = get_string('open_for_review_title', 'block_exaquest');
            $data->commentplaceholder = get_string('comment_placeholder', 'block_exaquest');
            $data->send_to_pk_text = get_string('notification_will_be_sent_to_pk', 'block_exaquest');
        }

        // create the selectusers autocomplete field with the help of an mform
        $mform = new autofill_helper_form();
        // the choosable options need to be an array of strings
        $autocompleteoptions = [];
        foreach ($data->selectusers as $selectuser) {
            $autocompleteoptions[$selectuser->id] = $selectuser->firstname . ' ' . $selectuser->lastname;
        }
        if ($this->action == 'open_question_for_review' || $this->action == 'revise_question') {
            $selectusers_autocomplete_html =
                $mform->create_autocomplete_single_select_html($autocompleteoptions, $this->questionbankentryid,
                    'popup_change_status');
        } else {
            $selectusers_autocomplete_html =
                $mform->create_autocomplete_multi_select_html($autocompleteoptions, $this->questionbankentryid,
                    'popup_change_status');
        }

        $data->selectusers_autocomplete_html = $selectusers_autocomplete_html;

        $data->action = $this->action;
        $data->sesskey = sesskey();
        return $data;
    }
}
