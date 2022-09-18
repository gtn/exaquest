<?php

namespace qbank_deletequestion;

use core_question\local\bank\question_version_status;
use core_question\local\bank\menu_action_column_base;

class delete_action_column_exaquest extends delete_action_column {


    protected function get_url_icon_and_label(\stdClass $question): array {
        global $DB, $USER, $COURSE;

        $questionStatus = $DB->get_field(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, 'status', array('questionbankentryid' => $question->questionbankentryid));

        if (!(question_has_capability_on($question, 'edit')
            && $questionStatus < BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED
            && (($question->createdby == $USER->id
                    && has_capability('block/exaquest:setstatustoreview', \context_course::instance($COURSE->id))
                    && ($questionStatus == BLOCK_EXAQUEST_QUESTIONSTATUS_NEW || $questionStatus == BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE))
                || ((has_capability('block/exaquest:modulverantwortlicher', \context_course::instance($COURSE->id)))
                    || has_capability('block/exaquest:pruefungskoordination', \context_course::instance($COURSE->id))))

        )) {
            return [null, null, null];
        }
        if ($question->status === question_version_status::QUESTION_STATUS_HIDDEN) {
            $hiddenparams = array(
                'unhide' => $question->id,
                'sesskey' => sesskey());
            $hiddenparams = array_merge($hiddenparams, $this->returnparams);
            $url = new \moodle_url($this->deletequestionurl, $hiddenparams);
            return [$url, 't/restore', $this->strrestore];
        } else {
            $deleteparams = array(
                'deleteselected' => $question->id,
                'q' . $question->id => 1,
                'sesskey' => sesskey());
            $deleteparams = array_merge($deleteparams, $this->returnparams);
            $url = new \moodle_url($this->deletequestionurl, $deleteparams);
            return [$url, 't/delete', $this->strdelete];
        }
    }

}