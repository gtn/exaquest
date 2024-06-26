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

use core_question\local\bank\column_base;

/**
 * A column to show question id.
 *
 * @package   qbank_viewcreator
 * @copyright 2009 Tim Hunt
 * @author    2021 Ghaly Marc-Alexandre <marc-alexandreghaly@catalyst-ca.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_id_column extends column_base {

    public function get_name(): string {
        return 'questionid';
    }

    public function get_title(): string {
        return get_string('question_id', 'block_exaquest');
    }

    public function is_sortable(): array {
        return [
            'ID' => ['field' => 'qbe.id', 'title' => "ID"],
        ];
    }

    protected function display_content($question, $rowclasses): void {

        if (!empty($question->questionbankentryid)) {
            echo $question->questionbankentryid;
        }
    }

}
