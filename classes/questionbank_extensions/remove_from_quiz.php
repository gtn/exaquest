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

class remove_from_quiz extends column_base {

    public function get_title(): string {
        return get_string('remove_from_quiz', 'block_exaquest');

    }

    public function get_name(): string {
        return 'removefromquiz';
    }

    protected function display_content($question, $rowclasses): void {
        echo static::get_content_static($question);
    }

    public static function get_content_static($question): string {
        $quizid = optional_param('quizid', null, PARAM_INT);

        global $DB, $COURSE;

        $url = new \moodle_url('/mod/quiz/edit_rest.php');
        $quizslotid = $DB->get_field_sql("SELECT qs.id
            FROM {question_references} qr
            JOIN {quiz_slots} qs ON qr.itemid = qs.id
            WHERE qr.component='mod_quiz' AND qr.questionarea = 'slot' AND qs.quizid = ? AND qr.questionbankentryid = ?",
            array($quizid, $question->questionbankentryid));

        ob_start();

        //check if already in the quiz
        if ($quizslotid) {
            echo '<button href="#" id="removequestion' . $question->questionbankentryid . '" class="exaquest-changequestionstatus removequestion' .
                $question->questionbankentryid . ' btn btn-primary" type="button" value="removequestion" data-quizslotid="' . $quizslotid . '"> ' .
                get_string('remove_from_quiz', 'block_exaquest') . '</button>';

            // TODO: remove the script below
            // the script is OLD code, which can be removed after everything is changed to table_sql!!!
            ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#removequestion<?php echo $question->questionbankentryid; ?>").click(function (e) {
                        var data = {
                            action: "DELETE",
                            class: "resource",
                            id: <?php echo $quizslotid; ?>,
                            courseid: <?php echo $COURSE->id; ?>,
                            quizid: <?php echo $quizid; ?>,
                            sesskey: "<?php echo sessKey(); ?>"
                        };
                        e.preventDefault();
                        var ajax = $.ajax({
                            method: "POST",
                            url: "<?php echo $url; ?>",
                            data: data
                        }).done(function (ret) {
                            console.log(data.action, 'ret', ret);
                            location.reload();
                            // TODO: Instead of pagereload, which would be the simple solution: only update that row, which is far better for usability but more complex to code.
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

        return ob_get_clean();
    }

    public function get_extra_joins(): array {
        return ['qs' => 'JOIN {block_exaquestquestionstatus} qs ON qbe.id = qs.questionbankentryid',
            'qra' => 'LEFT JOIN {block_exaquestreviewassign} qra ON qbe.id = qra.questionbankentryid',
            'qrevisea' => 'LEFT JOIN {block_exaquestreviseassign} qrevisea ON qbe.id = qrevisea.questionbankentryid'];
    }
}
