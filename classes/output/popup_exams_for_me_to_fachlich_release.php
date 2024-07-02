<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class popup_exams_for_me_to_fachlich_release implements renderable, templatable {

    public function __construct($exams_to_fachlich_release, $catAndCont) {
        $this->exams_to_fachlich_release = $exams_to_fachlich_release;
        $this->catAndCont = $catAndCont;
    }

    /**
     * Export this data, so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE;
        $data = new stdClass();

        // link to /mod/quiz/report.php?id=$quizid&mode=grading for every exam
        foreach ($this->exams_to_fachlich_release as $exam) {
            $exam->linktograding = new moodle_url('/blocks/exaquest/finished_exam_questionbank.php',
                array('courseid' => $COURSE->id,
                    'category' => $this->catAndCont[0] . ',' . $this->catAndCont[1],
                    'quizid' => $exam->quizid,
                ));
            $exam->linktograding = $exam->linktograding->raw_out(false);
        }

        // https://www.sitepoint.com/community/t/help-accessing-deep-level-json-in-mustache-template-solved/290780
        // https://stackoverflow.com/questions/35999024/how-to-iterate-an-array-of-objects-in-mustache
        //$data->questions_to_create_selfmade = [(object)["username"  => array_pop($this->questions_to_create)->username], (object)["username"  =>array_pop($this->questions_to_create)->username]];
        // this would work, but is not feasable to write like this
        // The problem with $data->questions_to_create = $this->questions_to_create; is that there is an associative array, e.g. 3 => stdClass(), 10 => stdClass() etc.... it MUST start counting at 0, otherwise it will break mustache
        $data->exams_to_fachlich_release = array_values($this->exams_to_fachlich_release);
        //foreach ($data->exams_to_fill as $id => $question) {
        //    $question->comma = true;
        //}
        //if (isset($data->exams_to_fill) && !empty($data->exams_to_fill)) {
        //    end($data->exams_to_fill)->comma = false;
        //}
        $data->action =
            $PAGE->url->out(false, array('action' => 'mark_as_done', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->sesskey = sesskey();
        $data->courseid = $COURSE->id;
        return $data;
    }
}
