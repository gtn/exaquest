<?php

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'block_exaquest';  // Recommended since 2.0.2 (MDL-26035). Required since 3.0 (MDL-48494)
$plugin->version = 2024053104;
$plugin->requires = 2010112400;
$plugin->dependencies = [
    'local_table_sql' => 2024052800,
];
