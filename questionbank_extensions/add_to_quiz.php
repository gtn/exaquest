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

class add_to_quiz extends column_base {


    public function get_title(): string {
        return get_string('add_to_quiz', 'block_exaquest');

    }

    public function get_name(): string {
        return 'addtoquiz';
    }

    protected function display_content($question, $rowclasses)
    {

        $quizid = optional_param('quizid', null, PARAM_INT);

        global $USER, $DB, $COURSE, $PAGE;
        $output = $PAGE->get_renderer('block_exaquest');
        $questioncreator = new \stdClass();
        $questioncreator->firstname = $question->creatorfirstname;
        $questioncreator->lastname = $question->creatorlastname;
        $questioncreator->id = $question->createdby;
        $questioncreators= array($questioncreator);#

        //check if already in the quiz
        if(! $DB->record_exists_sql("SELECT *
                                    FROM {question_references} qr
                                         JOIN {quiz_slots} qs ON qr.itemid = qs.id
                                   WHERE qr.component='mod_quiz' AND qr.questionarea = 'slot' AND qs.quizid = ? AND qr.questionbankentryid = ?", array($quizid, $question->questionbankentryid))){
            echo '<button href="#" class="addquestion' . $question->questionbankentryid . ' btn btn-primary" role="button" value="addquestion"> ' . get_string('add_to_quiz', 'block_exaquest') . '</button>';
      }


        ?>

        <script type="text/javascript">
// redirects event to ajax.php
            $(document).ready(function() {
                $(".addquestion<?php echo $question->questionbankentryid; ?>").click(function (e) {
                    var data = {
                        action: $(this).val(),
                        questionbankentryid: <?php echo $question->questionbankentryid; ?>,
                        questionid: <?php echo $question->id; ?>,
                        courseid: <?php echo $COURSE->id; ?>,
                        quizid: <?php echo $quizid; ?>,
                    };
                    e.preventDefault();
                    var ajax = $.ajax({
                        method: "POST",
                        url: "ajax.php",
                        data: data
                    }).done(function (ret) {
                        console.log(data.action, 'ret', ret);
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
        return ['qref' => 'LEFT JOIN {question_references} qref ON qbe.id = qref.questionbankentryid',
            'qusl' => 'LEFT JOIN {quiz_slots} qusl ON qref.itemid = qusl.id'];
    }
}
