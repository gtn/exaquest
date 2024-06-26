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
global $CFG;
require_once __DIR__ . '/../inc.php'; // otherwise the course_module_completion_updated does not have access to the exaquest functions in some cases

require_once($CFG->dirroot . '/lib/classes/task/manager.php');


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
        if (is_exaquest_active_in_course()) {
            // first check if for this questionbankentry there already exists an entry in our table:
            $questionbankentry = get_question_bank_entry($event->objectid); // $event->objecid is the questionid
            if (!$DB->record_exists(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, ["questionbankentryid" => $questionbankentry->id])) {
                $insert = new stdClass();
                // assume it has been created manually
                $insert->status = BLOCK_EXAQUEST_QUESTIONSTATUS_NEW;
                $insert->is_imported = 0;
                // get the questionbankentry via a moodle function (simply a join from questions over the versions to the banke_entry)
                $insert->questionbankentryid = $questionbankentry->id;
                $DB->insert_record(BLOCK_EXAQUEST_DB_QUESTIONSTATUS, $insert);

                // schedule a task to check if the question is imported or not, IF the question is marked as imported (sometimes the customfield data is not yet available)
                $customdata = array(
                    'questionid' => $event->objectid,
                    'questionbankentryid' => $questionbankentry->id,
                );
                $check_import_task = new \block_exaquest\task\check_if_questions_imported();
                $check_import_task->set_custom_data($customdata);
                // queue it
                \core\task\manager::queue_adhoc_task($check_import_task);

            }
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
        if ($data['other']['modulename'] == 'quiz') {
            $insert = new stdClass();
            $insert->quizid = $data['other']['instanceid'];
            $insert->status = BLOCK_EXAQUEST_QUIZSTATUS_NEW;
            $insert->creatorid = $data['userid'];
            //$insert->coursecategoryid =
            //    block_exaquest_get_coursecategoryid_by_courseid($event->courseid); // the context is context_module. The event has the courseid.
            $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, $insert);
        }
    }

    // ---------------------

    /**
     * Observer for core\event\course_module_completion_updated.
     *
     * @param core\event\course_module_completion_updated $event
     * @return void
     */
    public static function course_module_completion_updated(\core\event\course_module_completion_updated $event) {
        global $DB;
        $data = $event->get_data();
        if ($data['other']['modulename'] == 'quiz') {
            $insert = new stdClass();
            $insert->quizid = $data['other']['instanceid'];
            $insert->status = BLOCK_EXAQUEST_QUIZSTATUS_NEW;
            //$insert->coursecategoryid =
            //    block_exaquest_get_coursecategoryid_by_courseid($event->courseid); // the context is context_module. The event has the courseid.
            $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, $insert);
        }
    }

    ///**
    // * Observer for core\event\course_module_updated.
    // *
    // * @param core\event\course_module_updated $event
    // * @return void
    // */
    //public static function course_module_updated(\core\event\course_module_updated $event) {
    //    global $DB;
    //    $data = $event->get_data();
    //    if ($data['other']['modulename'] == 'quiz') {
    //        $insert = new stdClass();
    //        $insert->quizid = $data['other']['instanceid'];
    //        $insert->status = BLOCK_EXAQUEST_QUIZSTATUS_NEW;
    //        $insert->coursecategoryid =
    //            block_exaquest_get_coursecategoryid_by_courseid($event->courseid); // the context is context_module. The event has the courseid.
    //        $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, $insert);
    //    }
    //}

    /**
     * Observer for core\event\attempt_becameoverdue.
     * // TODO: is this ever triggered? Or is this whole thing solved only in the cron?
     * @param core\event\attempt_becameoverdue $event
     * @return void
     */
    public static function attempt_becameoverdue(\core\event\attempt_becameoverdue $event) {
        global $DB;
        $data = $event->get_data();
        if ($data['other']['modulename'] == 'quiz') {
            $insert = new stdClass();
            $insert->quizid = $data['other']['instanceid'];
            $insert->status = BLOCK_EXAQUEST_QUIZSTATUS_NEW;
            //$insert->coursecategoryid =
            //    block_exaquest_get_coursecategoryid_by_courseid($event->courseid); // the context is context_module. The event has the courseid.
            $DB->insert_record(BLOCK_EXAQUEST_DB_QUIZSTATUS, $insert);
        }
    }

    // /**
    //  * Observer for mod_quiz\event\attempt_manual_grading_completed.
    //  * @param mod_quiz\event\attempt_manual_grading_completed $event
    //  * @return void
    //  */
    // public static function attempt_manual_grading_completed(\mod_quiz\event\attempt_manual_grading_completed $event) {
    //     global $DB;
    //     $data = $event->get_data();
    // }

    /**
     * Observer for mod_quiz\event\question_manually_graded.
     * @param mod_quiz\event\question_manually_graded $event
     *
     * @return void
     */
    public static function question_manually_graded(\mod_quiz\event\question_manually_graded $event) {
        global $DB;
        $data = $event->get_data();
        block_exaquest_check_if_all_gradings_have_been_done($data["other"]["quizid"]);
    }

    // /**
    //  * Observer for mod_quiz\event\attempt_submitted.
    //  * @param mod_quiz\event\attempt_submitted $event
    //  * @return void
    //  */
    // public static function attempt_submitted(\mod_quiz\event\attempt_submitted $event) {
    //     global $DB;
    //     //$data = $event->get_data();
    // }

}
