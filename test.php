<?php

require_once __DIR__ . '/inc.php';

use core_customfield\field_controller;

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