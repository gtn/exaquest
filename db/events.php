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

// List of observers.
$observers = array(
        array(
                'eventname' => '\core\event\question_created',
                'callback' => 'block_exaquest_observer::question_created',
                'internal' => false,
        ),
        array(
                'eventname' => '\core\event\course_module_created',
                'callback' => 'block_exaquest_observer::course_module_created',
                'internal' => false,
        ),
        array(
                'eventname' => '\core\event\course_module_completion_updated',
                'callback' => 'block_exaquest_observer::course_module_completion_updated',
                'internal' => false,
        ),
        array(
                'eventname' => '\core\event\attempt_becameoverdue',
                'callback' => 'block_exaquest_observer::attempt_becameoverdue',
                'internal' => false,
        ),

        array(
                'eventname' => '\mod_quiz\event\attempt_reviewed',
                'callback' => 'block_exaquest_observer::attempt_reviewed',
                'internal' => false,
        ),
        array(
                'eventname' => '\mod_quiz\event\attempt_manual_grading_completed',
                'callback' => 'block_exaquest_observer::attempt_manual_grading_completed',
                'internal' => false,
        ),
        array(
                'eventname' => '\mod_quiz\event\question_manually_graded',
                'callback' => 'block_exaquest_observer::question_manually_graded',
                'internal' => false,
        ),
        array(
                'eventname' => '\mod_quiz\event\attempt_submitted',
                'callback' => 'block_exaquest_observer::attempt_submitted',
                'internal' => false,
        ),
        array(
                'eventname'   => '\mod_quiz\event\course_module_viewed',
                'callback'    => 'block_exaquest_observer::course_module_viewed',
        ),
);
