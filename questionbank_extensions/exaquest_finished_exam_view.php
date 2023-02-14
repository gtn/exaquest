<?php

namespace core_question\local\bank;

use core_question\local\bank\exaquest_view;

require_once('exaquest_exam_view.php');
require_once('plugin_feature.php');
require_once('filters/in_quiz_filter.php');
class exaquest_finished_exam_view extends exaquest_exam_view
{
    public function __construct($contexts, $pageurl, $course, $cm = null) {
        parent::__construct($contexts, $pageurl, $course, $cm);
    }


    /**
     * Display the header element for the question bank.
     */
    protected function display_question_bank_header(): void
    {
        global $OUTPUT, $DB;

        $quizid = optional_param('quizid', null, PARAM_INT);

        if ($quizid != null) {
            $quizname = $DB->get_field("quiz", "name", array("id" => $quizid));
        }

        $coustomfieldvalues = $DB->get_records_sql("SELECT *
                              FROM {quiz_slots} qusl
                              JOIN {question_references} qref ON qusl.id = qref.itemid
                              JOIN {question_versions} qv ON qv.questionbankentryid = qref.questionbankentryid
                              JOIN {customfield_data} cfd ON cfd.instanceid = qv.questionid
                              WHERE qv.version = (SELECT Max(v.version)
                                                    FROM   {question_versions} v
                                                    JOIN {question_bank_entries} be
                                                    ON be.id = v.questionbankentryid
                                                    WHERE  be.id = qref.questionbankentryid) AND qusl.quizid=" . $quizid);

        $categoryoptionidarray = array();
        foreach ($coustomfieldvalues as $categoryoptionid) {
            $mrg = explode(',', $categoryoptionid->value);
            $categoryoptionidarray = array_merge($categoryoptionidarray, $mrg);
        }
        $categoryoptionidcount = array();
        foreach ($categoryoptionidarray as $categoryoptionid) {
            $categoryoptionidcount[$categoryoptionid] += 1;
        }
        $categoryoptionidkeys = array();
        foreach ($categoryoptionidcount as $key => $categoryoptionidcnt) {
            $categoryoptionidkeys[] = $key;
        }


        $query = "('" . implode("','", $categoryoptionidkeys) . "')";

        $categoryoptions = $DB->get_records_sql("SELECT eqc.id, eqc.categoryname, eqc.categorytype
                                    FROM {block_exaquestcategories} eqc
                                   WHERE eqc.id IN " . $query);

        $options = array();
        foreach ($categoryoptions as $categoryoption) {
            $options[$categoryoption->categorytype][$categoryoption->id] = $categoryoption->categoryname;
        }

        $content = array('','','','');
        foreach ($options as $key => $option){
            foreach ($option as $keyy => $name) {
                $content[$key] .= '<div class="col-lg-12"><span>' . $name . ': ' . $categoryoptionidcount[$keyy] . '</span></div>';
            }
        }
        echo $OUTPUT->heading(get_string('questionbank_selected_quiz', 'block_exaquest').''. $quizname, 2);

        $html = '<div class="container-fluid">
                    <div class="row">
                         <div class="col-lg-3">
                         <div class="col-lg-12 border-bottom"><h6>Fragencharakter</h6></div>
                            '.$content[0].'
                        </div>
                        <div class="col-lg-3">
                        <div class="col-lg-12 border-bottom"><h6>Klassifikation</h6></div>
                            '.$content[1].'
                        </div>
                        <div class="col-lg-3">
                        <div class="col-lg-12 border-bottom"><h6>Fragefach</h6></div>
                            '.$content[2].'
                        </div>
                        <div class="col-lg-3">
                        <div class="col-lg-12 border-bottom"><h6>Lerninhalt</h6></div>
                            '.$content[3].'
                        </div>
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

    public function wanted_filters($cat, $tagids, $showhidden, $recurse, $editcontexts, $showquestiontext, $filterstatus=0, $fragencharakter=-1, $klassifikation=-1, $fragefach=-1,  $lehrinhalt=-1): void {
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
                    //   array_unshift($this->searchconditions,
                    //     new \core_question\bank\search\tag_condition([$catcontext, $thiscontext], $tagids));
                }

                //array_unshift($this->searchconditions, new \core_question\bank\search\hidden_condition(!$showhidden));
                array_unshift($this->searchconditions, new \core_question\bank\search\in_quiz_filter($filterstatus));
                //array_unshift($this->searchconditions, new \core_question\bank\search\category_condition_exaquest($cat, $recurse, $editcontexts, $this->baseurl, $this->course));
            }
        }
        $this->display_options_form($showquestiontext);
    }
}