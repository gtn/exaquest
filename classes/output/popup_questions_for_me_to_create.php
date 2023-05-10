<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;

class popup_questions_for_me_to_create implements renderable, templatable {
    /** @var string $questions_to_create Part of the data that should be passed to the template. */
    var $questions_to_create = null;

    public function __construct($questions_to_create) {
        $this->questions_to_create = $questions_to_create;
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
        $data->questions_to_create = array_values($this->questions_to_create);
        foreach ($data->questions_to_create as $id => $question) {
            $question->comma = true;
        }
        if (isset($data->questions_to_create) && !empty($data->questions_to_create)) {
            end($data->questions_to_create)->comma = false;
        }
        $data->action =
            $PAGE->url->out(false, array('action' => 'mark_as_done', 'sesskey' => sesskey(), 'courseid' => $COURSE->id));
        $data->sesskey = sesskey();
        $data->courseid = $COURSE->id;
        return $data;
    }
}