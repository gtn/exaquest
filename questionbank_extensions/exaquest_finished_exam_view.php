<?php

namespace core_question\local\bank;

use core_question\local\bank\exaquest_view;
use qbank_columnsortorder\column_manager;
use core_plugin_manager;

use moodle_url;

require_once('exaquest_exam_view.php');
require_once('plugin_feature.php');
require_once('filters/in_quiz_filter.php');
require_once('filters/only_released_questions.php');

/**
 * this class is for the exam view to view already added questions in an exam
 *
 * @package    exaquest_finished_exam
 * @copyright  2022 fabio <>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class exaquest_finished_exam_view extends exaquest_exam_view {
    public function __construct($contexts, $pageurl, $course, $cm = null) {
        parent::__construct($contexts, $pageurl, $course, $cm);
    }

    /**
     * Display the header element for the question bank.
     */
    protected function display_question_bank_header(): void {
        global $OUTPUT, $DB, $COURSE;

        $quizid = optional_param('quizid', null, PARAM_INT);

        if ($quizid != null) {
            $quizname = $DB->get_field("quiz", "name", array("id" => $quizid));
        }
        $categoryoptionidcount = block_exaquest_get_category_question_count($quizid);
        $categoryoptionidkeys = array_keys($categoryoptionidcount);
        $categoryoptions = block_exaquest_get_category_names_by_ids($categoryoptionidkeys);
        $options = [];
        foreach ($categoryoptions as $categoryoption) {
            $options[$categoryoption->categorytype][$categoryoption->id] = $categoryoption->categoryname;
        }
        $categorys_required_counts = block_exaquest_get_fragefaecher_by_courseid_and_quizid($COURSE->id, $quizid);

        // if there are no questions of a category, this category will now show up in the options. ==> add the required fragefaecher so they always show up
        foreach ($categorys_required_counts as $cat_required) {
            // first check if it is even an array, or if it is completely empty still
            // then check, if for THIS specific cat there is already an entry in the array or not
            if (!is_array($options[BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH]) ||
                    !array_key_exists($cat_required->id, $options[BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH])) {
                $options[BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH][$cat_required->id] = $cat_required->categoryname;
            }
        }

        $content = array('', '', '', '');
        foreach ($options as $key => $option) {
            foreach ($option as $categoryid => $name) {
                if ($key == BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH) {
                    $qcount = $categoryoptionidcount[$categoryid] ?: 0;
                    $content[$key] .= '<div class="col-lg-12"><span>' . $name . ': ' . $qcount .
                            ' von ' . $categorys_required_counts[$categoryid]->questioncount . '</span></div>';
                } else {
                    $content[$key] .= '<div class="col-lg-12"><span>' . $name . ': ' . $categoryoptionidcount[$categoryid] .
                            '</span></div>';
                }
            }
        }
        echo $OUTPUT->heading(get_string('questionbank_selected_quiz', 'block_exaquest') . '' . $quizname, 2);

        // get the questioncounts from questionqcount table for this quiz
        $html = '<div class="container-fluid">
                    <div class="row">
                         <div class="col-lg-3">
                         <div class="col-lg-12 border-bottom"><h6>Fragencharakter</h6></div>
                            ' . $content[0] . '
                        </div>
                        <div class="col-lg-3">
                        <div class="col-lg-12 border-bottom"><h6>Klassifikation</h6></div>
                            ' . $content[1] . '
                        </div>
                        <div class="col-lg-3">
                        <div class="col-lg-12 border-bottom"><h6>Fragefach</h6></div>
                            ' . $content[2] . '
                        </div>
                        <div class="col-lg-3">
                        <div class="col-lg-12 border-bottom"><h6>Lerninhalt</h6></div>
                            ' . $content[3] . '
                        </div>
                    </div>
                </div>';
        // add a button that links to exaquest/similarity_comparison.php with questioncategoryid, courseid and examid
        $catAndCont = get_question_category_and_context_of_course();
        $courseid = $COURSE->id;
        $url = new moodle_url('/blocks/exaquest/similarity_comparison.php',
                array("courseid" => $courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1], "examid" => $quizid));
        $html .= '<br>';
        $html .= '<div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="' . $url . '" class="btn btn-primary">Ähnlichkeitsvergleich</a>
                        </div>
                    </div>';
        echo "<br/>";
        echo $html;
        echo "<br/>";
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
                'change_status',
                'add_to_quiz',
                'usage_check_column'

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
                'comment_count_column',
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
        $questionbankclasscolumns["assign_to_revise_from_quiz"] = $specialplugincolumnobjects[14];

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
                    //array_unshift($this->searchconditions,
                    //new \core_question\bank\search\tag_condition([$catcontext, $thiscontext], $tagids));
                }

                //array_unshift($this->searchconditions, new \core_question\bank\search\hidden_condition(!$showhidden));
                array_unshift($this->searchconditions, new \core_question\bank\search\in_quiz_filter($filterstatus));
                //                array_unshift($this->searchconditions, new \core_question\bank\search\only_released_questions());
                array_unshift($this->searchconditions, new \core_question\bank\search\exaquest_category_condition(
                        $cat, $recurse, $editcontexts, $this->baseurl, $this->course));

            }
        }
        $this->display_options_form($showquestiontext);
    }
}
