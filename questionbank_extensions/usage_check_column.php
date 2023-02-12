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
            $prevuses = $DB->get_records_sql("SELECT qu.id, qu.timemodified
                                    FROM {question_references} qr
                                         JOIN {quiz_slots} qs ON qr.itemid = qs.id
                                         JOIN {quiz} qu ON qu.id = qs.quizid
                                         JOIN {course} c ON c.id = qu.course
                                   WHERE qr.component='mod_quiz' AND qr.questionarea = 'slot' AND c.category = ? AND qr.questionbankentryid = ?", array($COURSE->category, $question->questionbankentryid));
            $prevusescnt = count($prevuses);

            $last = 0;
            foreach($prevuses as $prevuse){
                if($prevuse->timemodified > $last){
                    $last = $prevuse->timemodified;
                }
            }

            $date = new \DateTime();
            $date->setTimestamp($last);

            if($prevusescnt>0){
                echo '<div class="p-3 mb-2 bg-danger text-center text-white">'.$prevusescnt.' times used, last on '. $date->format('d.m.Y').'</div>';
            } else {
                echo '<div class="p-3 mb-2 bg-success text-center text-white">Not used</div>';
            }

        }



    }
}
