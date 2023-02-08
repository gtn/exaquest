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
 *  file description here.
 *
 * @package
 * @copyright  2022 fabio <>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace qbank_openquestionforreview;

use core_question\local\bank\column_base;

class usage_check_column extends column_base {


    public function get_title(): string {
        return get_string('usage_check_column', 'block_exaquest');

    }

    public function get_name(): string {
        return 'usagecheck';
    }

    protected function display_content($question, $rowclasses)
    {

        $quizid = optional_param('quizid', null, PARAM_INT);

        global $USER, $DB, $COURSE, $PAGE;

        //check if already in the quiz
        if($DB->record_exists_sql("SELECT *
                                    FROM {question_references} qr
                                         JOIN {quiz_slots} qs ON qr.itemid = qs.id
                                   WHERE qr.component='mod_quiz' AND qr.questionarea = 'slot' AND qs.quizid = ? AND qr.questionbankentryid = ?", array($quizid, $question->questionbankentryid))){
            echo '<div class="p-3 mb-2 bg-secondary text-center text-white">Already used in this exam</div>';
        } else {
            /*
            $cnt = $DB->count_records_sql("SELECT *
                                        FROM {block_exaquestquizstatus} eqst
                                         JOIN {quiz_slots} qs ON eqst.quizid = qs.quizid
                                         JOIN {question_references} qr ON qr.itemid = qs.id
                                            WHERE qr.component='mod_quiz' AND qr.questionarea = 'slot' AND qs.quizid = ? AND qr.questionbankentryid = ?", array($quizid, $question->questionbankentryid));


            if($cnt>0){
                echo '<div class="p-3 mb-2 bg-success text-center text-white">'.$cnt.' times used</div>';
            } else {
                echo '<div class="p-3 mb-2 bg-success text-center text-white">Not used</div>';
            }
            */
        }



    }
}
