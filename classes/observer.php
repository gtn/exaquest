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

defined('MOODLE_INTERNAL') || die();
require_once __DIR__ . './../inc.php'; // otherwise the course_module_completion_updated does not have access to the exaquest functions in some cases

/**
 * Event observer for block_exaquest.
 */
class block_exaquest_observer {

    /**
     * Observer for \core\event\question_created event.
     *
     * @param \core\event\question_created $event
     * @return void
     */
    public static function question_created(\core\event\question_created $event) {
        global $DB;
        // first check if for this questionbankentry there already exists an entry in our table:
        $questionbankentry = get_question_bank_entry($event->objectid); // $event->objecid is the questionid
        if(!$DB->record_exists(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, ["questionbankentryid" => $questionbankentry->id])){
            $insert = new stdClass();
            // get the questionbankentry via a moodle function (simply a join from questions over the versions to the banke_entry)
            $insert->questionbankentryid = $questionbankentry->id;
            $insert->status = BLOCK_EXAQUEST_QUESTIONSTATUS_NEW;
            if ($event->get_context() instanceof \context_coursecat) {
                $insert->coursecategoryid = $event->contextinstanceid;
            } else if ($event->get_context() instanceof \context_course) { // TODO: this should NOT happen anyways. Otherwise the question is in the wrong place and will not be seen.
                //$course = $DB->get_record('course', array('id' => $event->contextinstanceid));
                $insert->coursecategoryid = block_exaquest_get_coursecategoryid_by_courseid($event->contextinstanceid);
            } else {
                throw new Exception("neither course nor coursecategory context"); // TODO, what else could there be?
            }
            $DB->insert_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $insert);
        }
    }


    /**
     * Observer for core\event\course_module_created.
     *
     * @param core\event\course_module_created $event
     * @return void
     */
    public static function course_module_created(\core\event\course_module_created $event) {
        global $DB;
        $data = $event->get_data();
        if($data['other']['modulename'] == 'quiz'){
            $insert = new stdClass();
            $insert->quizid = $data['other']['instanceid'];
            $insert->status = BLOCK_EXAQUEST_QUIZSTATUS_NEW;
            $insert->coursecategoryid = block_exaquest_get_coursecategoryid_by_courseid($event->courseid); // the context is context_module. The event has the courseid.
            $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, $insert);
        }
    }


    /**
     * Observer for core\event\course_module_completion_updated.
     *
     * @param core\event\course_module_completion_updated $event
     * @return void
     */
    public static function course_module_completion_updated(\core\event\course_module_completion_updated $event) {
        global $DB;
        $data = $event->get_data();
        if($data['other']['modulename'] == 'quiz'){
            $insert = new stdClass();
            $insert->quizid = $data['other']['instanceid'];
            $insert->status = BLOCK_EXAQUEST_QUIZSTATUS_NEW;
            $insert->coursecategoryid = block_exaquest_get_coursecategoryid_by_courseid($event->courseid); // the context is context_module. The event has the courseid.
            $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, $insert);
        }
    }


    /**
     * Observer for core\event\attempt_becameoverdue.
     *
     * @param core\event\attempt_becameoverdue $event
     * @return void
     */
    public static function attempt_becameoverdue(\core\event\attempt_becameoverdue $event) {
        global $DB;
        $data = $event->get_data();
        if($data['other']['modulename'] == 'quiz'){
            $insert = new stdClass();
            $insert->quizid = $data['other']['instanceid'];
            $insert->status = BLOCK_EXAQUEST_QUIZSTATUS_NEW;
            $insert->coursecategoryid = block_exaquest_get_coursecategoryid_by_courseid($event->courseid); // the context is context_module. The event has the courseid.
            $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, $insert);
        }
    }

}
