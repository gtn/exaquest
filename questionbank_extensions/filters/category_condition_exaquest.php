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
 * A search class to control from which category questions are listed.
 *
 * @package   core_question
 * @copyright 2013 Ray Morris
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core_question\bank\search;

use qbank_managecategories\helper;

/**
 *  This class controls from which category questions are listed.
 *
 * @copyright 2013 Ray Morris
 * @author    2021 Safat Shahin <safatshahin@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class category_condition_exaquest extends category_condition {
    /**
     * Constructor
     * @param string     $cat           categoryID,contextID as used with question_bank_view->display()
     * @param bool       $recurse       Whether to include questions from sub-categories
     * @param array      $contexts      Context objects as used by question_category_options()
     * @param \moodle_url $baseurl       The URL the form is submitted to
     * @param \stdClass   $course        Course record
     * @param integer    $maxinfolength The maximum displayed length of the category info.
     */
    public function __construct($cat, $recurse, $contexts, $baseurl, $course, $maxinfolength = null) {
        parent::__construct($cat, $recurse, $contexts, $baseurl, $course, $maxinfolength);

    }

    /**
     * Called by question_bank_view to display the GUI for selecting a category
     */
    public function display_options() {
        global $PAGE;
        $displaydata = [];
        $catmenu = helper::question_category_options($this->contexts, true, 0,
            true, -1, false);
        $displaydata['categoryselect'] = \html_writer::select($catmenu, 'category', $this->cat, [],
            array('class' => 'searchoptions custom-select', 'id' => 'id_selectacategory','style' => 'visibility:hidden'));
        $displaydata['categorydesc'] = '';
        if ($this->category) {
            $displaydata['categorydesc'] = $this->print_category_info($this->category);
        }
        return $PAGE->get_renderer('core_question', 'bank')->render_category_condition($displaydata);
    }

}
