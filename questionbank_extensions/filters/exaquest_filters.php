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
 * A search class to control whether hidden / deleted questions are hidden in the list.
 *
 * @package   core_question
 * @copyright 2013 Ray Morris
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//namespace core_question\bank\search; // Class 'core_question\bank\search' has been renamed for the autoloader and is now deprecated. Please use 'core_question\local\bank\condition'
namespace core_question\local\bank; // 4.3

//namespace qbank_tagquestion;
//
//use core\output\datafilter;
//use core_question\local\bank\condition;
/**
 * This class controls whether hidden / deleted questions are hidden in the list.
 *
 * @copyright 2013 Ray Morris
 * @author    2021 Safat Shahin <safatshahin@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class exaquest_filters extends condition {

    /** @var string SQL fragment to add to the where clause. */
    protected string $where = '';

    protected $filterstatus;

    /**
     * Constructor.
     */
    public function __construct($filterstatus = 0) {
        $this->filterstatus = $filterstatus;

    }

    /**
     * SQL fragment to add to the where clause.
     *
     * @return string
     */
    public function where() {
        global $USER, $COURSE;
        switch ($this->filterstatus) {
            case BLOCK_EXAQUEST_FILTERSTATUS_ALL_IMPORTED_QUESTIONS:
                $this->where = "qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_IMPORTED;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS:
                $this->where = "qs.status != " . BLOCK_EXAQUEST_QUESTIONSTATUS_IMPORTED; // all except imported
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_ALL_NEW_QUESTIONS:
                $this->where = "qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_NEW;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS:
                $this->where = "qbe.ownerid = " . $USER->id;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS_TO_SUBMIT:
                $this->where = "qbe.ownerid = " . $USER->id .
                    " AND qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_NEW;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVIEW:
                $this->where = "qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS .
                    " OR qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE .
                    " OR qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_FACHLICH_REVIEWED:
                $this->where = "qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_FORMAL_REVIEWED:
                $this->where = "qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVIEW:
                $this->where = "(qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS .
                    " OR qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE .
                    " OR qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE . ") 
                    AND qra.reviewerid = " . $USER->id;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVISE:
                $this->where = "qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVISE:
                $this->where = "qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE .
                    " AND qrevisea.reviserid = " . $USER->id;;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_RELEASE:
                $this->where = "qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_RELEASE:
                $this->where = "qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED . " AND qra.reviewerid = " . $USER->id;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_All_RELEASED_QUESTIONS:
                $this->where = "qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED;
                break;
            case BLOCK_EXAQUEST_FILTERSTATUS_ALL_LOCKED_QUESTIONS:
                $this->where = "qs.status = " . BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED;
                break;
        }
        //this is for restricing view of questions for new fragenersteller light role
        if (!has_capability('block/exaquest:readallquestions', \context_course::instance($COURSE->id))) {
            if (!has_capability('block/exaquest:fachlfragenreviewerlight', \context_course::instance($COURSE->id))) {
                if ($this->filterstatus != BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS &&
                    $this->filterstatus != BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS_TO_SUBMIT) {
                    if ($this->filterstatus == BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS) {
                        $this->where = "qbe.ownerid = " . $USER->id;
                    } else {
                        $this->where .= " AND qbe.ownerid = " . $USER->id;
                    }
                }
            } else {
                if ($this->filterstatus != BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS &&
                    $this->filterstatus != BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS_TO_SUBMIT) {
                    if ($this->filterstatus == BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS) {
                        $this->where = "AND qra.reviewerid = " . $USER->id;
                    } else {
                        $this->where .= " AND qra.reviewerid = " . $USER->id;
                    }
                }
            }
        }
        return $this->where;
    }

    /**
     * Print HTML to display the "Also show old questions" checkbox
     */
    public function display_options_adv() {
        global $PAGE, $COURSE;

        $selected = array_fill(0, 15, '');
        $selected[$this->filterstatus] = 'selected="selected"';

        $html =
            '<div class="form-group row"><label class="col-sm-2 col-form-label">Select Questions:</label><div class="col-sm-10"><select class="form-control select searchoptions" id="id_filterstatus" name="filterstatus">';
        if (has_capability('block/exaquest:readallquestions', \context_course::instance($COURSE->id))) {
            $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_ALL_IMPORTED_QUESTIONS] . ' value="' .
                BLOCK_EXAQUEST_FILTERSTATUS_ALL_IMPORTED_QUESTIONS . '">' . get_string('show_all_imported_questions', 'block_exaquest') .
                '</option>';
            $html .= '<option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS] . ' value="' .
                BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS . '">' . get_string('show_all_questions', 'block_exaquest') . '</option>';
        }
        $html .= '    <optgroup label="' . get_string('created', 'block_exaquest') . '">';
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_ALL_NEW_QUESTIONS] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_ALL_NEW_QUESTIONS . '">' . get_string('show_all_new_questions', 'block_exaquest') .
            '</option>';
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS . '">' . get_string('show_my_created_questions', 'block_exaquest') .
            '</option>';
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS_TO_SUBMIT] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS_TO_SUBMIT . '">' .
            get_string('show_my_created_questions_to_submit', 'block_exaquest') . '</option>';
        $html .= '    </optgroup>';
        $html .= '    <optgroup label="' . get_string('review', 'block_exaquest') . '">';
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVIEW] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVIEW . '">' .
            get_string('show_all_questions_to_review', 'block_exaquest') . '</option>';
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_FACHLICH_REVIEWED] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_FACHLICH_REVIEWED . '">' .
            get_string('show_all_fachlich_reviewed_questions_to_review', 'block_exaquest') . '</option>';
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_FORMAL_REVIEWED] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_FORMAL_REVIEWED . '">' .
            get_string('show_all_formal_reviewed_questions_to_review', 'block_exaquest') . '</option>';
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVIEW] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVIEW . '">' .
            get_string('show_questions_for_me_to_review', 'block_exaquest') . '</option>';
        $html .= '    </optgroup>';
        $html .= '    <optgroup label="' . get_string('revise', 'block_exaquest') . '">';
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVISE] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVISE . '">' . get_string('show_questions_to_revise', 'block_exaquest') .
            '</option>';
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVISE] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVISE . '">' .
            get_string('show_questions_for_me_to_revise', 'block_exaquest') . '</option>';
        $html .= '    </optgroup>';
        $html .= '    <optgroup label="' . get_string('release', 'block_exaquest') . '">';
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_RELEASE] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_RELEASE . '">' .
            get_string('show_questions_to_release', 'block_exaquest') . '</option>';
        //$html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_RELEASE] . ' value="' .
        //    BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_RELEASE . '">' .
        //    get_string('show_questions_for_me_to_release', 'block_exaquest') . '</option>';
        // TODO: most likely not needed
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_All_RELEASED_QUESTIONS] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_All_RELEASED_QUESTIONS . '">' .
            get_string('show_all_released_questions', 'block_exaquest') . '</option>';
        $html .= '    <optgroup label="' . get_string('locked_filter', 'block_exaquest') . '">';
        $html .= '        <option ' . $selected[BLOCK_EXAQUEST_FILTERSTATUS_ALL_LOCKED_QUESTIONS] . ' value="' .
            BLOCK_EXAQUEST_FILTERSTATUS_ALL_LOCKED_QUESTIONS . '">' .
            get_string('show_all_locked_questions', 'block_exaquest') . '</option>';
        $html .= '    </optgroup>';
        $html .= '</select></div></div>';

        return $html;
    }

    public function get_title() {
        return get_string('exaquest_filters', 'exaquest');
    }

    public function get_filter_class() {
        return null;
    }
}

