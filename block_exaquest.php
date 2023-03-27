<?php

require __DIR__ . '/inc.php';

class block_exaquest extends block_list {
    public function init() {
        $this->title = get_string('exaquest', 'block_exaquest');
    }

    public function get_content() {
        global $CFG, $COURSE, $PAGE, $DB, $USER;

        $context = context_block::instance($this->instance->id);
        //$context = context_system::instance(); // todo: system? or block? think of dashboard. for now solved with viewdashboardoutsidecourse cap

        // get all courses where exaquest is added as a block:
        $courseids = block_exaquest_get_courseids();
        $hascapability_in_some_course = false;
        foreach ($courseids as $courseid) {
            if (has_capability('block/exaquest:viewdashboardoutsidecourse', context_course::instance($courseid))) {
                $hascapability_in_some_course = true;
            }
        }
        if (has_capability('block/exaquest:view', $context) || $hascapability_in_some_course) {
            if ($this->content !== null) {
                return $this->content;
            }

            $PAGE->requires->css('/blocks/exaquest/css/block_exaquest.css', true);
            //$PAGE->requires->jquery();
            //$PAGE->requires->js("/blocks/exaquest/javascript/block_exaquest.js", true);

            $this->content = new stdClass;
            $this->content->items = array();
            $this->content->icons = array();
            $this->content->footer = '';

            if ($PAGE->pagelayout == "mydashboard") {
                // get all courses where exaquest is added as a blocK:
                //$courseids = block_exaquest_get_courseids();
                foreach ($courseids as $courseid) {
                    $course = get_course($courseid);
                    $coursename = $course->fullname;
                    $todocount = block_exaquest_get_todo_count($USER->id, $course->category);
                    if ($todocount) {
                        $todocountmesssage = ' ... ' . $todocount . get_string('todos_are_open', 'block_exaquest');
                    } else {
                        $todocountmesssage = '';
                    }

                    $this->content->items[] =
                        html_writer::tag('a', get_string('dashboard_of_course', 'block_exaquest', $coursename) . $todocountmesssage,
                            array('href' => $CFG->wwwroot . '/blocks/exaquest/dashboard.php?courseid=' . $courseid));
                }
            } else {
                // this is used to get the contexts of the category in the questionbank
                $catAndCont = get_question_category_and_context_of_course();
                // this is to get the button for creating a new question
                //$this->content->items[] = editquestion_helper::create_new_question_button(2, array('courseid' => $COURSE->id), true);
                $this->content->items[] = html_writer::tag('a', get_string('dashboard', 'block_exaquest'),
                    array('href' => $CFG->wwwroot . '/blocks/exaquest/dashboard.php?courseid=' . $COURSE->id));
                $this->content->items[] = html_writer::tag('a', get_string('get_questionbank', 'block_exaquest'),
                    array('href' => $CFG->wwwroot . '/blocks/exaquest/questbank.php?courseid=' . $COURSE->id . '&category=' .
                        $catAndCont[0] . '%2C' . $catAndCont[1]));
                // TODO: add custom plugin here
                $this->content->items[] = html_writer::tag('a', get_string('similarity', 'block_exaquest'),
                    array('href' => $CFG->wwwroot . '/blocks/exaquest/similarity_comparison.php?courseid=' . $COURSE->id .
                        '&category=' .
                        $catAndCont[0] . '%2C' . $catAndCont[1]));
                $this->content->items[] = html_writer::tag('a', get_string('exams', 'block_exaquest'),
                    array('href' => $CFG->wwwroot . '/blocks/exaquest/exams.php?courseid=' . $COURSE->id));
                $this->content->items[] = html_writer::tag('a', get_string('category_settings', 'block_exaquest'),
                    array('href' => $CFG->wwwroot . '/blocks/exaquest/category_settings.php?courseid=' . $COURSE->id));
            }
        } else {
            return null;
        }

        return $this->content;
    }

    /**
     * Allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    /**
     * Return the plugin config settings for external functions.
     *
     * @return stdClass the configs for both the block instance and plugin
     * @throws dml_exception
     * @since Moodle 3.8
     */
    public function get_config_for_external() {
        // Return all settings for all users since it is safe (no private keys, etc..).
        $instanceconfigs = !empty($this->config) ? $this->config : new stdClass();
        $pluginconfigs = get_config('block_exaquest');

        return (object) [
            'instance' => $instanceconfigs,
            'plugin' => $pluginconfigs,
        ];
    }

    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.
}
