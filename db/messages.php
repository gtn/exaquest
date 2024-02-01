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

$messageproviders = array(
    // Notify Fragenersteller that a new questions should be created
        'newquestionsrequest' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                    // somehow this does not always work. The admin has to do this manually on ./admin/message.php
                        'email' => MESSAGE_PERMITTED,
                ],
        ),
        'fillexam' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED,
                ],
        ),
        'gradeexam' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED,
                ],
        ),
        'checkexamgrading' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED,
                ],
        ),
        'changeexamgrading' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED,
                ],
        ),
        'quizfinishedgradingopen' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED,
                ],
        ),
        'quizfinishedgradingdone' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED,
                ],
        ),
        'newexamsrequest' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED,
                ],
        ),
    // Notify that Fragenersteller has to revise a question
        'revisequestion' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED,
                ],
        ),
    // Notify modulverantwortlicher and fachlicherfragenreviewer that they should review a question
        'reviewquestion' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED,
                ],
        ),
    // Notify modulverantwortlicher that they should release a question
        'releasequestion' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                        'email' => MESSAGE_PERMITTED,
                ],
        ),
    // Notify modulverantwortlicher and fachlicherfragenreviewer that they should review a question
        'dailytodos' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED,
                        'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                ],
        ),
    // Notify modulverantwortlicher and fachlicherfragenreviewer that they should review a question
        'daily_released_questions' => array(
                'defaults' => [
                        'popup' => MESSAGE_PERMITTED,
                        'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
                ],
        ),
);
