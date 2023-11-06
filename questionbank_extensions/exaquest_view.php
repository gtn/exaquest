<?php

namespace core_question\local\bank;

defined('MOODLE_INTERNAL') || die();

global $CFG, $SESSION;

require_once($CFG->dirroot . '/question/editlib.php');

require_once('change_status.php');
require_once('plugin_feature.php');
require_once('edit_action_column_exaquest.php');
require_once('filters/exaquest_filters.php');
require_once('filters/exaquest_questioncategoryfilter.php');
require_once('delete_action_column_exaquest.php');
require_once('history_action_column_exaquest.php');
require_once('exaquest_category_condition.php');
require_once('question_id_column.php');
require_once('set_fragenersteller_column.php');
require_once('owner_column.php');
require_once('last_changed_column.php');
require_once('status_column.php');
require_once('question_name_idnumber_tags_column_exaquest.php');



use core_plugin_manager;
use core_question\local\bank\condition;
use qbank_columnsortorder\column_manager;
use qbank_editquestion\editquestion_helper;
use qbank_managecategories\helper;
use qbank_questiontodescriptor;
use qbank_setfragenersteller\set_fragenersteller_column;

use qbank_editquestion\output\add_new_question;
/**
 * main exaquest view for questionbank, this one is also derived by other views
 *
 * @package    exaquest_view
 * @copyright  2022 fabio <>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class exaquest_view extends view
{


    public function __construct($contexts, $pageurl, $course, $cm = null, $pagevars = null)
    {
        parent::__construct($contexts, $pageurl, $course, $cm, $pagevars);


    }


    /**
     * Get the list of qbank plugins with available objects for features.
     *
     * @return array
     */
    protected function get_question_bank_plugins(): array
    {
        $questionbankclasscolumns = [];
        $newpluginclasscolumns = [];
        //edited:
        //Commenting out $corequestionbankcolumns removes them from the questionbank table
        $corequestionbankcolumns = [
            'checkbox_column',
            'question_type_column',
            'question_name_idnumber_tags_column',
            'edit_menu_column',
            'edit_action_column',
            //'copy_action_column',
            'tags_action_column',
            'preview_action_column',
            'history_action_column',
            'delete_action_column',
            //'export_xml_action_column',
            // 'question_status_column',
            //'version_number_column',
            //'creator_name_column',
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



        // Mitigate the error in case of any regression.
        foreach ($questionbankclasscolumns as $shortname => $questionbankclasscolumn) {
            if (empty($questionbankclasscolumn)) {
                unset($questionbankclasscolumns[$shortname]);
            }
        }

        // this is where you can add new columns to the current questionbank of this view
        // it also needs to be added in plugin_feature
        $specialpluginentrypointobject = new \qbank_openquestionforreview\plugin_feature();
        $specialplugincolumnobjects = $specialpluginentrypointobject->get_question_columns($this);
        // TODO: the order of this changes the order in which extrajoins are added, which determins if the query works or not
        // e.g. there would be a join that uses qbe.id, before the qbe table has been joined. check out how to solve this in a useful manner.
        $questionbankclasscolumns["edit_action_column"] = $specialplugincolumnobjects[1];
        $questionbankclasscolumns["delete_action_column"] = $specialplugincolumnobjects[2];
        $questionbankclasscolumns["history_action_column"] = $specialplugincolumnobjects[3];
        $questionbankclasscolumns["question_name_idnumber_tags_column"] = $specialplugincolumnobjects[12];
        $questionbankclasscolumns["set_fragenersteller_column"] = $specialplugincolumnobjects[13];
        $questionbankclasscolumns["question_id_column"] = $specialplugincolumnobjects[8];
        $questionbankclasscolumns["owner_column"] = $specialplugincolumnobjects[9];
        $questionbankclasscolumns["last_changed_column"] = $specialplugincolumnobjects[10];
        $questionbankclasscolumns["status_column"] = $specialplugincolumnobjects[11];
        $questionbankclasscolumns["change_status"] = $specialplugincolumnobjects[0];


        return $questionbankclasscolumns;
    }

    // The display function is called by the questionbank.php file, it creates the whole view of the questionbank
    public function display(): void
    {
        global $SESSION;

        // retrieving all the pagevars, that got initialized in questionbank.php, it also utilizes $SESSION to save the state when changing pages
        $page = $this->pagevars['qpage'];
        $perpage = $this->pagevars['qperpage'];
        $cat = $this->pagevars['cat'];
        $recurse = $this->pagevars['recurse'];
        $showhidden = $this->pagevars['showhidden'];
        $showquestiontext = $this->pagevars['qbshowtext'];
        $tagids = [];
        $filterstatus = $this->pagevars['filterstatus'];
        $fragencharakter = array_key_exists('fragencharakter', $this->pagevars) ? $this->pagevars['fragencharakter'] : null;
        $klassifikation = array_key_exists('klassifikation', $this->pagevars) ? $this->pagevars['klassifikation'] : null;
        $fragefach = array_key_exists('fragefach', $this->pagevars) ? $this->pagevars['fragefach'] : null;
        $lehrinhalt = array_key_exists('lehrinhalt', $this->pagevars) ? $this->pagevars['lehrinhalt'] : null;


        if (!empty($this->pagevars['qtagids'])) {
            $tagids = $this->pagevars['qtagids'];
        }

        echo \html_writer::start_div('questionbankwindow boxwidthwide boxaligncenter');

        $editcontexts = $this->contexts->having_one_edit_tab_cap($tabname);


        // Show the filters and search options.
        //$this->wanted_filters($cat, $tagids, $showhidden, $recurse, $editcontexts, $showquestiontext, $filterstatus, $fragencharakter, $klassifikation, $fragefach, $lehrinhalt);
        $this->wanted_filters();

        // Continues with list of questions.
        //$this->display_question_list($this->baseurl, $cat, null, $page, $perpage,
        //    $this->contexts->having_cap('moodle/question:add'));
        $this->pagevars['pageurl'] = $this->baseurl;

        $this->display_question_list();
        echo \html_writer::end_div();

    }

    /**
     * The filters for the question bank.
     *
     * @param string $cat 'categoryid,contextid'
     * @param array $tagids current list of selected tags
     * @param bool $showhidden whether deleted questions should be displayed
     * @param int $recurse Whether to include subcategories
     * @param array $editcontexts parent contexts
     * @param bool $showquestiontext whether the text of each question should be shown in the list
     */
    public function wanted_filters(): void
    {
        global $CFG;
        list(, $contextid) = explode(',', $this->pagevars['cat']);
        $catcontext = \context::instance_by_id($contextid);
        $thiscontext = $this->get_most_specific_context();

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
                        new \core_question\local\bank\tag_condition([$catcontext, $thiscontext], $this->pagevars['tagids']));
                }

                //array_unshift($this->searchconditions, new \core_question\local\bank\hidden_condition(!$showhidden));
                // these are used to add extra filters to this view
                array_unshift($this->searchconditions, new \core_question\local\bank\exaquest_filters($this->pagevars['filterstatus']));
                array_unshift($this->searchconditions, new \core_question\local\bank\exaquest_filters($this->pagevars['filterstatus']));
                array_unshift($this->searchconditions, new \core_question\local\bank\exaquest_questioncategoryfilter($this->pagevars['fragencharakter'], $this->pagevars['klassifikation'], $this->pagevars['fragefach'], $this->pagevars['lehrinhalt']));
                array_unshift($this->searchconditions, new \core_question\local\bank\exaquest_category_condition(
                    $this->pagevars['cat'], $this->pagevars['recurse'], $this->pagevars['editcontexts'], $this->baseurl, $this->course));
            }
        }
        $this->display_options_form($this->pagevars['showquestiontext']);
    }

    protected function display_options_form($showquestiontext): void
    {
        global $PAGE;

        // The html will be refactored in the filter feature implementation.
        echo \html_writer::start_tag('form', ['method' => 'get',
            'action' => new \moodle_url($this->baseurl), 'id' => 'displayoptions']);
        echo \html_writer::start_div();

        $excludes = ['recurse', 'showhidden', 'qbshowtext'];
        // If the URL contains any tags then we need to prevent them
        // being added to the form as hidden elements because the tags
        // are managed separately.
        if ($this->baseurl->param('qtagids[0]')) {
            $index = 0;
            while ($this->baseurl->param("qtagids[{$index}]")) {
                $excludes[] = "qtagids[{$index}]";
                $index++;
            }
        }
        echo \html_writer::input_hidden_params($this->baseurl, $excludes);

        $advancedsearch = [];

        foreach ($this->searchconditions as $searchcondition) {
            if ($searchcondition->display_options_adv()) {
                $advancedsearch[] = $searchcondition;
            }
            echo $searchcondition->display_options();
        }
        //$this->display_showtext_checkbox($showquestiontext);
        if (!empty($advancedsearch)) {
            $this->display_advanced_search_form($advancedsearch);
        }

        $go = \html_writer::empty_tag('input', ['type' => 'submit', 'value' => get_string('go')]);
        echo \html_writer::tag('noscript', \html_writer::div($go), ['class' => 'inline']);
        echo \html_writer::end_div();
        echo \html_writer::end_tag('form');
        $PAGE->requires->yui_module('moodle-question-searchform', 'M.question.searchform.init');
    }


    /**
     * Prints the table of questions in a category with interactions
     *
     * @param \moodle_url $pageurl The URL to reload this page.
     * @param string $categoryandcontext 'categoryID,contextID'.
     * @param int $recurse Whether to include subcategories.
     * @param int $page The number of the page to be displayed
     * @param int $perpage Number of questions to show per page
     * @param array $addcontexts contexts where the user is allowed to add new questions.
     */
    //protected function display_question_list($pageurl, $categoryandcontext, $recurse = 1, $page = 0,
    //        $perpage = 100, $addcontexts = []): void
    public function display_question_list(): void
    {
        global $OUTPUT, $DB;
        // This function can be moderately slow with large question counts and may time out.
        // We probably do not want to raise it to unlimited, so randomly picking 5 minutes.
        // Note: We do not call this in the loop because quiz ob_ captures this function (see raise() PHP doc).
        \core_php_time_limit::raise(300);
        //edit:
        /*$editcontexts = $this->contexts->having_one_edit_tab_cap('editq'); // tabname jsut copied for convinience bacasue it won't change
        // If it is required to create sub question categories i have to iterate over it and find the context_coursecat
        if($editcontexts[1] instanceof \context_coursecat){
            // gets the parent course category for this course
            $category = end($DB->get_records('question_categories',['contextid' => $editcontexts[1]->id])); // end gives me the last element
        } else {
            throw new \coding_exception('No parent course category found');*/
        $category = $this->get_current_category($this->pagevars['cat']);
        //}

        //list($categoryid, $contextid) = explode(',', $this->pagevars['categoryandcontext']);
        list($categoryid, $contextid) = explode(',', $this->pagevars['cat']); // TODO: why is it in cat? is this really the correct one?
        $catcontext = \context::instance_by_id($contextid);

        $canadd = has_capability('moodle/question:add', $catcontext);

        $this->create_new_question_form($category, $canadd);

        $this->build_query();


        $totalnumber = $this->get_question_count();

        if ($totalnumber == 0) {
            return;
        }
        $questionsrs = $this->load_page_questions($this->pagevars['page'], $this->pagevars['perpage']);
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

        $pageingurl = new \moodle_url($this->baseurl, $this->baseurl->params());
        $pagingbar = new \paging_bar($totalnumber, $this->pagevars['page'], $this->pagevars['perpage'], $pageingurl);
        $pagingbar->pagevar = 'qpage';

        $this->display_top_pagnation($OUTPUT->render($pagingbar));

        // This html will be refactored in the bulk actions implementation.
        echo \html_writer::start_tag('form', ['action' => $pageurl, 'method' => 'post', 'id' => 'questionsubmit']);
        echo \html_writer::start_tag('fieldset', ['class' => 'invisiblefieldset', 'style' => "display: block;"]);
        echo \html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
        echo \html_writer::input_hidden_params($this->baseurl);

        $this->display_questions($questions);

        $this->display_bottom_pagination($OUTPUT->render($pagingbar), $totalnumber, $this->pagevars['perpage'], $pageurl);

        $this->display_bottom_controls($catcontext);

        echo \html_writer::end_tag('fieldset');
        echo \html_writer::end_tag('form');
    }


    /**
     * Get the number of questions.
     * @return int
     */
    public function get_question_count(): int
    {
        global $DB;
        return $DB->count_records_sql($this->countsql, $this->sqlparams);
    }


    /**
     * Create a new question form in dashboard.
     *
     * @param false|mixed|\stdClass $category
     * @param bool $canadd
     */
    function create_new_question_form_dashboard($category, $canadd): void
    {
        $this->create_new_question_form($category, $canadd);
    }

    function get_current_category_dashboard($categoryandcontext)
    {
        global $DB;

        $editcontexts = $this->contexts->having_one_edit_tab_cap('editq'); // tabname just copied for convinience bacause it won't change


        // If it is required to create sub question categories i have to iterate over it and find the context_coursecat
        if ($editcontexts[1] instanceof \context_coursecat) {
            // gets the parent course category for this course
            $categories = $DB->get_records('question_categories', ['contextid' => $editcontexts[1]->id]);
            $category = end($categories); // end gives me the last element
        } else {
            throw new \coding_exception('No parent course category found');
            $category = $this->get_current_category($categoryandcontext);
        }
        return $category;
    }

    /**
     * Create a new question form.
     *
     * @param false|mixed|\stdClass $category
     * @param bool $canadd
     */
    protected function create_new_question_form($category, $canadd): void
    {
        global $COURSE, $OUTPUT;
        if (\core\plugininfo\qbank::is_plugin_enabled('qbank_editquestion') && has_capability('block/exaquest:createquestion', \context_course::instance($COURSE->id))) {
            //echo editquestion_helper::create_new_question_button($category->id,
            //    $this->requiredcolumns['edit_action_column_exaquest']->editquestionurl->params(), $canadd);

            // TODO: add the add new question button here. Works differently since moodle 4.3
            //$OUTPUT->render(new add_new_question($category->id, $this->requiredcolumns['edit_action_column_exaquest']->editquestionurl->params(), $canadd));
        }
    }

    /**
     * Create the SQL query to retrieve the indicated questions, based on
     * \core_question\local\bank\condition filters.
     */
    protected function build_query(): void
    {
        // Get the required tables and fields.
        $joins = [];
        // add here extra fields to get from the sql query, but be careful these can cause problems if not the right tables are not joind and the first one in this field determains the indexing of the return array
        $fields = [ 'qbe.id as questionbankentryid','qv.status', 'qc.id as categoryid', 'qv.version', 'qv.id as versionid'];
        if (!empty($this->requiredcolumns)) {
            foreach ($this->requiredcolumns as $column) {
                $extrajoins = $column->get_extra_joins();
                foreach ($extrajoins as $prefix => $join) {
                    if (isset($joins[$prefix]) && $joins[$prefix] != $join) {
                        throw new \coding_exception('Join ' . $join . ' conflicts with previous join ' . $joins[$prefix]);
                    }
                    $joins[$prefix] = $join;
                }
                $fields = array_merge($fields, $column->get_required_fields());
            }
        }
        $fields = array_unique($fields);

        // flip the order of $joins around
        //$joins = array_reverse($joins);


        // Build the order by clause.
        $sorts = [];
        foreach ($this->sort as $sort => $order) {
            list($colname, $subsort) = $this->parse_subsort($sort);
            $sorts[] = $this->requiredcolumns[$colname]->sort_expression($order < 0, $subsort);
        }

        // Build the where clause.
        $latestversion = 'qv.version = (SELECT MAX(v.version)
                                          FROM {question_versions} v
                                          JOIN {question_bank_entries} be
                                            ON be.id = v.questionbankentryid
                                         WHERE be.id = qbe.id)';
        $tests = ['q.parent = 0', $latestversion];
        $this->sqlparams = [];
        foreach ($this->searchconditions as $searchcondition) {
            if ($searchcondition->where()) {
                $tests[] = '((' . $searchcondition->where() . '))';
            }
            if ($searchcondition->params()) {
                $this->sqlparams = array_merge($this->sqlparams, $searchcondition->params());
            }
        }
        // Build the SQL.
        //here it adds all joins together, including the extra_joins from other columns
        $sql = ' FROM {question} q ' . implode(' ', $joins);
        // adds all where clauses, including the onse that were defined in out filters
        $sql .= ' WHERE ' . implode(' AND ', $tests);
        // important note: The DISTINCT is necessary so the questionbank counts all questions correctly
        $this->countsql = 'SELECT count(DISTINCT qbe.id)' . $sql;
        // important note: The DISTINCT is necessary so the questionbank does not leave out any questions
        $this->loadsql = 'SELECT DISTINCT ' . implode(', ', $fields) . $sql . ' ORDER BY ' . implode(', ', $sorts);
    }

    //protected function load_page_questions($page, $perpage): \moodle_recordset
    //{
    //    // here the actual query is called
    //    global $DB;
    //    $questions = $DB->get_recordset_sql($this->loadsql, $this->sqlparams, $page * $perpage, $perpage);
    //    if (empty($questions)) {
    //        $questions->close();
    //        // No questions on this page. Reset to page 0.
    //        $questions = $DB->get_recordset_sql($this->loadsql, $this->sqlparams, 0, $perpage);
    //    }
    //    return $questions;
    //}

    /**
     * Display the controls at the bottom of the list of questions.
     *
     * @param \context $catcontext The context of the category being displayed.
     * unchanged, maybe will change in the future
     */
    protected function display_bottom_controls(\context $catcontext): void {
        // use the parent function
        //parent::display_bottom_controls($catcontext);

        $caneditall = has_capability('moodle/question:editall', $catcontext);
        $canuseall = has_capability('moodle/question:useall', $catcontext);
        $canmoveall = has_capability('moodle/question:moveall', $catcontext);
        if ($caneditall || $canmoveall || $canuseall) {
            global $PAGE;
            $bulkactiondatas = [];
            $params = $this->base_url()->params();
            $params['returnurl'] = $this->base_url();
            foreach ($this->bulkactions as $key => $action) {
                // Check capabilities.
                $capcount = 0;
                foreach ($action['capabilities'] as $capability) {
                    if (has_capability($capability, $catcontext)) {
                        $capcount ++;
                    }
                }
                // At least one cap need to be there.
                if ($capcount === 0) {
                    unset($this->bulkactions[$key]);
                    continue;
                }
                $actiondata = new \stdClass();
                $actiondata->actionname = $action['title'];
                $actiondata->actionkey = $key;
                $actiondata->actionurl = new \moodle_url($action['url'], $params);
                $bulkactiondata[] = $actiondata;

                $bulkactiondatas ['bulkactionitems'] = $bulkactiondata;
            }
            // We dont need to show this section if none of the plugins are enabled.
            if (!empty($bulkactiondatas)) {
                echo $PAGE->get_renderer('core_question', 'bank')->render_bulk_actions_ui($bulkactiondatas);
            }
        }
    }

    /**
     * Initialize bulk actions.
     * unchanged, maybe will change in the future
     */
    protected function init_bulk_actions(): void {
        $plugins = \core_component::get_plugin_list_with_class('qbank', 'plugin_feature', 'plugin_feature.php');
        foreach ($plugins as $componentname => $plugin) {
            if (!\core\plugininfo\qbank::is_plugin_enabled($componentname)) {
                continue;
            }

            $pluginentrypoint = new $plugin();
            $bulkactions = $pluginentrypoint->get_bulk_actions();
            if (!is_array($bulkactions)) {
                debugging("The method {$componentname}::get_bulk_actions() must return an " .
                    "array of bulk actions instead of a single bulk action. " .
                    "Please update your implementation of get_bulk_actions() to return an array. " .
                    "Check out the qbank_bulkmove plugin for a working example.", DEBUG_DEVELOPER);
                $bulkactions = [$bulkactions];
            }

            foreach ($bulkactions as $bulkactionobject) {
                $this->bulkactions[$bulkactionobject->get_key()] = [
                    'title' => $bulkactionobject->get_bulk_action_title(),
                    'url' => $bulkactionobject->get_bulk_action_url(),
                    'capabilities' => $bulkactionobject->get_bulk_action_capabilities()
                ];
            }

        }
    }

}
