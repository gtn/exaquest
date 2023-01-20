<?php
require __DIR__ . '/inc.php';

global $DB, $CFG, $COURSE, $PAGE, $OUTPUT, $USER;

require_once($CFG->dirroot . '/question/editlib.php');
require_once(__DIR__ . '/questionbank_extensions/exaquest_view.php');



$courseid = required_param('courseid', PARAM_INT);

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
