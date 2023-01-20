<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $PAGE, $OUTPUT, $USER;

require_once($CFG->dirroot . '/question/editlib.php');
require_once(__DIR__ . '/questionbank_extensions/exaquest_view.php');


$courseid = optional_param('courseid', '', PARAM_INT);
if(!$courseid){
    // the form from modedit sends it as course, not as courseid
    $courseid = required_param('course', PARAM_INT);
}

$action = optional_param('action', '', PARAM_TEXT);
$name = optional_param('name', '', PARAM_TEXT);
require_login($courseid);

//$course = $DB->get_record('course', array('id' => $courseid));
$context = context_course::instance($courseid);

$page_params = array('courseid' => $courseid);

$url = new moodle_url('/blocks/exaquest/exams.php', $page_params);
$PAGE->set_url($url);
$PAGE->set_heading(get_string('exams', 'block_exaquest'));
$PAGE->set_title(get_string('exams', 'block_exaquest'));

block_exaquest_init_js_css();

// create quiz needs to call this: create_module($moduleinfo)

// code for creating a module when clicking the button in a modedit form. Based on course/modedit.php
if (optional_param('createorupdate', '', PARAM_INT)) {


    require_once("../../config.php");
    require_once("../../course/lib.php");
    require_once($CFG->libdir . '/filelib.php');
    require_once($CFG->libdir . '/gradelib.php');
    require_once($CFG->libdir . '/completionlib.php');
    require_once($CFG->libdir . '/plagiarismlib.php');
    require_once($CFG->dirroot . '/course/modlib.php');

    $add = optional_param('add', '', PARAM_ALPHANUM);     // Module name.
    $update = optional_param('update', 0, PARAM_INT);

    $add = optional_param('add', '', PARAM_ALPHANUM);     // Module name.
    $update = optional_param('update', 0, PARAM_INT);
    $return = optional_param('return', 0, PARAM_BOOL);    //return to course/view.php if false or mod/modname/view.php if true
    $type = optional_param('type', '', PARAM_ALPHANUM); //TODO: hopefully will be removed in 2.0
    $sectionreturn = optional_param('sr', null, PARAM_INT);

    $url = new moodle_url('/course/modedit.php');
    $url->param('sr', $sectionreturn);
    if (!empty($return)) {
        $url->param('return', $return);
    }

    if (!empty($add)) {
        $section = required_param('section', PARAM_INT);
        $course = required_param('course', PARAM_INT);

        $url->param('add', $add);
        $url->param('section', $section);
        $url->param('course', $course);
        $PAGE->set_url($url);

        $course = $DB->get_record('course', array('id' => $course), '*', MUST_EXIST);
        require_login($course);

        // There is no page for this in the navigation. The closest we'll have is the course section.
        // If the course section isn't displayed on the navigation this will fall back to the course which
        // will be the closest match we have.
        navigation_node::override_active_url(course_get_url($course, $section));

        // MDL-69431 Validate that $section (url param) does not exceed the maximum for this course / format.
        // If too high (e.g. section *id* not number) non-sequential sections inserted in course_sections table.
        // Then on import, backup fills 'gap' with empty sections (see restore_rebuild_course_cache). Avoid this.
        $courseformat = course_get_format($course);
        $maxsections = $courseformat->get_max_sections();
        if ($section > $maxsections) {
            throw new \moodle_exception('maxsectionslimit', 'moodle', '', $maxsections);
        }

        list($module, $context, $cw, $cm, $data) = prepare_new_moduleinfo_data($course, $add, $section);
        $data->return = 0;
        $data->sr = $sectionreturn;
        $data->add = $add;
        if (!empty($type)) { //TODO: hopefully will be removed in 2.0
            $data->type = $type;
        }

        $sectionname = get_section_name($course, $cw);
        $fullmodulename = get_string('modulename', $module->name);

        if ($data->section && $course->format != 'site') {
            $heading = new stdClass();
            $heading->what = $fullmodulename;
            $heading->to = $sectionname;
            $pageheading = get_string('addinganewto', 'moodle', $heading);
        } else {
            $pageheading = get_string('addinganew', 'moodle', $fullmodulename);
        }
        $navbaraddition = $pageheading;

    } else if (!empty($update)) {

        $url->param('update', $update);
        $PAGE->set_url($url);

        // Select the "Edit settings" from navigation.
        navigation_node::override_active_url(new moodle_url('/course/modedit.php', array('update' => $update, 'return' => 1)));

        // Check the course module exists.
        $cm = get_coursemodule_from_id('', $update, 0, false, MUST_EXIST);

        // Check the course exists.
        $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

        // require_login
        require_login($course, false, $cm); // needed to setup proper $COURSE

        list($cm, $context, $module, $data, $cw) = get_moduleinfo_data($cm, $course);
        $data->return = $return;
        $data->sr = $sectionreturn;
        $data->update = $update;

        $sectionname = get_section_name($course, $cw);
        $fullmodulename = get_string('modulename', $module->name);

        if ($data->section && $course->format != 'site') {
            $heading = new stdClass();
            $heading->what = $fullmodulename;
            $heading->in = $sectionname;
            $pageheading = get_string('updatingain', 'moodle', $heading);
        } else {
            $pageheading = get_string('updatinga', 'moodle', $fullmodulename);
        }
        $navbaraddition = null;

    }

    $pagepath = 'mod-' . $module->name . '-';
    if (!empty($type)) { //TODO: hopefully will be removed in 2.0
        $pagepath .= $type;
    } else {
        $pagepath .= 'mod';
    }
    $PAGE->set_pagetype($pagepath);
    $PAGE->set_pagelayout('admin');
    $PAGE->add_body_class('limitedwidth');

    $modmoodleform = "$CFG->dirroot/mod/$module->name/mod_form.php";
    if (file_exists($modmoodleform)) {
        require_once($modmoodleform);
    } else {
        throw new \moodle_exception('noformdesc');
    }

    $mformclassname = 'mod_' . $module->name . '_mod_form';
    $mform = new $mformclassname($data, $cw->section, $cm, $course);
    $mform->set_data($data);

    if ($fromform = $mform->get_data()) {
        if (!empty($fromform->update)) {
            list($cm, $fromform) = update_moduleinfo($cm, $fromform, $course, $mform);
        } else if (!empty($fromform->add)) {
            $fromform = add_moduleinfo($fromform, $course, $mform);
        } else {
            throw new \moodle_exception('invaliddata');
        }
    }
}
// --------------------- end of code for creating a module when clicking the button in a modedit form

if ($action == 'create') {
    //create an object with all of the neccesary information to build a quiz
    $myQuiz = new stdClass();
    $myQuiz->modulename = 'quiz';
    $myQuiz->name = $name;
    $myQuiz->introformat = 0;
    $myQuiz->quizpassword = '';
    $myQuiz->course = $COURSE->id;
    $myQuiz->section = 1; // google moodle sections
    $myQuiz->timeopen = 0;
    $myQuiz->timeclose = 0;
    $myQuiz->timelimit = 0;
    $myQuiz->grade = 0;
    $myQuiz->sumgrades = 0;
    $myQuiz->gradeperiod = 0;
    $myQuiz->attempts = 1;
    $myQuiz->preferredbehaviour = 'deferredfeedback';
    $myQuiz->attemptonlast = 0;
    $myQuiz->shufflequestions = 0;
    $myQuiz->grademethod = 1;
    $myQuiz->questiondecimalpoints = 2;
    $myQuiz->visible = 1;
    $myQuiz->questionsperpage = 1;
    $myQuiz->introeditor = array('text' => 'A matching quiz', 'format' => 1);

    //all of the review options
    $myQuiz->attemptduring = 1;
    $myQuiz->correctnessduring = 1;
    $myQuiz->marksduring = 1;
    $myQuiz->specificfeedbackduring = 1;
    $myQuiz->generalfeedbackduring = 1;
    $myQuiz->rightanswerduring = 1;
    $myQuiz->overallfeedbackduring = 1;

    $myQuiz->attemptimmediately = 1;
    $myQuiz->correctnessimmediately = 1;
    $myQuiz->marksimmediately = 1;
    $myQuiz->specificfeedbackimmediately = 1;
    $myQuiz->generalfeedbackimmediately = 1;
    $myQuiz->rightanswerimmediately = 1;
    $myQuiz->overallfeedbackimmediately = 1;

    $myQuiz->marksopen = 1;

    $myQuiz->attemptclosed = 1;
    $myQuiz->correctnessclosed = 1;
    $myQuiz->marksclosed = 1;
    $myQuiz->specificfeedbackclosed = 1;
    $myQuiz->generalfeedbackclosed = 1;
    $myQuiz->rightanswerclosed = 1;
    $myQuiz->overallfeedbackclosed = 1;

    //actually make the quiz using the function from course/lib.php

    $myQuiz2 = create_module($myQuiz);
    /*
        $mformclassname = 'mod_'.$module->name.'_mod_form';
        $mform = new $mformclassname($data, $cw->section, $cm, $course);
        $mform->set_data($data);

        if ($fromform = $mform->get_data()) {
            if (!empty($fromform->update)) {
                list($cm, $fromform) = update_moduleinfo($cm, $fromform, $course, $mform);
            } else if (!empty($fromform->add)) {
                $fromform = add_moduleinfo($fromform, $course, $mform);
            } else {
                throw new \moodle_exception('invaliddata');
            }
        }
    */
}
/*

//print_object($myQuiz2);

//get the last added random short answer matching question (which will likely be the one we just added)
$result = $DB->get_records('question',array('qtype'=>'randomsamatch'));
$keys = array_keys($result);
$count = count($keys);

//add the quiz question
quiz_add_quiz_question($result[$keys[$count-1]]->id, $myQuiz2, $page = 0, $maxmark = null);



$this->activities[7] = $this->getDataGenerator()->create_module('quiz', array('course'=>$this->course->id));
        $this->course_module[7] = get_coursemodule_from_instance('quiz', $this->activities[7]->id);
*/

/* adding questions to quiz:
question_bank::get_qtype($qtype)->save_question($question, $fromform);
$question needs to look like

        $question = new stdClass();
        $question->category  = $fromform->category;
        $question->qtype     = $qtype;
        $question->createdby = $USER->id;
*/

$output = $PAGE->get_renderer('block_exaquest');

echo $output->header($context, $courseid, get_string('exams_overview', 'block_exaquest'));

// RENDER:
$capabilities = [];
$capabilities["createquestions"] = is_enrolled($context, $USER, "block/exaquest:createquestion");

$exams = new \block_exaquest\output\exams($USER->id, $courseid, $capabilities);
echo $output->render($exams);

//echo '</div>';
echo $output->footer();
