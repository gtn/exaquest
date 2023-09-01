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
require_once(__DIR__ . '/../classes/output/popup_change_owner.php');
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

        $fragenersteller = block_exaquest_get_fragenersteller_by_courseid($COURSE->id);

        //decides which button is visible to whom
        switch (intval($question->teststatus)) {
            case BLOCK_EXAQUEST_QUESTIONSTATUS_IMPORTED:
                if (has_capability('block/exaquest:changeowner', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_owner($fragenersteller, 'change_owner',
                        get_string('change_owner', 'block_exaquest'), $question, false));
                }
                if (has_capability('block/exaquest:modulverantwortlicher', \context_course::instance($COURSE->id)) ||
                    has_capability('block/exaquest:pruefungskoordination', \context_course::instance($COURSE->id)) ||
                    intval($question->ownerid) == $USER->id) {
                    echo $output->render(new \block_exaquest\output\popup_change_status_warning('release_question',
                        get_string('skip_and_release_question', 'block_exaquest'), $question));
                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_NEW:
            case BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE:
                if (has_capability('block/exaquest:changeowner', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_owner($fragenersteller, 'change_owner',
                        get_string('change_owner', 'block_exaquest'), $question, true));

                }
                if (intval($question->ownerid) == $USER->id &&
                    has_capability('block/exaquest:setstatustoreview', \context_course::instance($COURSE->id))) {
                    $usertoselect = block_exaquest_get_reviewer_by_courseid($COURSE->id);
                    echo $output->render(new \block_exaquest\output\popup_change_status($usertoselect, 'open_question_for_review',
                        get_string('open_question_for_review', 'block_exaquest'), $question));
                }
                if (has_capability('block/exaquest:modulverantwortlicher', \context_course::instance($COURSE->id)) ||
                    has_capability('block/exaquest:pruefungskoordination', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status_warning('release_question',
                        get_string('skip_and_release_question', 'block_exaquest'), $question));
                }
                //echo '<a href="#" class="changestatus'.$question->questionbankentryid.' btn btn-primary btn-sm" role="button" value="open_question_for_review"> '.get_string('open_question_for_review', 'block_exaquest').'</a>';
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS:
                if (has_capability('block/exaquest:changeowner', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_owner($fragenersteller, 'change_owner',
                        get_string('change_owner', 'block_exaquest'), $question, true));
                }
                if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status($fragenersteller, 'revise_question',
                        get_string('revise_question', 'block_exaquest'), $question));
                    if (has_capability('block/exaquest:dofachlichreview', \context_course::instance($COURSE->id))) {
                        echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                            ' btn btn-primary" role="button" value="fachlich_review_done"> ' .
                            get_string('fachlich_review_done', 'block_exaquest') . '</button>';
                    }
                    if (has_capability('block/exaquest:doformalreview', \context_course::instance($COURSE->id))) {
                        echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                            ' btn btn-primary" role="button" value="formal_review_done"> ' .
                            get_string('formal_review_done', 'block_exaquest') . '</button>';
                    }
                }
                if (has_capability('block/exaquest:modulverantwortlicher', \context_course::instance($COURSE->id)) ||
                    has_capability('block/exaquest:pruefungskoordination', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status_warning('release_question',
                        get_string('skip_and_release_question', 'block_exaquest'), $question));
                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE:
                if (has_capability('block/exaquest:changeowner', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_owner($fragenersteller, 'change_owner',
                        get_string('change_owner', 'block_exaquest'), $question, true));
                }
                if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status($fragenersteller, 'revise_question',
                        get_string('revise_question', 'block_exaquest'), $question));
                }
                if (has_capability('block/exaquest:dofachlichreview', \context_course::instance($COURSE->id))) {
                    echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                        ' btn btn-primary" role="button" value="fachlich_review_done"> ' .
                        get_string('fachlich_review_done', 'block_exaquest') . '</button>';
                }
                if (has_capability('block/exaquest:modulverantwortlicher', \context_course::instance($COURSE->id)) ||
                    has_capability('block/exaquest:pruefungskoordination', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status_warning('release_question',
                        get_string('skip_and_release_question', 'block_exaquest'), $question));
                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE:
                if (has_capability('block/exaquest:changeowner', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_owner($fragenersteller, 'change_owner',
                        get_string('change_owner', 'block_exaquest'), $question, true));
                }
                if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status($fragenersteller, 'revise_question',
                        get_string('revise_question', 'block_exaquest'), $question));
                    if (has_capability('block/exaquest:doformalreview', \context_course::instance($COURSE->id))) {
                        echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                            ' btn btn-primary" role="button" value="formal_review_done"> ' .
                            get_string('formal_review_done', 'block_exaquest') . '</button>';
                    }
                    if (has_capability('block/exaquest:modulverantwortlicher', \context_course::instance($COURSE->id)) ||
                        has_capability('block/exaquest:pruefungskoordination', \context_course::instance($COURSE->id))) {
                        echo $output->render(new \block_exaquest\output\popup_change_status_warning('release_question',
                            get_string('skip_and_release_question', 'block_exaquest'), $question));
                    }
                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED:
                if (has_capability('block/exaquest:changeowner', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_owner($fragenersteller, 'change_owner',
                        get_string('change_owner', 'block_exaquest'), $question, true));
                }
                if (has_capability('block/exaquest:releasequestion', \context_course::instance($COURSE->id))) {
                    echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                        ' btn btn-primary" role="button" value="release_question"> ' .
                        get_string('release_question', 'block_exaquest') . '</button>';
                }
                if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status($fragenersteller, 'revise_question',
                        get_string('revise_question', 'block_exaquest'), $question));
                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED:
                if (has_capability('block/exaquest:changeowner', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_owner($fragenersteller, 'change_owner',
                        get_string('change_owner', 'block_exaquest'), $question, true));
                }
                if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_status($fragenersteller, 'revise_question',
                        get_string('revise_question', 'block_exaquest'), $question));
                    echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                        ' btn btn-primary" role="button" value="lockquestion"> ' .
                        get_string('lock_question', 'block_exaquest') . '</button>';
                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_IN_QUIZ:
                if (has_capability('block/exaquest:changeowner', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_owner($fragenersteller, 'change_owner',
                        get_string('change_owner', 'block_exaquest'), $question, true));
                }
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED:
                if (has_capability('block/exaquest:changeowner', \context_course::instance($COURSE->id))) {
                    echo $output->render(new \block_exaquest\output\popup_change_owner($fragenersteller, 'change_owner',
                        get_string('change_owner', 'block_exaquest'), $question, true));
                }
                if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
                    if (block_exaquest_check_if_question_contains_categories($question->id)) {
                        echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                            ' btn btn-primary" role="button" value="unlockquestion"> ' .
                            get_string('unlock_question', 'block_exaquest') . '</button>';
                    } else {
                        echo '<button href="#" class="changestatus' . $question->questionbankentryid .
                            ' btn btn-primary disabled" disabled data-toggle="tooltip" role="button" value="unlockquestion" title="' .
                            get_string('missing_category_tooltip', 'block_exaquest') . '"> ' .
                            get_string('unlock_question', 'block_exaquest') . '</button>';
                    }
                    echo $output->render(new \block_exaquest\output\popup_change_status($fragenersteller, 'revise_question',
                        get_string('revise_question', 'block_exaquest'), $question));
                }
                break;
        }

        ?>

        <script type="text/javascript">
            //  javascript redirects events to the ajax.php file and passes necessary data
            $(document).ready(function () {
                $(".changestatus<?php echo $question->questionbankentryid; ?>").click(function (e) {
                    debugger
                    //let changestatus_value = $(".changestatus<?php //echo $question->questionbankentryid; ?>//").val();
                    let changestatus_value = e.currentTarget.value;
                    let textarea_value = $('.commenttext<?php echo $question->questionbankentryid; ?>').val();

                    if (changestatus_value == 'revise_question') {
                        let $selecteduser = $('#id_selectedusers<?php echo $question->questionbankentryid; ?>').val();
                        if ($selecteduser == "" || $selecteduser && $selecteduser.length == 0 || textarea_value == '') {
                            alert("Es muss mindestens eine Person ausgewählt sein und ein Kommentar eingegeben werden!");
                            return false;
                        }
                    }

                    if (changestatus_value == 'open_question_for_review') {

                        let $selecteduser = $('#id_selectedusers<?php echo $question->questionbankentryid; ?>').val();
                        if ($selecteduser == "" || $selecteduser && $selecteduser.length == 0) {
                            alert("Es muss mindestens eine Person ausgewählt sein!");
                            return false;
                        }
                    }
                    var data = {
                        action: $(this).val(),
                        questionbankentryid: <?php echo $question->questionbankentryid; ?>,
                        questionid: <?php echo $question->id; ?>,
                        courseid: <?php echo $COURSE->id; ?>,
                        //users: $('.userselectioncheckbox<?php //echo $question->questionbankentryid; ?>//:checkbox:checked').map(function () {
                        //    return $(this).val();
                        //}).get(), this was the code for the checkboxes, now we have a multiselect
                        users: $("#changeStatusModal<?php echo $question->questionbankentryid; ?>").find('.form-autocomplete-selection').children().map(function () {
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

                // same for changeowner
                $(".changeowner<?php echo $question->questionbankentryid; ?>").click(function (e) {
                    debugger
                    var data = {
                        action: $(this).val(),
                        questionbankentryid: <?php echo $question->questionbankentryid; ?>,
                        questionid: <?php echo $question->id; ?>,
                        courseid: <?php echo $COURSE->id; ?>,
                        //users: $('.userselectioncheckbox<?php //echo $question->questionbankentryid; ?>//:checkbox:checked').map(function () {
                        //    return $(this).val();
                        //}).get(), this was the code for the checkboxes, now we have a multiselect
                        users: $("#changeOwnerModal<?php echo $question->questionbankentryid; ?>").find('.form-autocomplete-selection').children().map(function () {
                            return $(this).attr("data-value");
                        }).get()
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
            });


            document.addEventListener("DOMContentLoaded", function () {
                // Get references to the button and link elements
                var openModalBtn = document.getElementById("openModalButton<?php echo $question->questionbankentryid; ?>");
                var openModalLink = document.querySelector("a[data-action='changeowner<?php echo $question->questionbankentryid; ?>']");

                // Function to simulate the button click event and open the modal
                function openModalWithButton() {
                    // Trigger the click event on the button
                    openModalBtn.click();
                }

                // Attach click event handler to the link
                openModalLink.addEventListener("click", function (event) {
                    debugger
                    event.preventDefault(); // Prevent default link behavior
                    openModalWithButton(); // Call the function to open the modal
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
