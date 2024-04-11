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
 *  shows all categories assigned to this question
 *
 * @package
 * @copyright  2022 fabio <>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace qbank_openquestionforreview;

use core_question\local\bank\column_base;

class category_options extends column_base {

    public function get_title(): string {
        return get_string('category_options', 'block_exaquest');

    }

    public function get_name(): string {
        return 'categoryoptions';
    }

    protected function display_content($question, $rowclasses) {

        $quizid = optional_param('quizid', null, PARAM_INT);

        global $USER, $DB, $COURSE, $PAGE;

        $categoryoptionids = $DB->get_records_sql("SELECT cfd.value
                                    FROM {question} q
                                    JOIN {customfield_data} cfd ON q.id = cfd.instanceid
                                   WHERE q.id = ?", array($question->id));

        $categoryoptionidarray = array();
        foreach ($categoryoptionids as $categoryoptionid) {
            $mrg = explode(',', $categoryoptionid->value);
            $categoryoptionidarray = array_merge($categoryoptionidarray, $mrg);
        }
        $query = "('" . implode("','", $categoryoptionidarray) . "')";

        $categoryoptions = $DB->get_records_sql("SELECT eqc.id, eqc.categoryname, eqc.categorytype
                                    FROM {" . BLOCK_EXAQUEST_DB_CATEGORIES . "} eqc
                                   WHERE eqc.id IN " . $query);

        $options = array();
        foreach ($categoryoptions as $categoryoption) {
            $options[$categoryoption->categorytype][] = $categoryoption->categoryname;
        }

        for ($k = 0; $k <= 3; $k++) {
            if (!is_array($options[$k])) {
                $options[$k][] = ' ';
            }
        }

                $html = '<div class="container">
          <div class="row">
            <div class="col-sm-6 ">
                <div class="exaquest-category-tag-fragencharakter rounded">
                    ' . implode(' ', $options[0]) . '
                </div>
            </div>
            <div class="col-sm-6 ">
            <div class="exaquest-category-tag-klassifikation rounded">
                    ' . implode(' ', $options[1]) . '
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 ">
                <div class="exaquest-category-tag-fragefach rounded">
                    ' . implode(' ', $options[2]) . '
                </div>
            </div>
            <div class="col-sm-6 ">
                <div class="exaquest-category-tag-lerninhalt rounded">
                    ' . implode(' ', $options[3]) . '
                </div>
            </div>
          </div>
        </div>';

        echo $html;
        //echo '<div class="p-3 mb-2 bg-danger text-center text-white"></div>';

    }

    public function get_extra_joins(): array {
        return ['qref' => 'LEFT JOIN {question_references} qref ON qbe.id = qref.questionbankentryid',
                'qusl' => 'LEFT JOIN {quiz_slots} qusl ON qref.itemid = qusl.id',
                'cfd' => 'LEFT JOIN {customfield_data} cfd ON q.id = cfd.instanceid'];
    }
}
