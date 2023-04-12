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

require_once(__DIR__ . '/../classes/output/popup_change_status.php');
require_once(__DIR__ . '/../classes/output/popup_change_status_warning.php');

$PAGE->requires->js('/blocks/exaquest/javascript/jquery.js', true);

class change_status extends column_base {

    public function get_name(): string {
        return 'changestatus';
    }

    public function get_title(): string {
        return get_string('change_status', 'block_exaquest');

    }

    protected function display_content($question, $rowclasses): void {
        global $USER, $DB, $COURSE, $PAGE;
        //echo '<div class="container"><div class="row"><div class="col-md-12 text-right">';
        $output = $PAGE->get_renderer('block_exaquest');
        $questioncreator = new \stdClass();
        $questioncreator->firstname = $question->creatorfirstname;
        $questioncreator->lastname = $question->creatorlastname;
        $questioncreator->id = $question->createdby;
        $questioncreators = array($questioncreator);


        switch (intval($question->teststatus)) {

            case BLOCK_EXAQUEST_QUESTIONSTATUS_NEW:
            case BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE:
                if (intval($question->ownerid) == $USER->id &&
                    has_capability('block/exaquest:setstatustoreview', \context_course::instance($COURSE->id))) {
                    $usertoselect = block_exaquest_get_reviewer_by_courseid($COURSE->id);
                    echo $output->render(new \block_exaquest\output\popup_change_status($usertoselect, 'open_question_for_review',
                        get_string('open_question_for_review', 'block_exaquest'), $question->questionbankentryid));
                }
                //echo '<a href="#" class="changestatus'.$question->questionbankentryid.' btn btn-primary btn-sm" role="button" value="open_question_for_review"> '.get_string('open_question_for_review', 'block_exaquest').'</a>';
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS:
                if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status($questioncreators, 'revise_question',
                        get_string('revise_question', 'block_exaquest'), $question->questionbankentryid));
                    echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                        ' btn btn-primary" role="button" value="fachlich_review_done"> ' .
                        get_string('fachlich_review_done', 'block_exaquest') . '</button>';
                    if (has_capability('block/exaquest:modulverantwortlicher', \context_course::instance($COURSE->id)) ||
                        has_capability('block/exaquest:pruefungskoordination', \context_course::instance($COURSE->id))) {
                        echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                            ' btn btn-primary" role="button" value="formal_review_done"> ' .
                            get_string('formal_review_done', 'block_exaquest') . '</button>';
                        echo $output->render(new \block_exaquest\output\popup_change_status_warning('release_question',
                            get_string('skip_and_release_question', 'block_exaquest'), $question->questionbankentryid));
                    }
                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE:
                if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status($questioncreators, 'revise_question',
                        get_string('revise_question', 'block_exaquest'), $question->questionbankentryid));
                    if (has_capability('block/exaquest:modulverantwortlicher', \context_course::instance($COURSE->id)) ||
                        has_capability('block/exaquest:pruefungskoordination', \context_course::instance($COURSE->id))) {
                        echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                            ' btn btn-primary" role="button" value="formal_review_done"> ' .
                            get_string('formal_review_done', 'block_exaquest') . '</button>';
                        echo $output->render(new \block_exaquest\output\popup_change_status_warning('release_question',
                            get_string('skip_and_release_question', 'block_exaquest'), $question->questionbankentryid));
                    }

                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE:
                if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status($questioncreators, 'revise_question',
                        get_string('revise_question', 'block_exaquest'), $question->questionbankentryid));
                    echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                        ' btn btn-primary" role="button" value="formal_review_done"> ' .
                        get_string('formal_review_done', 'block_exaquest') . '</button>';
                    if (has_capability('block/exaquest:modulverantwortlicher', \context_course::instance($COURSE->id)) ||
                        has_capability('block/exaquest:pruefungskoordination', \context_course::instance($COURSE->id))) {
                        echo $output->render(new \block_exaquest\output\popup_change_status_warning('release_question',
                            get_string('skip_and_release_question', 'block_exaquest'), $question->questionbankentryid));
                    }
                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED:
                if (has_capability('block/exaquest:releasequestion', \context_course::instance($COURSE->id))) {
                    echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                        ' btn btn-primary" role="button" value="release_question"> ' .
                        get_string('release_question', 'block_exaquest') . '</button>';
                }
                if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status($questioncreators, 'revise_question',
                        get_string('revise_question', 'block_exaquest'), $question->questionbankentryid));
                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED:
                if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status($questioncreators, 'revise_question',
                        get_string('revise_question', 'block_exaquest'), $question->questionbankentryid));
                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_IN_QUIZ:
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED:
                break;
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
                        //users: $('.userselectioncheckbox<?php //echo $question->questionbankentryid; ?>//:checkbox:checked').map(function () {
                        //    return $(this).val();
                        //}).get(), this was the code for the checkboxes, now we have a multiselect
                        users: $('.form-autocomplete-selection').children().map(function() {
                            return $(this).attr("data-value");
                        }).get(),
                        commenttext: $('.commenttext<?php echo $question->questionbankentryid; ?>').val(),
                    };
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

                $(".disable<?php echo $question->questionbankentryid; ?>").attr('disabled', true);

                $('.commenttext<?php echo $question->questionbankentryid; ?>').on('keyup', function () {
                    var textarea_value = $('.commenttext<?php echo $question->questionbankentryid; ?>').val();

                    if (textarea_value != '') {
                        $(".disable<?php echo $question->questionbankentryid; ?>").attr('disabled', false);
                    } else {
                        $(".disable<?php echo $question->questionbankentryid; ?>").attr('disabled', true);
                    }
                });
            });

        </script>
        <?php

    }

    public function load_additional_data(array $questions) {
        global $DB;

        $questionstatusdb = $DB->get_records("block_exaquestquestionstatus");

        $questionbankentries = $DB->get_records("question_bank_entries");

        $questionstatus = array();
        foreach ($questionstatusdb as $qs) {
            $questionstatus[$qs->questionbankentryid] = $qs->status;
        }

        foreach ($questions as $question) {
            $question->teststatus = $questionstatus[$question->questionbankentryid];
            $question->ownerid = $questionbankentries[$question->questionbankentryid]->ownerid;
        }

    }

    public function get_extra_joins(): array {
        return ['qs' => 'JOIN {block_exaquestquestionstatus} qs ON qbe.id = qs.questionbankentryid',
            'qra' => 'LEFT JOIN {block_exaquestreviewassign} qra ON qbe.id = qra.questionbankentryid',
            'qrevisea' => 'LEFT JOIN {block_exaquestreviseassign} qrevisea ON qbe.id = qrevisea.questionbankentryid'];
    }

}
