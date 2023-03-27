<?php

require_once __DIR__ . '/../inc.php';

function xmldb_block_exaquest_upgrade($oldversion)
{
    global $DB, $CFG;
    $dbman = $DB->get_manager();
    $return_result = true;

    if ($oldversion < 2022060300) {

        $table = new xmldb_table('block_exaquestquestionstatus');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('questionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('status', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Exaquest savepoint reached.
        upgrade_block_savepoint(true, 2022060300, 'exaquest');
    }

    if ($oldversion < 2022060902) {
        // Creating roles and assigning capabilities
        // Done as a task AFTER the installation/upgrade, because the capabilities only exist at the end/after the installation/upgrade.
        // create the instance
        $setuptask = new \block_exaquest\task\set_up_roles();
        // queue it
        \core\task\manager::queue_adhoc_task($setuptask);
        // Exaquest savepoint reached.
        upgrade_block_savepoint(true, 2022060902, 'exaquest');
    }
    if ($oldversion < 2022062401) {

        // TODO add reference to block_exaquestquestionstatus ? or is it enough to have it in the install.xml?

        // Define table block_exaquestreviewassign to be created.
        $table = new xmldb_table('block_exaquestreviewassign');

        // Adding fields to table block_exaquestreviewassign.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('questionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('reviewerid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('reviewtype', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_exaquestreviewassign.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_exaquestreviewassign.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Exaquest savepoint reached.
        upgrade_block_savepoint(true, 2022062401, 'exaquest');
    }

    if ($oldversion < 2022062404) {
        // add keys block_exaquestquestionstatus and block_exaquestreviewassign
        $table = new xmldb_table('block_exaquestquestionstatus');
        $key = new xmldb_key('questionid', XMLDB_KEY_FOREIGN, array('questionid'), 'question', array('id'));
        // Launch add key questionid.
        $dbman->add_key($table, $key);

        $table = new xmldb_table('block_exaquestreviewassign');
        $key = new xmldb_key('questionid', XMLDB_KEY_FOREIGN, array('questionid'), 'question', array('id'));
        // Launch add key questionid.
        $dbman->add_key($table, $key);
        $key = new xmldb_key('reviewerid', XMLDB_KEY_FOREIGN, array('reviewerid'), 'user', array('id'));
        // Launch add key reviewerid.
        $dbman->add_key($table, $key);

        // Exaquest savepoint reached.
        upgrade_block_savepoint(true, 2022062404, 'exaquest');
    }

    if ($oldversion < 2022062407) {
        // remove the roles, because of typos
        $DB->delete_records('role', ['shortname' => 'admintechnprufungsdurchf']);
        $DB->delete_records('role', ['shortname' => 'prufungskoordination']);
        $DB->delete_records('role', ['shortname' => 'prufungsstudmis']);
        $DB->delete_records('role', ['shortname' => 'fachlicherprufer']);
        $DB->delete_records('role', ['shortname' => 'prufungsmitwirkende']);
        $DB->delete_records('role', ['shortname' => 'fachlicherzweitprufer']);
        // redo the set_up_roles
        $setuptask = new \block_exaquest\task\set_up_roles();
        // queue it
        \core\task\manager::queue_adhoc_task($setuptask);
        upgrade_block_savepoint(true, 2022062407, 'exaquest');
    }


    if ($oldversion < 2022070500) {
        // rename fields questionid to questionbanentryid
        $table = new xmldb_table('block_exaquestquestionstatus');
        $field = new xmldb_field('questionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        if ($dbman->field_exists($table, $field)) {
            $dbman->rename_field($table, $field, 'questionbankentryid');
        }

        $table = new xmldb_table('block_exaquestreviewassign');
        $field = new xmldb_field('questionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        if ($dbman->field_exists($table, $field)) {
            $dbman->rename_field($table, $field, 'questionbankentryid');
        }


        // drop keys because we want to use questionbankentryid instead of questionid
        $table = new xmldb_table('block_exaquestquestionstatus');
        $key = new xmldb_key('questionid', XMLDB_KEY_FOREIGN, array('questionid'), 'question', array('id'));
        // Launch drop key primary.
        $dbman->drop_key($table, $key);

        $table = new xmldb_table('block_exaquestreviewassign');
        $key = new xmldb_key('questionid', XMLDB_KEY_FOREIGN, array('questionid'), 'question', array('id'));
        // Launch drop key primary.
        $dbman->drop_key($table, $key);


        // add keys block_exaquestquestionstatus and block_exaquestreviewassign with questionbankentryid instead of questionid
        $table = new xmldb_table('block_exaquestquestionstatus');
        $key = new xmldb_key('questionbankentryid', XMLDB_KEY_FOREIGN, array('questionbankentryid'), 'question_bank_entries', array('id'));
        // Launch add key questionbankentryid.
        $dbman->add_key($table, $key);

        $table = new xmldb_table('block_exaquestreviewassign');
        $key = new xmldb_key('questionbankentryid', XMLDB_KEY_FOREIGN, array('questionbankentryid'), 'question_bank_entries', array('id'));
        // Launch add key questionbankentryid.
        $dbman->add_key($table, $key);

        upgrade_block_savepoint(true, 2022070500, 'exaquest');
    }

    if ($oldversion < 2022070501) {
        // add field courseid
        $table = new xmldb_table('block_exaquestquestionstatus');
        $field = new xmldb_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);
        $dbman->add_field($table, $field);

        // add key courseid
        $table = new xmldb_table('block_exaquestquestionstatus');
        $key = new xmldb_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
        // Launch add key courseid.
        $dbman->add_key($table, $key);

        upgrade_block_savepoint(true, 2022070501, 'exaquest');
    }

    if ($oldversion < 2022072500) {
        // first commit of stefan, actually done at an earlier version, but moved here on refactor

        // Define table block_exaquest_similarity to be created.
        $table = new xmldb_table('block_exaquest_similarity');

        // Adding fields to table block_exaquest_similarity.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('question_id1', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('question_id2', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('is_similar', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('similarity', XMLDB_TYPE_NUMBER, '20, 19', null, null, null, null);
        $table->add_field('timestamp_calculation', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('threshold', XMLDB_TYPE_NUMBER, '20, 19', null, null, null, null);
        $table->add_field('algorithm', XMLDB_TYPE_CHAR, '20', null, null, null, null);

        // Adding keys to table block_exaquest_similarity.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('fk_questionid_1', XMLDB_KEY_FOREIGN, ['question_id1'], 'question', ['id']);
        $table->add_key('fk_questionid_2', XMLDB_KEY_FOREIGN, ['question_id2'], 'question', ['id']);

        // Conditionally launch create table for block_exaquest_similarity.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // ---------------------------------- second commit of Stefan, added to this one in refactoring

        // Changing precision of field algorithm on table block_exaquest_similarity to (50).
        $table = new xmldb_table('block_exaquest_similarity');
        $field = new xmldb_field('algorithm', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'threshold');

        // Launch change of precision for field algorithm.
        $dbman->change_field_precision($table, $field);

        // Exaquest savepoint reached.
        upgrade_block_savepoint(true, 2022072500, 'exaquest');
    }

    if ($oldversion < 2022101302) {
        $table = new xmldb_table('block_exaquestcategories');

        // Adding fields to table block_exaquest_similarity.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('coursecategoryid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('categoryname', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('categorytype', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);


        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('coursecategoryid', XMLDB_KEY_FOREIGN, ['coursecategoryid'], 'course_categories', ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Exaquest savepoint reached.
        upgrade_block_savepoint(true, 2022101302, 'exaquest');
    }

    if ($oldversion < 2022101303) {
        $table = new xmldb_table('block_exaquestquestcat_mm');

        // Adding fields to table block_exaquest_similarity.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('questionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('exaquestcategoryid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);


        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('questionid', XMLDB_KEY_FOREIGN, ['questionid'], 'question', ['id']);
        $table->add_key('exaquestcategoryid', XMLDB_KEY_FOREIGN, ['exaquestcategoryid'], 'exaquestcategories', ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Exaquest savepoint reached.
        upgrade_block_savepoint(true, 2022101303, 'exaquest');
    }

    if ($oldversion < 2022101800) {
        // Define table block_exaquestquizstatus to be created.
        $table = new xmldb_table('block_exaquestquizstatus');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('quizid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('status', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('fk_quizid', XMLDB_KEY_FOREIGN, ['quizid'], 'quiz', ['id']);
        $table->add_key('fk_courseid', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Exaquest savepoint reached.
        upgrade_block_savepoint(true, 2022101800, 'exaquest');
    }

    if ($oldversion < 2022110700) {
        $table = new xmldb_table('block_exaquestrequestquest');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('comment', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('fk_userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Exaquest savepoint reached.
        upgrade_block_savepoint(true, 2022110700, 'exaquest');
    }

    if ($oldversion < 2022110800) {
        // add courseid to block_exaquestrequestquest
        $table = new xmldb_table('block_exaquestrequestquest');
        $field = new xmldb_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);
        $key = new xmldb_key('fk_courseid', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);

        // Conditionally launch add field courseid and key
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
            $dbman->add_key($table, $key);
        }

        // Exaquest savepoint reached.
        upgrade_block_savepoint(true, 2022110800, 'exaquest');
    }



    if ($oldversion < 2022110900) {
        // change the courseid fields to coursecategoryid

        $table = new xmldb_table('block_exaquestquestionstatus');
        $field = new xmldb_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        if ($dbman->field_exists($table, $field)) {
            $dbman->rename_field($table, $field, 'coursecategoryid');
        }

        $table = new xmldb_table('block_exaquestquizstatus');
        $field = new xmldb_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        if ($dbman->field_exists($table, $field)) {
            $dbman->rename_field($table, $field, 'coursecategoryid');
        }

        $table = new xmldb_table('block_exaquestrequestquest');
        $field = new xmldb_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        if ($dbman->field_exists($table, $field)) {
            $dbman->rename_field($table, $field, 'coursecategoryid');
        }

        // drop keys because we want to use coursecategoryid instead of courseid
        $table = new xmldb_table('block_exaquestquestionstatus');
        $key = new xmldb_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
        $dbman->drop_key($table, $key);

        $table = new xmldb_table('block_exaquestquizstatus');
        $key = new xmldb_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
        $dbman->drop_key($table, $key);

        $table = new xmldb_table('block_exaquestrequestquest');
        $key = new xmldb_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
        $dbman->drop_key($table, $key);

        // add keys coursecategoryid
        $table = new xmldb_table('block_exaquestquestionstatus');
        $key = new xmldb_key('coursecategoryid', XMLDB_KEY_FOREIGN, array('coursecategoryid'), 'course_categories', array('id'));
        $dbman->add_key($table, $key);

        $table = new xmldb_table('block_exaquestquizstatus');
        $key = new xmldb_key('coursecategoryid', XMLDB_KEY_FOREIGN, array('coursecategoryid'), 'course_categories', array('id'));
        $dbman->add_key($table, $key);

        $table = new xmldb_table('block_exaquestrequestquest');
        $key = new xmldb_key('coursecategoryid', XMLDB_KEY_FOREIGN, array('coursecategoryid'), 'course_categories', array('id'));
        $dbman->add_key($table, $key);

        upgrade_block_savepoint(true, 2022110900, 'exaquest');
    }

    if ($oldversion < 2023031300) {
        // Creating roles and assigning capabilities
        // Done as a task AFTER the installation, because the capabilities only exist at the end/after the installation.
        // create the instance
        $setuptask = new \block_exaquest\task\set_up_roles();
        // queue it
        \core\task\manager::queue_adhoc_task($setuptask);
        upgrade_block_savepoint(true, 2023031300, 'exaquest');
    }

    if ($oldversion < 2023031303) {
        $table = new xmldb_table('block_exaquestrequestexam');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('comment', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('coursecategoryid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('fk_userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
        $table->add_key('fk_coursecategoryid', XMLDB_KEY_FOREIGN, ['coursecategoryid'], 'course_categories', ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Exaquest savepoint reached.
        upgrade_block_savepoint(true, 2023031303, 'exaquest');
    }

    if ($oldversion < 2023032702) {
        // Creating roles and assigning capabilities
        // Done as a task AFTER the installation, because the capabilities only exist at the end/after the installation.
        // create the instance
        $setuptask = new \block_exaquest\task\set_up_roles();
        // queue it
        \core\task\manager::queue_adhoc_task($setuptask);
        upgrade_block_savepoint(true, 2023032702, 'exaquest');
    }

    return $return_result;
}