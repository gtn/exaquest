<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class popup_exams_for_me_to_create implements renderable, templatable {
    /** @var string $exams_to_create Part of the data that should be passed to the template. */
    var $exams_to_create = null;

    public function __construct($exams_to_create) {
        $this->exams_to_create = $exams_to_create;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE;
        $data = new stdClass();
        // https://www.sitepoint.com/community/t/help-accessing-deep-level-json-in-mustache-template-solved/290780
        // https://stackoverflow.com/questions/35999024/how-to-iterate-an-array-of-objects-in-mustache
        //$data->questions_to_create_selfmade = [(object)["username"  => array_pop($this->questions_to_create)->username], (object)["username"  =>array_pop($this->questions_to_create)->username]];
        // this would work, but is not feasable to write like this
        // The problem with $data->questions_to_create = $this->questions_to_create; is that there is an associative array, e.g. 3 => stdClass(), 10 => stdClass() etc.... it MUST start counting at 0, otherwise it will break mustache
        $data->exams_to_create = array_values($this->exams_to_create);
        foreach ($data->exams_to_create as $id => $exam) {
            $exam->comma = true;
        }
        if (isset($data->exams_to_create) && !empty($data->exams_to_create)) {
            end($data->exams_to_create)->comma = false;
        }
        $data->action =
            $PAGE->url->out(false, array('action' => 'mark_as_done', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));

        $data->create_exam_link = new moodle_url('/course/modedit.php',
            array('add' => 'quiz', 'course' => $COURSE->id, 'section' => 0, 'return' => 0, 'sr' => 0));
        $data->create_exam_link = $data->create_exam_link->raw_out(false);

        $data->sesskey = sesskey();
        return $data;
    }
}
