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
 *  button to remove question from quiz.
 *
 * @package
 * @copyright  2022 fabio <>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace qbank_openquestionforreview;

use core_question\local\bank\column_base;

class lock_from_quiz extends column_base {

    public function get_title(): string {
        return get_string('lock_from_quiz', 'block_exaquest');

    }

    public function get_name(): string {
        return 'lock_from_quiz';
    }

    protected function display_content($question, $rowclasses) {
        global $DB, $COURSE, $OUTPUT;

        $quizid = optional_param('quizid', null, PARAM_INT);
        $status = $DB->get_field(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, 'status', array("questionbankentryid" => $question->questionbankentryid));

        // if the question has NOT been added --> enable locking the question
        if (!$DB->record_exists_sql("SELECT *
                                    FROM {question_references} qr
                                         JOIN {quiz_slots} qs ON qr.itemid = qs.id
                                   WHERE qr.component='mod_quiz' AND qr.questionarea = 'slot' AND qs.quizid = ? AND qr.questionbankentryid = ?", array($quizid, $question->questionbankentryid))) {
            echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                ' btn btn-primary" role="button" value="lockquestion"> ' .
                get_string('lock_question', 'block_exaquest') . '</button>';
        }


        ?>
        <script type="text/javascript">

            $(document).ready(function () {
                $(".changestatus<?php echo $question->questionbankentryid; ?>").click(function (e) {
                    var data = {
                        action: $(this).val(),
                        questionbankentryid: <?php echo $question->questionbankentryid; ?>,
                        questionid: <?php echo $question->id; ?>,
                        courseid: <?php echo $COURSE->id; ?>,
                        sesskey: "<?php echo sessKey(); ?>",
                        //users: $('.userselectioncheckbox<?php //echo $question->questionbankentryid; ?>//:checkbox:checked').map(function () {
                        //    return $(this).val();
                        //}).get(), this was the code for the checkboxes, now we have a multiselect
                        users: $("#changeStatusModal<?php echo $question->questionbankentryid; ?>").find('.form-autocomplete-selection').children().map(function () {
                            return $(this).attr("data-value");
                        }).get(),
                    };

                    debugger
                    e.preventDefault();
                    var ajax = $.ajax({
                        method: "POST",
                        url: "ajax.php",
                        data: data
                    }).done(function () {
                        //console.log(data.action, 'ret', ret);
                        location.reload();
                    }).fail(function (ret) {
                        var errorMsg = '';
                        if (ret.responseText[0] == '<') {
                            // html
                            errorMsg = $(ret.responseText).find('.errormessage').text();
                        }
                        console.log("Error in action '" + data.action + "'", errorMsg, 'ret', ret);
                    });
                });
            });

        </script>
        <?php

    }

    public function get_extra_joins(): array {
        return ['qs' => 'JOIN {block_exaquestquestionstatus} qs ON qbe.id = qs.questionbankentryid',
            'qra' => 'LEFT JOIN {block_exaquestreviewassign} qra ON qbe.id = qra.questionbankentryid'];
    }
}
