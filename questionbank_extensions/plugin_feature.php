<?php


namespace qbank_openquestionforreview;

use core_question\local\bank\plugin_features_base;
use core_question\local\bank\menu_action_column_base;

require_once('add_to_quiz.php');
require_once('usage_check_column.php');
require_once('category_options.php');
require_once('remove_from_quiz.php');

/**
 * Class plugin_feature is the entrypoint for the columns.
 *
 * @package    qbank_questiontodescriptor
 * @copyright  2021 Catalyst IT Australia Pty Ltd
 * @author     Ghaly Marc-Alexandre <marc-alexandreghaly@catalyst-ca.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plugin_feature extends \core_question\local\bank\plugin_features_base {

    public function get_question_columns(\core_question\local\bank\view $qbank): array {
        return [
            new change_status($qbank),
            new \qbank_editquestion\edit_action_column_exaquest($qbank),
            new \qbank_deletequestion\delete_action_column_exaquest($qbank),
            new \qbank_history\history_action_column_exaquest($qbank),
            new add_to_quiz($qbank),
            new usage_check_column($qbank),
            new category_options($qbank),
            new remove_from_quiz($qbank),
            new question_id_column($qbank),
            new owner_column($qbank),
            new last_changed_column($qbank),
            new status_column($qbank)
        ];
    }
}
