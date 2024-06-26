<?php
global $DB;

use qbank_openquestionforreview\change_status;

require __DIR__ . '/inc.php';

global $CFG, $COURSE, $PAGE, $OUTPUT, $SESSION;

require_once($CFG->dirroot . '/question/editlib.php');
require_once($CFG->dirroot . '/blocks/exaquest/classes/questionbank_extensions/exaquest_exam_view.php');

block_exaquest_init_js_css();

list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) =
    question_edit_setup('questions', '/blocks/exaquest/exam_questionbank.php');

$courseid = required_param('courseid', PARAM_INT);
$filterstatus = optional_param('filterstatus', 0, PARAM_INT);
$fragencharakter = optional_param('fragencharakter', -2, PARAM_INT);
$klassifikation = optional_param('klassifikation', -2, PARAM_INT);
$fragefach = optional_param('fragefach', -2, PARAM_INT);
$lehrinhalt = optional_param('lehrinhalt', -2, PARAM_INT);
$quizid = required_param('quizid', PARAM_INT);

require_login($courseid);
//require_capability('block/exaquest:viewcategorytab', context_course::instance($courseid));

// check the status of the quiz. If it is not BLOCK_EXAQUEST_QUIZSTATUS_CREATED or BLOCK_EXAQUEST_QUIZSTATUS_NEW or BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED it is not allowed to edit the questions
if ($quizid != null) {
    $quiz = $DB->get_record_sql('SELECT qs.status
        FROM {' . BLOCK_EXAQUEST_DB_QUIZSTATUS . '} qs
         WHERE qs.quizid = ?',
        array($quizid));

    if (!($quiz->status == BLOCK_EXAQUEST_QUIZSTATUS_CREATED || $quiz->status == BLOCK_EXAQUEST_QUIZSTATUS_NEW || $quiz->status == BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED)) {
        // throw exception
        throw new moodle_exception('block_exaquest:quiz_not_editable', 'block_exaquest');
    }
}

if (!property_exists($SESSION, 'filterstatus')) {
    $SESSION->filterstatus = 0;
}
if (!property_exists($SESSION, 'fragencharakter')) {
    $SESSION->fragencharakter = -1;
}
if (!property_exists($SESSION, 'klassifikation')) {
    $SESSION->klassifikation = -1;
}
if (!property_exists($SESSION, 'fragefach')) {
    $SESSION->fragefach = -1;
}
if (!property_exists($SESSION, 'lehrinhalt')) {
    $SESSION->lehrinhalt = -1;
}

if ($filterstatus != -1) {
    $SESSION->filterstatus = $filterstatus;
}
if ($fragencharakter != -2) {
    $SESSION->fragencharakter = $fragencharakter;
}
if ($klassifikation != -2) {
    $SESSION->klassifikation = $klassifikation;
}
if ($fragefach != -2) {
    $SESSION->fragefach = $fragefach;
}
if ($lehrinhalt != -2) {
    $SESSION->lehrinhalt = $lehrinhalt;
}
if ($quizid != null) {
    $SESSION->quizid = $quizid;
}

$pagevars['filterstatus'] = $SESSION->filterstatus;
$pagevars['fragencharakter'] = $SESSION->fragencharakter;
$pagevars['klassifikation'] = $SESSION->klassifikation;
$pagevars['fragefach'] = $SESSION->fragefach;
$pagevars['lehrinhalt'] = $SESSION->lehrinhalt;

$catAndCont = get_question_category_and_context_of_course();
$pagevars['cat'] = $catAndCont[0] . ',' . $catAndCont[1];

$page_params = array('courseid' => $courseid, "category" => $pagevars['cat']);

$url = new moodle_url('/blocks/exaquest/exam_questionbank.php', $page_params);

$PAGE->set_url($url);
$PAGE->set_heading('showQuestionBank');
//$streditingquestions = get_string('editquestions', 'question');
//$PAGE->set_title(block_exacomp_get_string($streditingquestions));
$PAGE->set_title('showQuestionBank');

$context = context_course::instance($courseid);
$output = $PAGE->get_renderer('block_exaquest');

$questionbank = new \block_exaquest\questionbank_extensions\exaquest_exam_view($contexts, $url, $COURSE, $cm);

class exam_questionbank_table extends \block_exaquest\tables\questionbank_table {
    protected $quizid;

    protected function define_table_configs() {

        $this->quizid = required_param('quizid', PARAM_INT);

        $cols = [
            'questionbankentryid' => 'Id',
            'qtype' => 'T',
            'name' => 'Name',
            'buttons' => '',
            'ownername' => get_string('ownername', 'block_exaquest'),
            'comments' => 'Kommentare',
            'usage_check_column' => 'Verwendungsüberprüfung',
            'categories' => 'Kategorien',
            'quizactions' => 'Prüfung',
            'changestatus' => 'Status verändern',
        ];

        $this->set_table_columns($cols);

        // $this->set_column_options('timecreated', data_type: static::PARAM_TIMESTAMP);
        $this->set_column_options('comments', no_sorting: true, no_filter: true);

        // $this->no_sorting('quizactions');
        $this->no_filter('quizactions');
        $this->no_sorting('changestatus');
        $this->no_filter('changestatus');
        $this->no_sorting('verwendung');
        $this->no_filter('verwendung');

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

        // SELECT DISTINCT qbe.id as questionbankentryid, qv.status, qc.id as categoryid, qv.version, qv.id as versionid, qrevisea.reviserid as reviserid, q.id, q.qtype, q.name, qbe.idnumber, q.createdby, qc.contextid, uc.firstnamephonetic AS creatorfirstnamephonetic, uc.lastnamephonetic AS creatorlastnamephonetic, uc.middlename AS creatormiddlename, uc.alternatename AS creatoralternatename, uc.firstname AS creatorfirstname, uc.lastname AS creatorlastname, q.timecreated
        // FROM {question} q
        // LEFT JOIN {customfield_data} cfd ON q.id = cfd.instanceid
        // JOIN {question_versions} qv ON qv.questionid = q.id
        // JOIN {question_bank_entries} qbe on qbe.id = qv.questionbankentryid
        // JOIN {question_categories} qc ON qc.id = qbe.questioncategoryid
        // LEFT JOIN {user} uc ON uc.id = q.createdby
        // LEFT JOIN {question_references} qref ON qbe.id = qref.questionbankentryid
        // LEFT JOIN {quiz_slots} qusl ON qref.itemid = qusl.id
        // LEFT JOIN {block_exaquestreviseassign} qrevisea ON qbe.id = qrevisea.questionbankentryid
        // JOIN {block_exaquestquestionstatus} qs ON qbe.id = qs.questionbankentryid
        // LEFT JOIN {block_exaquestreviewassign} qra ON qbe.id = qra.questionbankentryid
        // WHERE q.parent = 0 AND qv.version = (SELECT MAX(v.version)
        //                                   FROM {question_versions} v
        //                                   JOIN {question_bank_entries} be
        //                                     ON be.id = v.questionbankentryid
        //                                  WHERE be.id = qbe.id) AND ((qbe.questioncategoryid = :cat18)) AND ((qs.status = 6)) ORDER BY q.qtype ASC, q.name ASC

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
            , (
                SELECT qs.id
                FROM {question_references} qr
                JOIN {quiz_slots} qs ON qr.itemid = qs.id
                WHERE qr.component="mod_quiz" AND qr.questionarea = "slot" AND qs.quizid = ? AND qr.questionbankentryid = qbe.id
                LIMIT 1
            ) > 0 AS quizactions
            FROM {question} q
            -- LEFT JOIN {customfield_data} cfd ON q.id = cfd.instanceid
            JOIN {question_versions} qv ON qv.questionid = q.id
            JOIN {question_bank_entries} qbe on qbe.id = qv.questionbankentryid
            JOIN {question_categories} qc ON qc.id = qbe.questioncategoryid
            -- LEFT JOIN {user} uc ON uc.id = q.createdby
            LEFT JOIN {user} owner ON owner.id = qbe.ownerid
            JOIN {block_exaquestquestionstatus} qs ON qbe.id = qs.questionbankentryid
            LEFT JOIN {block_exaquestreviewassign} qra ON qbe.id = qra.questionbankentryid -- für filterstatus_condition notwendig
            -- LEFT JOIN {block_exaquestreviseassign} qrevisea ON qbe.id = qrevisea.questionbankentryid
            WHERE q.parent = 0 AND qv.version = (
                SELECT MAX(v.version)
                FROM {question_versions} v
                JOIN {question_bank_entries} be
                ON be.id = v.questionbankentryid
                WHERE be.id = qbe.id
            ) AND ((qbe.questioncategoryid = ?)) AND
            qs.status = ?
            ', [
            $this->quizid,
            $this->categoryid,
            BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED,
        ]);
        // removed the "AND ((qs.status != 9))" as it will always be created in the $this->filterstatus_condition->where() when neccecary.
        // so either it will be a duplicate condition OR it will be a condition that is wrong (for imported questions for example, we want "qs.status = 9"

        $this->set_row_actions_display_as_menu(true);

        // $this->add_row_action(
        //     id: 'dummy',
        //     disabled: true,
        //     label: 'keine Aktionen',
        // );

        // $this->add_row_action(
        //     id: 'revise_question',
        //     label: get_string('revise_question', 'block_exaquest'),
        // );

        // $this->enable_row_selection();

        $this->sortable(true, 'quizactions', SORT_DESC);
    }

    function col_quizactions($question) {
        ob_start();
        echo '<div class="changestatus-button-container" data-question="' . htmlspecialchars(json_encode([
                // hack:
                'questionbankentryid' => $question->questionbankentryid,
                'questionid' => $question->id,
                'quizid' => $this->quizid,
            ])) . '">';

        echo \qbank_openquestionforreview\add_to_quiz::get_content_static($question);
        echo \qbank_openquestionforreview\remove_from_quiz::get_content_static($question);

        echo '</div>';
        return ob_get_clean();
    }

    protected function get_row_actions(object $row, array $row_actions): ?array {
        return null;
    }

    function col_usage_check_column($row) {
        return \qbank_openquestionforreview\usage_check_column::get_content_static($row);
    }

    public function col_changestatus($question) {
        global $DB, $COURSE, $PAGE;

        ob_start();
        echo '<div class="changestatus-button-container" data-question="' . htmlspecialchars(json_encode([
                // hack:
                'questionbankentryid' => $question->questionbankentryid,
                'questionid' => $question->id,
            ])) . '">';

        $output = $PAGE->get_renderer('block_exaquest');
        $fragenersteller = block_exaquest_get_fragenersteller_by_courseid($COURSE->id, false);

        if (has_capability('block/exaquest:changeowner', \context_course::instance($COURSE->id))) {
            echo $output->render(new \block_exaquest\output\popup_change_owner($fragenersteller, 'change_owner',
                get_string('change_owner', 'block_exaquest'), $question, true));
        }
        if (has_capability('block/exaquest:editquestiontoreview', \context_course::instance($COURSE->id))) {
            echo $output->render(new \block_exaquest\output\popup_change_status_secondary_button($fragenersteller, 'revise_question',
                get_string('revise_question', 'block_exaquest'), $question));

            // if the question has NOT been added --> enable locking the question
            if (!$DB->record_exists_sql("SELECT *
                                    FROM {question_references} qr
                                         JOIN {quiz_slots} qs ON qr.itemid = qs.id
                                   WHERE qr.component='mod_quiz' AND qr.questionarea = 'slot' AND qs.quizid = ? AND qr.questionbankentryid = ?", array($this->quizid, $question->questionbankentryid))) {

                echo '<button href="#" class="exaquest-changequestionstatus changestatus' . $question->questionbankentryid .
                    ' btn btn-primary" role="button" value="lockquestion"> ' .
                    get_string('lock_question', 'block_exaquest') . '</button>';
            }
        }

        echo '</div>';
        return ob_get_clean();
    }
}

if (@!$_REQUEST['table_sql']) {
    $questionbank_table = new exam_questionbank_table(
        $questionbank,
        $catAndCont[0] ?: 0, // TODO: gibts da mehrere ids?!?.
        // Die erste Zahl ist die question_cateogry_id, die zweite die context_id (coursecategorycontextid). Kommt von "get_question_category_and_context_of_course", da ist es genauer beschrieben
        with_filterstatus_condition: false
    );


    echo $output->header($context, $courseid, get_string('get_questionbank', 'block_exaquest'));

    block_exaquest_render_questioncount_per_category();
    block_exaquest_render_buttons_for_exam_questionbank();

    // print the create_question_button
    $coursecategorycontextid = $catAndCont[1];
    $coursecategory = \qbank_managecategories\helper::get_categories_for_contexts($coursecategorycontextid, 'id', false);
    $coursecategory = array_pop($coursecategory);
    $coursecategorycontext = \context::instance_by_id($coursecategorycontextid);
    $canadd = has_capability('moodle/question:add', $coursecategorycontext);
    $questionbank->create_new_question_form_dashboard($coursecategory, $canadd);


    $questionbank_table->out();

    echo $output->footer();

    exit;
}

echo $output->header($context, $courseid, get_string('get_questionbank', 'block_exaquest'));

if (($lastchanged = optional_param('lastchanged', 0, PARAM_INT)) !== 0) {
    $url->param('lastchanged', $lastchanged);
}

echo '<div class="questionbankwindow boxwidthwide boxaligncenter">';
$questionbank->display($pagevars, 'editq');
echo "</div>\n";

echo $output->footer();
