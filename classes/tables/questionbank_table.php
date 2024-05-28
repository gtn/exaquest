<?php

namespace block_exaquest\tables;

use core_question\local\bank\menuable_action;
use core_question\local\bank\view;
use qbank_openquestionforreview\change_status;
use qbank_openquestionforreview\set_fragenersteller_column;

class questionbank_table extends \local_table_sql\table_sql {
    private $filterstatus_condition;

    public function __construct(
        protected view $qbank,
        protected int $categoryid,
    ) {
        $filterstatus = optional_param('filterstatus', 0, PARAM_INT);
        // 0 instead of -1 as default value. because 0 is the default value in public function __construct($filterstatus = 0) {
        // if it is not 0, but -1, "if ($this->filterstatus == BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS) {" in exaquest_filters will not be true
        // TODO: for now every role has every filter, so 0 as default value is fine. If we ever change it, we need to take care of the problem, that 0 may not be available.

        $this->filterstatus_condition = new \core_question\bank\search\exaquest_filters($filterstatus);

        global $PAGE;
        $PAGE->requires->js('/blocks/exaquest/javascript/questionbank_table.js');

        parent::__construct();
    }

    protected function define_table_configs() {

        $cols = [
            'questionbankentryid' => 'Id',
            'qtype' => 'T',
            'name' => 'Name',
            'ownername' => get_string('ownername', 'block_exaquest'),
            'comments' => 'Kommentare',
            'categories' => 'Kategorien',
            'timecreated' => get_string('lastchanged', 'block_exaquest'),
            'state_text' => 'Status',
            'changestatus' => 'Status verändern',
        ];

        $this->set_table_columns($cols);

        $this->set_column_options('timecreated', data_type: static::PARAM_TIMESTAMP);
        $this->set_column_options('comments', no_sorting: true, no_filter: true);

        $this->no_sorting('changestatus');
        $this->no_filter('changestatus');

        // fürs css "td.changestatus" notwendig
        // $this->column_class('changestatus', 'changestatus');
        // $this->no_sorting('interface_or_widget');
        // $this->no_filter('interface_or_widget');

        $categories = $this->sql_aggregated_column_from_query(
            'block_exaquestcategories.categoryname',
            '{customfield_data} cfd JOIN {block_exaquestcategories} block_exaquestcategories ON q.id = cfd.instanceid AND find_in_set(block_exaquestcategories.id,cfd.value)'
        );

        $status_sql = $this->sql_case('qs.status', [
            BLOCK_EXAQUEST_QUESTIONSTATUS_IMPORTED => get_string("imported_question", "block_exaquest"),
            BLOCK_EXAQUEST_QUESTIONSTATUS_NEW => get_string("new_question", "block_exaquest"),
            BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE => get_string("to_revise", "block_exaquest"),
            BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS => get_string("to_assess", "block_exaquest"),
            BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE => get_string("formal_done", "block_exaquest"),
            BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE => get_string("fachlich_done", "block_exaquest"),
            BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED => get_string("finalised", "block_exaquest"),
            BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED => get_string("released", "block_exaquest"),
            BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED => get_string("locked", "block_exaquest"),
        ]);

        $this->set_sql_query('
            SELECT DISTINCT qbe.id as questionbankentryid, qbe.questioncategoryid, qv.status, qc.id as categoryid, qv.version, qv.id as versionid
            -- , qrevisea.reviserid as reviserid
            , q.id, q.qtype, q.name
            , qbe.idnumber, q.createdby, qc.contextid
            , qbe.ownerid
            , q.timecreated
            -- , uc.firstnamephonetic AS creatorfirstnamephonetic, uc.lastnamephonetic AS creatorlastnamephonetic, uc.middlename AS creatormiddlename, uc.alternatename AS creatoralternatename, uc.firstname AS creatorfirstname, uc.lastname AS creatorlastname
            -- , CONCAT(uc.lastname, " ", uc.firstname) as ucname
            -- , uc.id as ucid
            , CONCAT(owner.lastname, " ", owner.firstname) as ownername
            , ' . $categories . ' AS categories
            , ' . $status_sql . ' AS state_text
            , qs.status AS teststatus -- Spalte wird für die Statusbuttons benötigt
            FROM {question} q
            -- LEFT JOIN {customfield_data} cfd ON q.id = cfd.instanceid
            JOIN {question_versions} qv ON qv.questionid = q.id
            JOIN {question_bank_entries} qbe on qbe.id = qv.questionbankentryid
            JOIN {question_categories} qc ON qc.id = qbe.questioncategoryid
            -- LEFT JOIN {user} uc ON uc.id = q.createdby
            LEFT JOIN {user} owner ON owner.id = qbe.ownerid
            JOIN {block_exaquestquestionstatus} qs ON qbe.id = qs.questionbankentryid
            -- LEFT JOIN {block_exaquestreviewassign} qra ON qbe.id = qra.questionbankentryid
            -- LEFT JOIN {block_exaquestreviseassign} qrevisea ON qbe.id = qrevisea.questionbankentryid
            WHERE q.parent = 0 AND qv.version = (
                SELECT MAX(v.version)
                FROM {question_versions} v
                JOIN {question_bank_entries} be
                ON be.id = v.questionbankentryid
                WHERE be.id = qbe.id
            ) AND ((qbe.questioncategoryid = ?)) AND ((qs.status != 9)) AND ' .
            ($this->filterstatus_condition->where() ?: '1=1'), [
            $this->categoryid,
        ]);

        $this->set_row_actions_display_as_menu(true);

        $this->add_row_action(
            id: 'dummy',
            disabled: true,
            label: 'keine Aktionen',
        );
        // $this->add_row_action(
        //     id: 'revise_question',
        //     label: get_string('revise_question', 'block_exaquest'),
        // );

        // $this->enable_row_selection();

        $this->sortable(true, 'timecreated', SORT_DESC);
    }

    public function col_qtype($question) {
        return print_question_icon($question);
    }

    public function col_comments($question) {
        // logik von comment_count_column
        global $DB;

        $syscontext = \context_system::instance();
        $args = [
            'component' => 'qbank_comment',
            'commentarea' => 'question',
            'itemid' => $question->id,
            'contextid' => $syscontext->id,
        ];
        $commentcount = $DB->count_records('comments', $args);
        $attributes = [];
        if (question_has_capability_on($question, 'comment')) {
            $target = 'questioncommentpreview_' . $question->id;
            // $datatarget = '[data-target="' . $target . '"]';
            // $PAGE->requires->js_call_amd('qbank_comment/comment', 'init', [$datatarget]);
            $attributes = [
                'href' => '#',
                'class' => 'comment-count-popup',
                'data-target' => $target,
                'data-questionid' => $question->id,
                'data-courseid' => optional_param('courseid', 0, PARAM_INT),
                'data-contextid' => $syscontext->id,
            ];
        }

        // needs to be wrapped in a div, because else table_sql will remove the link attributes!
        return '<div>' . \html_writer::tag('a', $commentcount, $attributes) . '</div>';
    }

    public function col_name($row) {
        global $COURSE, $PAGE;

        return $this->format_col_content($row->name,
            new \moodle_url('/question/bank/previewquestion/preview.php', [
                'id' => $row->id,
                'courseid' => $COURSE->id,
                // 'returnurl' => $PAGE->url->out_as_local_url(false),
                'returnurl' => '/blocks/exaquest/questbank.php?' . preg_replace('!^.*\?!', '', $_SERVER['HTTP_REFERER']),
            ]));
    }

    public function col_approval($row) {
        if ($row->approvalmaintainer) {
            return get_string('maintainer', 'local_eduportal');
        }
        if ($row->approvalpartner) {
            return get_string('partner', 'local_eduportal');
        }
    }

    protected function get_row_actions(object $row, array $row_actions): ?array {
        static $columns = null;

        if ($columns === null) {
            $specialpluginentrypointobject = new \qbank_openquestionforreview\plugin_feature();
            $specialplugincolumnobjects = $specialpluginentrypointobject->get_question_columns($this->qbank);

            // TODO: anders machen
            $questionbankclasscolumns = [];
            $questionbankclasscolumns["question_id_column"] = $specialplugincolumnobjects[8];
            $questionbankclasscolumns["owner_column"] = $specialplugincolumnobjects[9];
            $questionbankclasscolumns["last_changed_column"] = $specialplugincolumnobjects[10];
            $questionbankclasscolumns["status_column"] = $specialplugincolumnobjects[11];
            $questionbankclasscolumns["change_status"] = $specialplugincolumnobjects[0];
            $questionbankclasscolumns["edit_action_column"] = $specialplugincolumnobjects[1];
            $questionbankclasscolumns["delete_action_column"] = $specialplugincolumnobjects[2];
            $questionbankclasscolumns["history_action_column"] = $specialplugincolumnobjects[3];
            $questionbankclasscolumns["question_name_idnumber_tags_column"] = $specialplugincolumnobjects[12];
            $questionbankclasscolumns["set_fragenersteller_column"] = $specialplugincolumnobjects[13];

            // TODO: vielleicht so?
            // $questionbankclasscolumns[] = new \qbank_editquestion\edit_action_column_exaquest($this->qbank);

            // new change_status($qbank),
            // new \qbank_editquestion\edit_action_column_exaquest($qbank),
            // new \qbank_deletequestion\delete_action_column_exaquest($qbank),
            // new \qbank_history\history_action_column_exaquest($qbank),
            // new add_to_quiz($qbank),
            // new usage_check_column($qbank),
            // new category_options($qbank),
            // new remove_from_quiz($qbank),
            // new question_id_column($qbank),
            // new owner_column($qbank),
            // new last_changed_column($qbank),
            // new status_column($qbank),
            // new \qbank_viewquestionname\question_name_idnumber_tags_column_exaquest($qbank),
            // new set_fragenersteller_column($qbank),
            // new assign_to_revise_from_quiz($qbank),
            // new lock_from_quiz($qbank),


            $columns = array_filter($questionbankclasscolumns, function($column) {
                return $column instanceof menuable_action;
            });
        }

        $icon_system = \core\output\icon_system_fontawesome::instance();
        $icon_map = $icon_system->get_core_icon_map();

        $final_actions = [];
        /** @var menuable_action $column */
        foreach ($columns as $column) {
            $ret = $column->get_action_menu_link($row);
            if ($ret) {
                $onclick = $column instanceof set_fragenersteller_column
                    ? "show_change_owner_popup"
                    : '';


                $icon = @$icon_map[($ret->icon->component == 'moodle' ? 'core' : $ret->icon->component) . ':' . $ret->icon->pix];

                // fix broken icon
                if (!$icon && $column instanceof \qbank_history\history_action_column_exaquest) {
                    $icon = 'fa-clock-rotate-left';
                }

                $final_actions[] = [
                    'url' => $ret->url->out(false),
                    'label' => $ret->text,
                    'onclick' => $onclick,
                    'icon' => "icon fa $icon fa-fw",
                ];
            }
        }

        // return new actions, or "no actions" if no actions are available
        return $final_actions ?: $row_actions;
    }

    public function col_changestatus($question) {
        ob_start();
        echo '<div class="changestatus-button-container" data-question="' . htmlspecialchars(json_encode([
                // hack:
                'questionbankentryid' => $question->questionbankentryid,
                'questionid' => $question->id,
            ])) . '">';
        change_status::display_content_static($question);
        echo '</div>';
        return ob_get_clean();
    }

    public function wrap_html_start() {
        echo $this->filterstatus_condition->display_options_adv();
    }
}
