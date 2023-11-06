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


/**
 * Defines an abstract class for filtering/searching the question bank.
 *
 * @package   core_question
 * @copyright 2013 Ray Morris
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core_question\local\bank;

/**
 * An abstract class for filtering/searching questions.
 *
 * See also {@see question_bank_view::init_search_conditions()}.
 * @copyright 2013 Ray Morris
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class added_to_quiz_condition extends condition {
    /**
     * Return an SQL fragment to be ANDed into the WHERE clause to filter which questions are shown.
     * @return string SQL fragment. Must use named parameters.
     */

    public function where(){

        $quizid = optional_param('quizid', null, PARAM_INT);

        /*$this->where = "qbe.id IN (SELECT qref.
                        FROM {question_references} qref
                        JOIN {quiz_slots} qusl ON qref.itemid = qusl.id
                        WHERE qref.component='mod_quiz' AND qref.questionarea = 'slot' AND qusl.quizid = qbe.id)";
*/
        $this->where = "quizid != ". $quizid . " OR quizid IS NULL";
        return $this->where;
    }

    public function get_title() {
        return get_string('added_to_quiz_condition', 'exaquest');
    }

    public function get_filter_class() {
        return null;
    }
}
