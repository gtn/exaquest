<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use renderable;
use renderer_base;
use stdClass;
use templatable;

class popup_change_status_warning implements renderable, templatable {
    /** @var string $fragenersteller Part of the data that should be passed to the template. */
    var $selectusers = null;
    var $name = null;
    var $questionbankentryid = null;
    var $action = null;

    public function __construct($action, $name, $question) {
        $this->name = $name;
        $this->questionbankentryid = $question->questionbankentryid;
        $this->action = $action;
        $this->questionname = $question->name;
        $this->questionid = $question->id;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE;
        $data = new stdClass();
        $data->name = $this->name;
        $data->text = get_string('release_question_warning', 'block_exaquest');
        $data->title = get_string('release_question_warning_title', 'block_exaquest');
        $data->questionbankentryid = $this->questionbankentryid;
        $data->questionname = $this->questionname;
        $data->disabled = "";
        $data->dataToggle = "modal";
        // disable the button if not all categorytypes are assigned
        if (!block_exaquest_check_if_question_contains_categories($this->questionid)) {
            $data->disabled = "disabled";
            $data->dataToggle = "tooltip";
            $data->tooltip = get_string('missing_category_tooltip', 'block_exaquest');
        }


        $data->action = $this->action;
        $data->sesskey = sesskey();
        return $data;
    }
}
