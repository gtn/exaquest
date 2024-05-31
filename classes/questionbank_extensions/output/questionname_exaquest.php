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

namespace qbank_viewquestionname\output;

class questionname_exaquest extends questionname {
    public function __construct(\stdClass $question) {
        parent::__construct(
            'qbank_viewquestionname',
            'questionname',
            $question->id,
            question_has_capability_on($question, 'edit'),
            format_string($question->name), $question->name,
            get_string('edit_question_name_hint', 'qbank_viewquestionname'),
            get_string('edit_question_name_label', 'qbank_viewquestionname', (object)[
                'name' => $question->name,
            ])
        );
    }

    public function get_template_name(\renderer_base $renderer): string {
        return 'core/inplace_editable';
    }
}
