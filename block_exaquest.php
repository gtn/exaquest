<?php

require __DIR__ . '/inc.php';

class block_exaquest extends block_list {
    public function init() {
        $this->title = get_string('exaquest', 'block_exaquest');
    }

    public function get_content() {
        global $CFG, $COURSE, $PAGE, $DB, $USER;

        $context = context_block::instance($this->instance->id);
        //$context = context_system::instance(); // TODO: system? or block? think of dashboard. for now solved with viewdashboardoutsidecourse cap

        // get all courses where exaquest is added as a block:
        $courses = block_exaquest_get_relevant_courses_for_user($USER->id);

        $hascapability_in_some_course = false;
        foreach ($courses as $c) {
            if (has_capability('block/exaquest:viewdashboardoutsidecourse', context_course::instance($c->id))) {
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
            //$this->content->body = html_writer::tag('div', '', array('id' => 'block_exaquest block list_block mb-3'));
            $this->content->items = array();
            $this->content->icons = array();
            $this->content->footer = '';

            $previous_coursecat = 0;
            if ($PAGE->pagelayout == "mydashboard") {
                // get all courses where exaquest is added as a blocK:
                //$courseids = block_exaquest_get_courseids();
                foreach ($courses as $c) {
                    // if the $previous_coursecat is not the same as the $c->category then print the category name
                    if ($previous_coursecat != $c->category) {
                        $previous_coursecat = $c->category;
                        $categoryname = $DB->get_record('course_categories', array('id' => $c->category))->name;
                        $this->content->items[] = html_writer::tag('div', $categoryname, array('class' => 'mb-2'));
                    }


                    $coursename = $c->fullname;
                    $questioncategoryid = get_question_category_and_context_of_course($c->id)[0];
                    $todocount = block_exaquest_get_todo_count($USER->id, $c->category, $questioncategoryid, context_course::instance($c->id), $c->id);
                    if ($todocount) {
                        $todocountmesssage = '<span class="badge badge-primary ml-3 badge-lg">' .
                            $todocount . get_string('todos_are_open', 'block_exaquest') . ' </span>';
                    } else {
                        $todocountmesssage = '';
                    }

                    $combinedmessage =
                        html_writer::tag('a', get_string('dashboard_of_course', 'block_exaquest', $coursename) . $todocountmesssage,
                            array('href' => $CFG->wwwroot . '/blocks/exaquest/dashboard.php?courseid=' . $c->id));
                    $combinedmessage = html_writer::tag('div', $combinedmessage, array('class' => 'mb-2'));
                    $this->content->items[] = $combinedmessage;
                }
            } else {
                // this is used to get the contexts of the category in the questionbank
                $catAndCont = get_question_category_and_context_of_course();
                // this is to get the button for creating a new question
                //$this->content->items[] = editquestion_helper::create_new_question_button(2, array('courseid' => $COURSE->id), true);
                $this->content->items[] = html_writer::tag('a', get_string('dashboard', 'block_exaquest'),
                    array('href' => $CFG->wwwroot . '/blocks/exaquest/dashboard.php?courseid=' . $COURSE->id));
                if (has_capability('block/exaquest:viewquestionbanktab', \context_course::instance($COURSE->id))) {
                    $this->content->items[] = html_writer::tag('a', get_string('get_questionbank', 'block_exaquest'),
                        array('href' => $CFG->wwwroot . '/blocks/exaquest/questbank.php?courseid=' . $COURSE->id . '&category=' .
                            $catAndCont[0] . '%2C' . $catAndCont[1]));
                }
                if (has_capability('block/exaquest:viewsimilaritytab', \context_course::instance($COURSE->id))) {
                    $this->content->items[] = html_writer::tag('a', get_string('similarity', 'block_exaquest'),
                        array('href' => $CFG->wwwroot . '/blocks/exaquest/similarity_comparison.php?courseid=' . $COURSE->id .
                            '&category=' .
                            $catAndCont[0] . '%2C' . $catAndCont[1]));
                }
                if (has_capability('block/exaquest:viewexamstab', \context_course::instance($COURSE->id))) {
                    $this->content->items[] = html_writer::tag('a', get_string('exams', 'block_exaquest'),
                        array('href' => $CFG->wwwroot . '/blocks/exaquest/exams.php?courseid=' . $COURSE->id));
                }
                if (has_capability('block/exaquest:viewcategorytab', \context_course::instance($COURSE->id))) {
                    $this->content->items[] = html_writer::tag('a', get_string('category_settings', 'block_exaquest'),
                        array('href' => $CFG->wwwroot . '/blocks/exaquest/category_settings.php?courseid=' . $COURSE->id));
                }
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
