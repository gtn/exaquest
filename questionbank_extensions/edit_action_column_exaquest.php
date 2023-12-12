<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace qbank_editquestion;

use core_question\local\bank\menu_action_column_base;
use moodle_url;
use qbank_previewquestion\helper;

/**
 * Class for question bank edit question column.
 *
 * @copyright 2009 Tim Hunt
 * @author    2021 Safat Shahin <safatshahin@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class edit_action_column_exaquest extends edit_action_column {

    // ideas to change the link from edit to preview:
    // 1. look at public function question_row(structure $structure... in edit_renderer of the mode/quiz/edit maybe
    // 2. look at the question/bank/previewquestion... the code structure is more similar to what we need
    protected function get_url_icon_and_label(\stdClass $question): array {
        global $COURSE, $USER, $DB;
        if (!\question_bank::is_qtype_installed($question->qtype)) {
            // It sometimes happens that people end up with junk questions
            // in their question bank of a type that is no longer installed.
            // We cannot do most actions on them, because that leads to errors.
            return [null, null, null];
        }
        $questionStatus = $DB->get_field(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, 'status',
                array('questionbankentryid' => $question->questionbankentryid));

        // if the user can edit this question and it is in the right state, and the user is the owner OR has been assigned to revise then show the edit link.
        if (question_has_capability_on($question, 'edit') // has to have edit capability
                && ($questionStatus < BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED || // question has to be in the right state
                        $questionStatus == BLOCK_EXAQUEST_QUESTIONSTATUS_IMPORTED ||
                        $questionStatus == BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED)
                && (($question->ownerid == $USER->id
                                // user has to be owner OR modulverantwortlicher OR pruefungskoordination OR assigned reviser
                                && has_capability('block/exaquest:setstatustoreview', \context_course::instance($COURSE->id)))
                        || ((has_capability('block/exaquest:modulverantwortlicher', \context_course::instance($COURSE->id)))
                                || has_capability('block/exaquest:pruefungskoordination', \context_course::instance($COURSE->id)))
                        ||
                        (intval($question->reviserid) == $USER->id && $questionStatus == BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE &&
                                has_capability('block/exaquest:setstatustoreview', \context_course::instance($COURSE->id))))
        ) {
            return [$this->edit_question_moodle_url($question->id), 't/edit', $this->stredit];
            //return [$this->preview_question_moodle_url($question->id), 't/edit', $this->stredit];
            //return [helper::question_preview_url($question->id), 't/preview', $this->stredit];

        } else {
            return [null, null, null];
        }
    }
    // this lead to a problem in the question bank for showing the imported questions since customfield data does not exist for imported questiosn
    // solution: left join? or just put that into category_options.php where it belongs
    public function get_extra_joins(): array {
        return ['cfd' => 'LEFT JOIN {customfield_data} cfd ON q.id = cfd.instanceid'];
    }

    //private function preview_question_moodle_url($questionid): moodle_url {
    //    //return new moodle_url($this->editquestionurl, ['id' => $questionid]);
    //    $params = [
    //        'id' => $questionid,
    //    ];
    //    if ($context->contextlevel == CONTEXT_MODULE) {
    //        $params['cmid'] = $context->instanceid;
    //    } else if ($context->contextlevel == CONTEXT_COURSE) {
    //        $params['courseid'] = $context->instanceid;
    //    }
    //    if ($previewid) {
    //        $params['previewid'] = $previewid;
    //    }
    //    if ($returnurl !== null) {
    //        $params['returnurl'] = $returnurl;
    //    }
    //    return new moodle_url('/question/bank/previewquestion/preview.php', $params);
    //}
}
