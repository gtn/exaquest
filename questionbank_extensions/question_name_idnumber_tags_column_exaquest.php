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

namespace qbank_viewquestionname;

use core_question\local\bank\menu_action_column_base;
use moodle_url;
use qbank_editquestion\edit_action_column;
use qbank_previewquestion\helper;
use qbank_previewquestion\question_preview_options;
use qbank_viewquestionname\output\questionname;
require_once($CFG->dirroot . '/lib/classes/task/manager.php');

// require once the questionname_exaquest
//use qbank_viewquestionname\output\questionname_exaquest;

/**
 * Class for question bank edit question column.
 *
 * @copyright 2009 Tim Hunt
 * @author    2021 Safat Shahin <safatshahin@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_name_idnumber_tags_column_exaquest extends question_name_idnumber_tags_column {
    protected function display_content($question, $rowclasses): void {
        global $OUTPUT, $PAGE, $COURSE;

        echo \html_writer::start_tag('div', ['class' => 'd-inline-flex flex-nowrap overflow-hidden w-100']);

        //$questiondisplay = $OUTPUT->render(new \qbank_viewquestionname\output\questionname($question));

        // generate a preview link for the question html
        $link = helper::question_preview_url($question->id, null, null, null, null, null, $this->qbank->returnurl );
        $questiondisplay = \html_writer::link(
            //new \moodle_url('/blocks/exaquest/preview_question.php', ['id' => $question->id]),
            $link,
            $question->name,
            ['class' => 'questionpreviewlink']
        );


        //// get context of coursecategory by the id stored in the question object
        //$context = \context::instance_by_id($question->contextid, IGNORE_MISSING);
        //$questiondisplay = $this->question_preview_link($question->id, $context, true);

        $labelfor = $this->label_for($question);
        if ($labelfor) {
            echo \html_writer::tag('label', $questiondisplay, [
                'for' => $labelfor,
            ]);
        } else {
            echo \html_writer::start_span('questionname flex-grow-1 flex-shrink-1 text-truncate');
            echo $questiondisplay;
            echo \html_writer::end_span();
        }

        // Question idnumber.
        // The non-breaking space '&nbsp;' is used in html to fix MDL-75051 (browser issues caused by chrome and Edge).
        if ($question->idnumber !== null && $question->idnumber !== '') {
            echo ' ' . \html_writer::span(
                    \html_writer::span(get_string('idnumber', 'question'), 'accesshide')
                    . '&nbsp;' . \html_writer::span(s($question->idnumber), 'badge badge-primary'), 'ml-1');
        }

        // Question tags.
        if (!empty($question->tags)) {
            $tags = \core_tag_tag::get_item_tags('core_question', 'question', $question->id);
            echo $OUTPUT->tag_list($tags, null, 'd-inline flex-shrink-1 text-truncate ml-1', 0, null, true);
        }

        echo \html_writer::end_tag('div');
    }

    ///**
    // * From question\bank\previewquestion\classes\output\renderer, modified to use the question_preview_url helper.
    // * Render an icon, optionally with the word 'Preview' beside it, to preview a given question.
    // *
    // * @param int $questionid the id of the question to be previewed.
    // * @param context $context the context in which the preview is happening.
    // *      Must be a course or category context.
    // * @param bool $showlabel if true, show the word 'Preview' after the icon.
    // *      If false, just show the icon.
    // */
    //private function question_preview_link($questionid, $context, $showlabel) {
    //    global $OUTPUT;
    //    if ($showlabel) {
    //        $alt = '';
    //        $label = get_string('preview');
    //        $attributes = [];
    //    } else {
    //        $alt = get_string('preview');
    //        $label = '';
    //        $attributes = ['title' => $alt];
    //    }
    //
    //    $image = $OUTPUT->pix_icon('t/preview', $alt, '', ['class' => 'iconsmall']);
    //    $link = helper::question_preview_url($questionid, null, null, null, null, $context, null,
    //        question_preview_options::ALWAYS_LATEST);
    //    $action = new \popup_action('click', $link, 'questionpreview', helper::question_preview_popup_params());
    //
    //    return $OUTPUT->action_link($link, $image . $label, $action, $attributes);
    //}
}