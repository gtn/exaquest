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

namespace qbank_openquestionforreview;

use core_question\local\bank\action_column_base;
use core_question\local\bank\column_base;
use core_question\local\bank\menu_action_column_base;
use core_question\local\bank\menuable_action;

/**
 * A column to open the change_owner_popup.
 * Originally the popup was like e.g. change status.
 * The solution for getting this functionality into the action-menue is the following:
 * The action leads nowhere.
 * In change_status.php the click is handeled by an event-listener.
 * The event-listener takes the action from the button and opens the popup.
 * The button itself is hidden by display:none.
 * Better solution: Add the whole functionality into the action-menue. But this would lead to code duplication and it is not sure
 * how it will work or if this solution is needed permanently
 * --> solve it like this for now
 *
 * based on question/bank/tagquestion/tags_action_column
 */
class set_fragenersteller_column extends action_column_base implements menuable_action {

    public function get_name(): string {
        return 'questionid';
    }

    public function get_title(): string {
        return get_string('question_id', 'block_exaquest');
    }

    public function is_sortable(): array {
        return [
            'ID' => ['field' => 'q.id', 'title' => "ID"],
        ];
    }

    protected function display_content($question, $rowclasses): void {

        if (!empty($question->id)) {
            echo $question->id;
        }
    }

    public function get_action_menu_link(\stdClass $question): ?\action_menu_link {
        global $COURSE;
        [$url, $attributes] = $this->get_link_url_and_attributes($question);

        //<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changeStatusModal227" id="yui_3_17_2_1_1690883936729_85">
        // create a url that leads nowhere
        if (has_capability('block/exaquest:changeowner', \context_course::instance($COURSE->id))) {
            return new \action_menu_link_secondary($url, new \pix_icon('i/group', ''),
                get_string('change_owner_title', 'block_exaquest'), $attributes);
        }
        return null;
    }

    protected function get_link_url_and_attributes($question): array {
        $url = new \moodle_url('', []);

        $attributes = [
            'data-action' => 'changeowner' . $question->questionbankentryid,
            'data-contextid' => $this->qbank->get_most_specific_context()->id,
            'data-questionid' => $question->id,
        ];

        return [$url, $attributes];
    }

}
