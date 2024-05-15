<?php

namespace block_exaquest\tables;

use qbank_openquestionforreview\change_status;

class questionbank_table extends \local_table_sql\table_sql {
    private $filterstatus_condition;

    public function __construct(
        protected int $categoryid,
    ) {
        $filterstatus = optional_param('filterstatus', -1, PARAM_INT);
        $this->filterstatus_condition = new \core_question\bank\search\exaquest_filters($filterstatus);

        parent::__construct();
    }

    protected function define_table_configs() {

        $cols = [
            'id' => 'Id',
            'qtype' => 'T',
            'name' => 'Name',
            'ucname' => get_string('ownername', 'block_exaquest'),
            'comments' => 'Kommentare',
            'categories' => 'Kategorien',
            'timecreated' => get_string('lastchanged', 'block_exaquest'),
            'state_text' => 'Status',
            'actions' => 'Aktionen',
        ];

        $this->set_table_columns($cols);

        $this->set_column_options('timecreated', data_type: static::PARAM_TIMESTAMP);
        $this->set_column_options('comments', no_sorting: true, no_filter: true);

        $this->no_sorting('actions');
        $this->no_filter('actions');
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
            SELECT DISTINCT qbe.id as questionbankentryid, qbe.questioncategoryid, qv.status, qc.id as categoryid, qv.version, qv.id as versionid, qrevisea.reviserid as reviserid, q.id, q.qtype, q.name
            , qbe.idnumber, q.createdby, qc.contextid, uc.firstnamephonetic AS creatorfirstnamephonetic, uc.lastnamephonetic AS creatorlastnamephonetic, uc.middlename AS creatormiddlename, uc.alternatename AS creatoralternatename, uc.firstname AS creatorfirstname, uc.lastname AS creatorlastname, q.timecreated
            , qbe.ownerid
            , CONCAT(uc.lastname, " ", uc.firstname) as ucname
            , uc.id as ucid
            , ' . $categories . ' AS categories
            , ' . $status_sql . ' AS state_text
            , qs.status AS teststatus
            FROM {question} q
            LEFT JOIN {customfield_data} cfd ON q.id = cfd.instanceid
            JOIN {question_versions} qv ON qv.questionid = q.id
            JOIN {question_bank_entries} qbe on qbe.id = qv.questionbankentryid
            JOIN {question_categories} qc ON qc.id = qbe.questioncategoryid
            LEFT JOIN {user} uc ON uc.id = q.createdby
            JOIN {block_exaquestquestionstatus} qs ON qbe.id = qs.questionbankentryid
            LEFT JOIN {block_exaquestreviewassign} qra ON qbe.id = qra.questionbankentryid
            LEFT JOIN {block_exaquestreviseassign} qrevisea ON qbe.id = qrevisea.questionbankentryid
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

        // $this->set_row_actions_display_as_menu(true);
        // $this->add_row_action(
        //     id: 'lock_question',
        //     label: get_string('lock_question', 'block_exaquest'),
        // );
        // $this->add_row_action(
        //     id: 'revise_question',
        //     label: get_string('revise_question', 'block_exaquest'),
        // );

        $this->enable_row_selection();

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
                'class' => 'comment-count',
                'onclick' => 'return comment_count_popup(event);',
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

    // protected function __get_row_actions(object $row, array $row_actions): ?array {
    //     // $row_actions[1]->disabled = true;
    //     // return $row_actions;
    // }

    public function col_actions($question) {
        ob_start();
        echo '<div data-question="' . htmlspecialchars(json_encode([
                // hack:
                'questionbankentryid' => $question->questionbankentryid,
                'questionid' => $question->id,
            ])) . '">';
        change_status::display_content_static($question);
        echo '</div>';
        return ob_get_clean();
    }

    public function wrap_html_start() {
        global $COURSE;

        echo $this->filterstatus_condition->display_options_adv();

        ?>
        <script>
            function comment_count_popup(e) {
                e.preventDefault();
                e.stopPropagation();

                require(['qbank_comment/comment'], function (amd) {
                    var id = Math.random();
                    var $clone = $(e.target).clone();
                    $clone.attr('data-tmp', id);
                    $clone.attr('onclick', '');
                    $clone.appendTo('body');

                    amd.init('[data-tmp="' + id + '"]');

                    var event = new Event('click');
                    $clone[0].dispatchEvent(event);

                    $clone.remove();
                });
            }

            $(document).on('change', 'select.searchoptions', function () {
                document.location.href = document.location.href.replace(/([?&])filterstatus=[^&]*/, '$1').replace(/&+$/, '') + '&filterstatus=' + $(this).val();
            });

            $(document).on('click', '.exaquest-changequestionstatus', function (e) {
                //let changestatus_value = $(".changestatus<?php //echo $question->questionbankentryid; ?>//").val();
                let changestatus_value = e.currentTarget.value;
                /*
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
                */

                var questionData = JSON.parse($(this).closest('[data-question]').attr('data-question'));

                var data = Object.assign(<?php echo json_encode([
                    'courseid' => $COURSE->id,
                    'sesskey' => sesskey(),
                ])?>, {
                    action: $(this).val(),
                    questionbankentryid: questionData.questionbankentryid,
                    questionid: questionData.questionid,
                });
                e.preventDefault();
                var ajax = $.ajax({
                    method: "POST",
                    url: "ajax.php",
                    data: data
                }).done(function () {
                    //console.log(data.action, 'ret', ret);
                    document.dispatchEvent(new CustomEvent("local_table_sql:reload"));
                }).fail(function (ret) {
                    var errorMsg = '';
                    if (ret.responseText[0] == '<') {
                        // html
                        errorMsg = $(ret.responseText).find('.errormessage').text();
                    }
                    console.log("Error in action '" + data.action + "'", errorMsg, 'ret', ret);
                });
            });

        </script>
        <?php
    }
}
