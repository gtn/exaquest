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

namespace block_exaquest\task;

defined('MOODLE_INTERNAL') || die();

require_once __DIR__ . '/../../inc.php';

/**
 * Check newly created questions if they are imported or created in exaquest. This has to be done here, because sometimes the customfield data is not available in the event observer.
 */
class check_if_questions_imported extends \core\task\adhoc_task {
    /**
     * Execute the task.
     */
    public function execute() {
        $customdata = $this->get_custom_data();
        block_exaquest_check_if_questions_imported($customdata->questionid, $customdata->questionbankentryid);
    }

    public function get_name() {
        //return block_exacomp_trans(['en:Import Data with additional functionality', 'de:Daten Importieren mit zusätzlicher Funktionalität']);
        return "Check if questions have been imported.";
        //return get_string('set_up_roles', 'block_exaquest');
    }
}




