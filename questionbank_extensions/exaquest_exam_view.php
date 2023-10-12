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
 * this calss is for the exam view to add questions to exams
 *
 * @package    exaquest_history
 * @copyright  2022 fabio <>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core_question\local\bank;

use core_plugin_manager;
use core_question\bank\search\condition;
use qbank_columnsortorder\column_manager;
use qbank_editquestion\editquestion_helper;
use qbank_managecategories\helper;
use qbank_questiontodescriptor;

require_once('exaquest_view.php');
require_once('plugin_feature.php');
require_once('filters/added_to_quiz_condition.php');
require_once('filters/only_released_questions.php');
require_once('filters/exaquest_questioncategoryfilter.php');

// this calss is for the exam view to add questions to exams
class exaquest_exam_view extends exaquest_view {

    public function __construct($contexts, $pageurl, $course, $cm = null) {
        parent::__construct($contexts, $pageurl, $course, $cm);
    }

    /**
     * Display the header element for the question bank.
     */
    protected function display_question_bank_header(): void {
        global $OUTPUT, $DB, $SESSION;

        $quizid = $SESSION->quizid;

        if ($quizid != null) {
            $quizname = $DB->get_field("quiz", "name", array("id" => $quizid));
        }

        echo $OUTPUT->heading(get_string('questionbank_selected_quiz', 'block_exaquest') . '' . $quizname, 2);
    }

    protected function wanted_columns(): array {
        $this->requiredcolumns = [];
        $excludefeatures = [
            'question_usage_column',
            'history_action_column',
            'edit_menu_column',
            'edit_action_column',
            'copy_action_column',
            'tags_action_column',
            'export_xml_action_column',
            'delete_action_column',
            'question_status_column',
            'version_number_column',
            'change_status'

        ];
        $questionbankcolumns = $this->get_question_bank_plugins();
        foreach ($questionbankcolumns as $classobject) {
            if (empty($classobject) || in_array($classobject->get_column_name(), $excludefeatures)) {
                continue;
            }
            $this->requiredcolumns[$classobject->get_column_name()] = $classobject;
        }

        return $this->requiredcolumns;
    }

    /**
     * Get the list of qbank plugins with available objects for features.
     *
     * @return array
     */
    protected function get_question_bank_plugins(): array {
        $questionbankclasscolumns = [];
        $newpluginclasscolumns = [];
        //edited:
        $corequestionbankcolumns = [
            'checkbox_column',
            'question_type_column',
            'question_name_idnumber_tags_column',
            'edit_menu_column',
            'edit_action_column',
            'copy_action_column',
            'tags_action_column',
            'preview_action_column',
            'history_action_column',
            'delete_action_column',
            'export_xml_action_column',
            'question_status_column',
            'version_number_column',
            'creator_name_column',
            'comment_count_column'
        ];
        if (question_get_display_preference('qbshowtext', 0, PARAM_BOOL, new \moodle_url(''))) {
            $corequestionbankcolumns[] = 'question_text_row';
        }

        foreach ($corequestionbankcolumns as $fullname) {
            $shortname = $fullname;
            if (class_exists('core_question\\local\\bank\\' . $fullname)) {
                $fullname = 'core_question\\local\\bank\\' . $fullname;
                $questionbankclasscolumns[$shortname] = new $fullname($this);
            } else {
                $questionbankclasscolumns[$shortname] = '';
            }
        }
        $plugins = \core_component::get_plugin_list_with_class('qbank', 'plugin_feature', 'plugin_feature.php');
        foreach ($plugins as $componentname => $plugin) {
            $pluginentrypointobject = new $plugin();
            $plugincolumnobjects = $pluginentrypointobject->get_question_columns($this);
            // Don't need the plugins without column objects.
            if (empty($plugincolumnobjects)) {
                unset($plugins[$componentname]);
                continue;
            }
            foreach ($plugincolumnobjects as $columnobject) {
                $columnname = $columnobject->get_column_name();
                foreach ($corequestionbankcolumns as $key => $corequestionbankcolumn) {
                    if (!\core\plugininfo\qbank::is_plugin_enabled($componentname)) {
                        unset($questionbankclasscolumns[$columnname]);
                        continue;
                    }
                    // Check if it has custom preference selector to view/hide.
                    if ($columnobject->has_preference()) {
                        if (!$columnobject->get_preference()) {
                            continue;
                        }
                    }
                    if ($corequestionbankcolumn === $columnname) {
                        $questionbankclasscolumns[$columnname] = $columnobject;
                    } else {
                        // Any community plugin for column/action.
                        //$newpluginclasscolumns[$columnname] = $columnobject;
                    }
                }
            }
        }

        //// New plugins added at the end of the array, will change in sorting feature.
        //foreach ($newpluginclasscolumns as $key => $newpluginclasscolumn) {
        //    $questionbankclasscolumns[$key] = $newpluginclasscolumn;
        //}
        //// Check if qbank_columnsortorder is enabled.
        //if (array_key_exists('columnsortorder', core_plugin_manager::instance()->get_enabled_plugins('qbank'))) {
        //    $columnorder = new column_manager();
        //    $questionbankclasscolumns = $columnorder->get_sorted_columns($questionbankclasscolumns);
        //}

        // Mitigate the error in case of any regression.
        foreach ($questionbankclasscolumns as $shortname => $questionbankclasscolumn) {
            if (empty($questionbankclasscolumn)) {
                unset($questionbankclasscolumns[$shortname]);
            }
        }

        $specialpluginentrypointobject = new \qbank_openquestionforreview\plugin_feature();
        $specialplugincolumnobjects = $specialpluginentrypointobject->get_question_columns($this);
        $questionbankclasscolumns["change_status"] = $specialplugincolumnobjects[0];
        $questionbankclasscolumns["edit_action_column"] = $specialplugincolumnobjects[1];
        $questionbankclasscolumns["delete_action_column"] = $specialplugincolumnobjects[2];
        $questionbankclasscolumns["history_action_column"] = $specialplugincolumnobjects[3];
        $questionbankclasscolumns["add_to_quiz"] = $specialplugincolumnobjects[4];
        $questionbankclasscolumns["usage_check_column"] = $specialplugincolumnobjects[5];
        $questionbankclasscolumns["category_options"] = $specialplugincolumnobjects[6];
        $questionbankclasscolumns["remove_from_quiz"] = $specialplugincolumnobjects[7];


        return $questionbankclasscolumns;
    }

    public function wanted_filters($cat, $tagids, $showhidden, $recurse, $editcontexts, $showquestiontext, $filterstatus = 0,
        $fragencharakter = -1, $klassifikation = -1, $fragefach = -1, $lehrinhalt = -1): void {
        global $CFG;
        list(, $contextid) = explode(',', $cat);
        $catcontext = \context::instance_by_id($contextid);
        $thiscontext = $this->get_most_specific_context();
        //var_dump($catcontext);
        //echo("---------------");
        //var_dump($thiscontext);
        //die;
        // Category selection form.
        $this->display_question_bank_header();
        //edited:
        // Display tag filter if usetags setting is enabled/enablefilters is true.
        if ($this->enablefilters) {
            if (is_array($this->customfilterobjects)) {
                foreach ($this->customfilterobjects as $filterobjects) {
                    $this->searchconditions[] = $filterobjects;
                }
            } else {
                if ($CFG->usetags) {
                    array_unshift($this->searchconditions,
                        new \core_question\bank\search\tag_condition([$catcontext, $thiscontext], $tagids));
                }

                //array_unshift($this->searchconditions, new \core_question\bank\search\hidden_condition(!$showhidden));
                //array_unshift($this->searchconditions, new \core_question\bank\search\added_to_quiz_condition($filterstatus));
                array_unshift($this->searchconditions, new \core_question\bank\search\only_released_questions());
                array_unshift($this->searchconditions, new \core_question\bank\search\exaquest_category_condition(
                    $cat, $recurse, $editcontexts, $this->baseurl, $this->course));
                array_unshift($this->searchconditions,
                    new \core_question\bank\search\exaquest_questioncategoryfilter($fragencharakter, $klassifikation, $fragefach,
                        $lehrinhalt));
            }
        }
        $this->display_options_form($showquestiontext);
    }

    protected function load_page_questions($page, $perpage): \moodle_recordset {
        //this funciton calls the sql query and allows me to adjust questions that will get selected
        global $DB;
        $questions = $DB->get_recordset_sql($this->loadsql, $this->sqlparams, $page * $perpage, $perpage);
        if (empty($questions)) {
            $questions->close();
            // No questions on this page. Reset to page 0.
            $questions = $DB->get_recordset_sql($this->loadsql, $this->sqlparams, 0, $perpage);
        }
        return $questions;
    }

    public function display($pagevars, $tabname): void {
        global $SESSION;

        $page = $pagevars['qpage'];
        $perpage = $pagevars['qperpage'];
        $cat = $pagevars['cat'];
        $recurse = $pagevars['recurse'];
        $showhidden = $pagevars['showhidden'];
        $showquestiontext = $pagevars['qbshowtext'];
        $tagids = [];
        $filterstatus = $pagevars['filterstatus'];
        $fragencharakter = array_key_exists('fragencharakter', $pagevars) ? $pagevars['fragencharakter'] : null;
        $klassifikation = array_key_exists('klassifikation', $pagevars) ? $pagevars['klassifikation'] : null;
        $fragefach = array_key_exists('fragefach', $pagevars) ? $pagevars['fragefach'] : null;
        $lehrinhalt = array_key_exists('lehrinhalt', $pagevars) ? $pagevars['lehrinhalt'] : null;

        if (!empty($pagevars['qtagids'])) {
            $tagids = $pagevars['qtagids'];
        }

        echo \html_writer::start_div('questionbankwindow boxwidthwide boxaligncenter');

        $editcontexts = $this->contexts->having_one_edit_tab_cap($tabname);

        //var_dump($cat);
        // Show the filters and search options.
        $this->wanted_filters($cat, $tagids, $showhidden, $recurse, $editcontexts, $showquestiontext, $filterstatus,
            $fragencharakter, $klassifikation, $fragefach, $lehrinhalt);

        // Continues with list of questions.
        $this->display_question_list($this->baseurl, $cat, null, $page, $perpage,
            $this->contexts->having_cap('moodle/question:add'));
        echo \html_writer::end_div();

    }

    /**
     * Prints the table of questions in a category with interactions
     *
     * @param \moodle_url $pageurl The URL to reload this page.
     * @param string $categoryandcontext 'categoryID,contextID'.
     * @param int $recurse Whether to include subcategories.
     * @param int $page The number of the page to be displayed
     * @param int|null $perpage Number of questions to show per page
     * @param array $addcontexts contexts where the user is allowed to add new questions.
     */
    protected function display_question_list($pageurl, $categoryandcontext, $recurse = 1, $page = 0,
        $perpage = null, $addcontexts = []): void {
        global $OUTPUT;
        // This function can be moderately slow with large question counts and may time out.
        // We probably do not want to raise it to unlimited, so randomly picking 5 minutes.
        // Note: We do not call this in the loop because quiz ob_ captures this function (see raise() PHP doc).
        \core_php_time_limit::raise(300);

        $category = $this->get_current_category($categoryandcontext);
        $perpage = $perpage ?? $this->pagesize;

        list($categoryid, $contextid) = explode(',', $categoryandcontext);
        $catcontext = \context::instance_by_id($contextid);

        $canadd = has_capability('moodle/question:add', $catcontext);

        $this->create_new_question_form($category, $canadd);

        $this->build_query();
        $totalnumber = $this->get_question_count();
        if ($totalnumber == 0) {
            return;
        }
        $questionsrs = $this->load_page_questions($page, $perpage);
        $questions = [];
        foreach ($questionsrs as $question) {
            if (!empty($question->id)) {
                $questions[$question->id] = $question;
            }
        }
        $questionsrs->close();
        foreach ($this->requiredcolumns as $name => $column) {
            $column->load_additional_data($questions);
        }

        $pageingurl = new \moodle_url($pageurl, $pageurl->params());
        $pagingbar = new \paging_bar($totalnumber, $page, $perpage, $pageingurl);
        $pagingbar->pagevar = 'qpage';

        $this->display_top_pagnation($OUTPUT->render($pagingbar));

        // This html will be refactored in the bulk actions implementation.
        echo \html_writer::start_tag('form', ['action' => $pageurl, 'method' => 'post', 'id' => 'questionsubmit']);
        echo \html_writer::start_tag('fieldset', ['class' => 'invisiblefieldset', 'style' => "display: block;"]);
        echo \html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
        echo \html_writer::input_hidden_params($this->baseurl);

        $this->display_questions($questions);

        $this->display_bottom_pagination($OUTPUT->render($pagingbar), $totalnumber, $perpage, $pageurl);

        //$this->display_bottom_controls($catcontext);

        echo \html_writer::end_tag('fieldset');
        echo \html_writer::end_tag('form');
    }

}
