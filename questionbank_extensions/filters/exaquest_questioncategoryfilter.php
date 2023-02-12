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

namespace core_question\bank\search;

/**
 * This class controls whether hidden / deleted questions are hidden in the list.
 *
 * @copyright 2013 Ray Morris
 * @author    2021 Safat Shahin <safatshahin@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class exaquest_questioncategoryfilter extends condition {

    /** @var string SQL fragment to add to the where clause. */
    protected $where;

    protected $fragencharakter;
    protected $klassifikation;
    protected $fragefach;
    protected $lehrinhalt;

    /**
     * Constructor.
     */
    public function __construct($fragencharakter = -1, $klassifikation = -1, $fragefach = -1, $lehrinhalt = -1) {
        $this->fragencharakter = $fragencharakter;
        $this->klassifikation = $klassifikation;
        $this->fragefach = $fragefach;
        $this->lehrinhalt = $lehrinhalt;

    }

    /**
     * SQL fragment to add to the where clause.
     *
     * @return string
     */
    public function where() {
        global $USER;
        $this->where = '';
        if ($this->fragencharakter != -1) {
            if ($this->where == '') {
                $this->where .= "q.id IN (SELECT q.id
                                       FROM {question} q
                                       JOIN {customfield_data} cfd
                                       ON q.id = cfd.instanceid
                                       WHERE cfd.value like '%" . $this->fragencharakter . "%')";
            } else {
                $this->where .= "AND q.id IN (SELECT q.id
                                       FROM {question} q
                                       JOIN {customfield_data} cfd
                                       ON q.id = cfd.instanceid
                                       WHERE cfd.value like '%" . $this->fragencharakter . "%')";
            }

        }
        if ($this->klassifikation != -1) {
            if ($this->where == '') {
                $this->where .= "q.id IN (SELECT q.id
                                       FROM {question} q
                                       JOIN {customfield_data} cfd
                                       ON q.id = cfd.instanceid
                                       WHERE cfd.value like '%" . $this->klassifikation . "%')";
            } else {
                $this->where .= "AND q.id IN (SELECT q.id
                                       FROM {question} q
                                       JOIN {customfield_data} cfd
                                       ON q.id = cfd.instanceid
                                       WHERE cfd.value like '%" . $this->klassifikation . "%')";
            }
        }
        if ($this->fragefach != -1) {
            if ($this->where == '') {
                $this->where .= "q.id IN (SELECT q.id
                                       FROM {question} q
                                       JOIN {customfield_data} cfd
                                       ON q.id = cfd.instanceid
                                       WHERE cfd.value like '%" . $this->fragefach . "%')";
            } else {
                $this->where .= "AND q.id IN (SELECT q.id
                                       FROM {question} q
                                       JOIN {customfield_data} cfd
                                       ON q.id = cfd.instanceid
                                       WHERE cfd.value like '%" . $this->fragefach . "%')";
            }
        }
        if ($this->lehrinhalt != -1) {
            if ($this->where == '') {
                $this->where .= "q.id IN (SELECT q.id
                                       FROM {question} q
                                       JOIN {customfield_data} cfd
                                       ON q.id = cfd.instanceid
                                       WHERE cfd.value like '%" . $this->lehrinhalt . "%')";
            } else {
                $this->where .= "AND q.id IN (SELECT q.id
                                       FROM {question} q
                                       JOIN {customfield_data} cfd
                                       ON q.id = cfd.instanceid
                                       WHERE cfd.value like '%" . $this->lehrinhalt . "%')";
            }
        }

        return $this->where;
    }

    /**
     * Print HTML to display the "Also show old questions" checkbox
     */
    public function display_options_adv() {
        global $PAGE, $COURSE, $DB;

        $html =
            '<div style="height:50px"><div style="padding:5.5px;float:left">Select Fragencharakter:</div><select class="select custom-select searchoptions custom-select" id="id_filterstatus" style="margin-left:5px;margin-bottom:50px" name="fragencharakter">';
        $html .= '<option value="-1"></option>';
        $qcats = $DB->get_records("block_exaquestcategories", array("coursecategoryid" => $COURSE->category));
        $questcats = [];
        if ($qcats) {
            foreach ($qcats as $qcat) {
                $questcats[$qcat->categorytype][] = $qcat;
            }

            foreach ($questcats[0] as $cat) {
                if ($cat->id == $this->fragencharakter) {
                    $html .= '<option selected="selected" value="' .
                        $cat->id . '">' . $cat->categoryname . '</option>';
                } else {
                    $html .= '<option value="' .
                        $cat->id . '">' . $cat->categoryname . '</option>';
                }
            }
        }

        $html .= '</select></div>';

        $html .= '<div style="height:50px"><div style="padding:5.5px;float:left">Select Klassifikation:</div><select class="select custom-select searchoptions custom-select" id="id_filterstatus" style="margin-left:5px;margin-bottom:50px" name="klassifikation">';
        $html .= '<option value="-1"></option>';
        if ($questcats) {
            foreach ($questcats[1] as $cat) {
                if ($cat->id == $this->klassifikation) {
                    $html .= '<option selected="selected" value="' .
                        $cat->id . '">' . $cat->categoryname . '</option>';
                } else {
                    $html .= '<option value="' .
                        $cat->id . '">' . $cat->categoryname . '</option>';
                }
            }
        }
        $html .= '</select></div>';

        $html .= '<div style="height:50px"><div style="padding:5.5px;float:left">Select Fragefach:</div><select class="select custom-select searchoptions custom-select" id="id_filterstatus" style="margin-left:5px;margin-bottom:50px" name="fragefach">';
        $html .= '<option value="-1"></option>';
        if ($questcats) {
            foreach ($questcats[2] as $cat) {
                if ($cat->id == $this->fragefach) {
                    $html .= '<option selected="selected" value="' .
                        $cat->id . '">' . $cat->categoryname . '</option>';
                } else {
                    $html .= '<option value="' .
                        $cat->id . '">' . $cat->categoryname . '</option>';
                }
            }
        }
        $html .= '</select></div>';

        $html .= '<div style="height:50px"><div style="padding:5.5px;float:left">Select Lehrinhalt:</div><select class="select custom-select searchoptions custom-select" id="id_filterstatus" style="margin-left:5px;margin-bottom:50px" name="lehrinhalt">';
        $html .= '<option value="-1"></option>';
        if ($questcats) {
            foreach ($questcats[3] as $cat) {
                if ($cat->id == $this->lehrinhalt) {
                    $html .= '<option selected="selected" value="' . $cat->id . '">' . $cat->categoryname . '</option>';
                } else {
                    $html .= '<option value="' . $cat->id . '">' . $cat->categoryname . '</option>';
                }
            }
        }

        $html .= '</select></div>';

        return $html;
    }
}

