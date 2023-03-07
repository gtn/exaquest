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

require_once __DIR__ . '/../inc.php';

use core_customfield\field_controller;

// called when installing a plugin
function xmldb_block_exaquest_install() {
    global $DB;

    // TODO: only do it once, if those fields do not exist yet
    // TODO: check if the created fields work as intended

    $handler = qbank_customfields\customfield\question_handler::create();
    $c1id = $handler->create_category();
    $c1 = $handler->get_categories_with_fields()[$c1id];
    $handler->rename_category($c1, 'Exaquest Kategorie');

    // create the categories:
    $record = new stdClass();
    $record->name = 'Fragencharakter';
    $record->shortname = "fragencharakter";
    $record->type = 'exaquestcategory';
    $record->sortorder = 0;
    $configdata = [];
    $configdata += [
        'required' => 0,
        'uniquevalues' => 0,
        "categorytype"=> 0,
        'locked' => 0,
        'visibility' => 2,
    ];

    $record->configdata = json_encode($configdata);
    $field = field_controller::create(0, (object) ['type' => $record->type], $c1);
    $handler->save_field_configuration($field, $record);

    $record = new stdClass();
    $record->name = 'Klassifikation';
    $record->shortname = "klassifikation";
    $record->type = 'exaquestcategory';
    $record->sortorder = 0;
    $configdata = [];
    $configdata += [
        'required' => 0,
        'uniquevalues' => 0,
        "categorytype"=> 1,
        'locked' => 0,
        'visibility' => 2,
    ];
    $record->configdata = json_encode($configdata);
    $field = field_controller::create(0, (object) ['type' => $record->type], $c1);
    $handler->save_field_configuration($field, $record);

    $record = new stdClass();
    $record->name = 'Fragefach';
    $record->shortname = "fragefach";
    $record->type = 'exaquestcategory';
    $record->sortorder = 0;
    $configdata = [];
    $configdata += [
        'required' => 0,
        'uniquevalues' => 0,
        "categorytype"=> 2,
        'locked' => 0,
        'visibility' => 2,
    ];
    $record->configdata = json_encode($configdata);
    $field = field_controller::create(0, (object) ['type' => $record->type], $c1);
    $handler->save_field_configuration($field, $record);

    $record = new stdClass();
    $record->name = 'Lehrinhalt';
    $record->shortname = "lehrinhalt";
    $record->type = 'exaquestcategory';
    $record->sortorder = 0;
    $configdata = [];
    $configdata += [
        'required' => 0,
        'uniquevalues' => 0,
        "categorytype"=> 3,
        'locked' => 0,
        'visibility' => 2,
    ];
    $record->configdata = json_encode($configdata);
    $field = field_controller::create(0, (object) ['type' => $record->type], $c1);
    $handler->save_field_configuration($field, $record);

    // Creating roles and assigning capabilities
    // Done as a task AFTER the installation, because the capabilities only exist at the end/after the installation.
    // create the instance
    $setuptask = new \block_exaquest\task\set_up_roles();
    // queue it
    \core\task\manager::queue_adhoc_task($setuptask);
}
