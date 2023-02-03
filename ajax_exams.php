<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $USER;

$action = required_param('action', PARAM_TEXT);
$quizid = required_param('quizid', PARAM_INT);

switch ($action) {
    case ('fachlich_release_exam'):
        block_exaquest_exams_set_status($quizid, BLOCK_EXAQUEST_QUIZSTATUS_FACHLICH_RELEASED);
        break;
}