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

require_once __DIR__ . '/inc.php';

/**
 * Inject the exaquest element into all moodle module settings forms.
 *
 * @param moodleform $formwrapper The moodle quickforms wrapper object.
 * @param MoodleQuickForm $mform The actual form object (required to modify the form).
 */
function block_exaquest_coursemodule_definition_after_data($formwrapper, $mform) {
    global $CFG, $COURSE, $DB, $PAGE;

    // only add the elements if the course module is a quiz
    if (is_exaquest_active_in_course()) {
        // add button to quiz
        if ($mform->_formName == 'mod_quiz_mod_form') {
            ?>
            <script type="text/javascript">
                function changeFormActionExaquest() {
                    document.getElementsByClassName("mform")[0].action = "../blocks/exaquest/create_or_update_exam_redirect.php";
                }
            </script>
            <?php
            $mform->addElement('submit', 'saveandreturnexaquest', get_string('save_and_return', 'block_exaquest'),
                'onClick="changeFormActionExaquest()"');
        }
    }

    return;
}

// Adds elements to the course module settings form
function block_exaquest_coursemodule_standard_elements($formwrapper, $mform) {
    global $CFG, $COURSE, $DB, $PAGE;
    if (is_exaquest_active_in_course()) {
        if ($mform->_formName == 'mod_quiz_mod_form') {

            // add the Maximum grade setting
            // from public function maximum_grade_input in mod/quiz/classes/output/edit_renderer.php
            //$output = '';
            //$output .= html_writer::start_div('maxgrade');
            //$output .= html_writer::start_tag('form', ['method' => 'post', 'action' => 'edit.php',
            //        'class' => 'quizsavegradesform form-inline']);
            //$output .= html_writer::start_tag('fieldset', ['class' => 'invisiblefieldset']);
            //$output .= html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
            ////$output .= html_writer::input_hidden_params($pageurl);
            //$output .= html_writer::tag('label', get_string('maximumgrade') . ' ',
            //        ['for' => 'inputmaxgrade']);
            ////$output .= html_writer::empty_tag('input', ['type' => 'text', 'id' => 'inputmaxgrade',
            ////        'name' => 'maxgrade', 'size' => ($structure->get_decimal_places_for_grades() + 2),
            ////        'value' => $structure->formatted_quiz_grade(),
            ////        'class' => 'form-control']);
            //$output .= html_writer::empty_tag('input', ['type' => 'submit', 'class' => 'btn btn-secondary ml-1',
            //        'name' => 'savechanges', 'value' => get_string('save', 'quiz')]);
            //$output .= html_writer::end_tag('fieldset');
            //$output .= html_writer::end_tag('form');
            //$output .= html_writer::end_tag('div');

            // from lib/form/modgrade.php
            // Maximum grade textbox.
            //$langmaxgrade = get_string('modgrademaxgrade', 'grades');
            //$this->maxgradeformelement = $this->createFormElement('text', 'modgrade_point', $langmaxgrade, array());
            //$this->maxgradeformelement->setHiddenLabel(true);
            //$maxgradeformelementid = $this->generate_modgrade_subelement_id('modgrade_point');
            //$this->maxgradeformelement->updateAttributes(array('id' => $maxgradeformelementid));


            $quizid = $formwrapper->get_instance();

            $mform->addElement('header', 'exaquest_settings', get_string('exaquest_settings', 'block_exaquest'));
            $mform->setExpanded('exaquest_settings');

            // Add the fachlicherpruefer setting
            $fachlichepruefer =
                block_exaquest_get_fachlichepruefer_by_courseid($COURSE->id); // for now I assume: there are only fachlichepruefer, there are no other roles, just other assignments
            if ($fachlichepruefer) {
                $fachlichepruefer_options = array();
                foreach ($fachlichepruefer as $fachlicherpruefer) {
                    $fachlichepruefer_options[$fachlicherpruefer->id] =
                        $fachlicherpruefer->firstname . ' ' . $fachlicherpruefer->lastname;
                }
                $mform->addElement('select', 'assignfachlicherpruefer', 'Fachlichen Prüfer auswählen', $fachlichepruefer_options);
                //$mform->addElement('select', 'assignfachlicherzweitpruefer', 'Fachlichen Zweitprüfer auswählen',
                //        array_merge(array(''), $fachlichepruefer_options));
                //$mform->addElement('select', 'assignfachlicherdrittpruefer', 'Fachlichen Drittprüfer auswählen',
                //        array_merge(array(''), $fachlichepruefer_options));
                if ($quizid) {
                    // get the assigned fachlicherprüfer
                    $assignedfachlicherpruefer = block_exaquest_get_assigned_fachlicherpruefer($quizid);
                    $mform->setDefault('assignfachlicherpruefer', $assignedfachlicherpruefer->assigneeid);

                    //$assignedfachlicherpruefer = block_exaquest_get_assigned_fachlicherzweitpruefer($quizid);
                    //$mform->setDefault('assignfachlicherzweitpruefer', $assignedfachlicherpruefer->assigneeid);
                    //
                    //$assignedfachlicherpruefer = block_exaquest_get_assigned_fachlicherdrittpruefer($quizid);
                    //$mform->setDefault('assignfachlicherdrittpruefer', $assignedfachlicherpruefer->assigneeid);
                }
            }

            //$fachlichezweitpruefer = block_exaquest_get_fachlichezweitpruefer_by_courseid($COURSE->id);
            //if ($fachlichezweitpruefer) {
            //    $fachlichepruefer_options = array();
            //    foreach ($fachlichezweitpruefer as $fachlicherpruefer) {
            //        $fachlichepruefer_options[$fachlicherpruefer->id] =
            //                $fachlicherpruefer->firstname . ' ' . $fachlicherpruefer->lastname;
            //    }
            //    $mform->addElement('select', 'assignfachlicherzweitpruefer', 'Fachlichen Zweitprüfer auswählen', $fachlichepruefer_options);
            //    if ($quizid) {
            //        $assignedfachlicherpruefer = block_exaquest_get_assigned_fachlicherzweitpruefer($quizid);
            //        $mform->setDefault('assignfachlicherzweitpruefer', $assignedfachlicherpruefer->assigneeid);
            //    }
            //}
            //
            //$fachlichedrittpruefer = block_exaquest_get_fachlichedrittpruefer_by_courseid($COURSE->id);
            //if ($fachlichedrittpruefer) {
            //    $fachlichepruefer_options = array();
            //    foreach ($fachlichedrittpruefer as $fachlicherpruefer) {
            //        $fachlichepruefer_options[$fachlicherpruefer->id] =
            //                $fachlicherpruefer->firstname . ' ' . $fachlicherpruefer->lastname;
            //    }
            //    $mform->addElement('select', 'assignfachlicherdrittpruefer', 'Fachlichen Drittprüfer auswählen', $fachlichepruefer_options);
            //    if ($quizid) {
            //        $assignedfachlicherpruefer = block_exaquest_get_assigned_fachlicherdrittpruefer($quizid);
            //        $mform->setDefault('assignfachlicherdrittpruefer', $assignedfachlicherpruefer->assigneeid);
            //    }
            //}

            // add the question count per quiz and fragefach settings
            if ($quizid) {
                $fragefaecher = block_exaquest_get_fragefaecher_by_courseid_and_quizid($COURSE->id, $quizid, false);
            } else {
                $fragefaecher = block_exaquest_get_fragefaecher_by_courseid($COURSE->id, false);
            }

            // Add the quiz questions count / points count settings
            // for every fragefach, add one input
            foreach ($fragefaecher as $fragefach) {
                $mform->addElement('text', 'exaquestquestioncategoryid' . $fragefach->id,
                    get_string('points_per', 'block_exaquest') . $fragefach->categoryname,
                    array('size' => '10'));
                $mform->setType('exaquestquestioncategoryid' . $fragefach->id, PARAM_INT);
                if ($quizid) {
                    $mform->setDefault('exaquestquestioncategoryid' . $fragefach->id, $fragefach->questioncount);
                } else {
                    $mform->setDefault('exaquestquestioncategoryid' . $fragefach->id, 0);
                }
            }
        }
    }
}

// Processes the data from the course module settings form
function block_exaquest_coursemodule_edit_post_actions($data, $course) {
    global $USER;
    if (is_exaquest_active_in_course()) {
        $quizid = $data->instance;

        // from mod/quiz/edit.php
        //    // If rescaling is required save the new maximum.
        //    $maxgrade = unformat_float(optional_param('maxgrade', '', PARAM_RAW_TRIMMED), true);
        //    if (is_float($maxgrade) && $maxgrade >= 0) {
        //        $gradecalculator->update_quiz_maximum_grade($maxgrade);
        //    }
        //$quizobj = new quiz_settings($quiz, $cm, $course);
        //$gradecalculator = $quizobj->get_grade_calculator();
        //    $maxgrade = unformat_float(optional_param('maxgrade', '', PARAM_RAW_TRIMMED), true);
        //    if (is_float($maxgrade) && $maxgrade >= 0) {
        //        $gradecalculator->update_quiz_maximum_grade($maxgrade);
        //    }


        // set the question count per fragefach
        $fragefaecher = block_exaquest_get_fragefaecher_by_courseid($course->id);
        foreach ($fragefaecher as $fragefach) {
            $setting = 'exaquestquestioncategoryid' . $fragefach->id;
            if (isset($data->$setting)) {
                block_exaquest_set_questioncount_for_exaquestcategory($quizid, $fragefach->id, $data->$setting);
            }
        }

        // set the fachlicherprüfer
        if ($data->assignfachlicherpruefer) {
            block_exaquest_quizassign($USER, $data->assignfachlicherpruefer, "", $quizid, BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERPRUEFER);
            //block_exaquest_quizassign($USER, $data->assignfachlicherzweitpruefer, "", $quizid, BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERZWEITPRUEFER);
            //block_exaquest_quizassign($USER, $data->assignfachlicherdrittpruefer, "", $quizid, BLOCK_EXAQUEST_QUIZASSIGNTYPE_FACHLICHERDRITTPRUEFER);
        }
    }

    return $data;
}

function block_exaquest_extend_question_definition() {
    global $CFG, $COURSE;
    return;
}

/**
 * Inject the exaquest element into all moodle module settings forms.
 *
 * @param moodleform $formwrapper The moodle quickforms wrapper object.
 * @param MoodleQuickForm $mform The actual form object (required to modify the form).
 */
function block_exaquest_question_definition_after_data($formwrapper, $mform) {
    global $CFG, $COURSE, $DB, $PAGE;

    return;
}

/**
 * Inject the exaquest element into all moodle module settings forms.
 *
 * @param moodleform $formwrapper The moodle quickforms wrapper object.
 * @param MoodleQuickForm $mform The actual form object (required to modify the form).
 */
function block_exaquest_instance_form_definition_after_data($formwrapper, $mform) {
    global $CFG, $COURSE, $DB, $PAGE;

    return;
}


///**
// * Inject the exaquest element into all moodle module settings forms.
// *
// * @param moodleform $formwrapper The moodle quickforms wrapper object.
// * @param MoodleQuickForm $mform The actual form object (required to modify the form).
// */
//function block_exaquest_quiz_standard_elements($formwrapper, $mform) {
//    global $CFG, $COURSE, $DB, $PAGE;
//
//    $cmid = optional_param('update', 0, PARAM_INT);
//    $exacompUseAutoCompetencesVal = block_exacomp_cmodule_is_autocompetence($cmid);
//    $mform->addElement('checkbox', 'exacompUseAutoCompetences', block_exacomp_get_string('module_used_availabilitycondition_competences'));
//    $mform->setType('exacompUseAutoCompetences', PARAM_INT);
//    if ($exacompUseAutoCompetencesVal) {
//        $mform->setDefault('exacompUseAutoCompetences', true);
//    }
//    // sorting all elements - we need to add our element before 'Restrict access' element
//    $allelements = $mform->_elementIndex;
//    //$DB->delete_records('block_exacompcmsettings', ['name' => 'exacompUseAutoCompetnces']);
//    $exacompElementInd = $allelements['exacompUseAutoCompetences'];
//    $exacompElement = $mform->_elements[$exacompElementInd];
//    unset($mform->_elements[$exacompElementInd]);
//    if (array_key_exists('availabilityconditionsjson', $allelements)) {
//        $avacondintionsElementInd = $allelements['availabilityconditionsjson'];
//        // go insert
//        array_splice($mform->_elements, $avacondintionsElementInd, 0, array($exacompElement)); // splice in at position 3
//
//        // reformat indexes
//        foreach ($mform->_elements as $key => $el) {
//            if ($el->_attributes && $el->_attributes['name']) {
//                $mform->_elementIndex[$el->_attributes['name']] = $key;
//            } else if ($el->_name) {
//                $mform->_elementIndex[$el->_name] = $key;
//            }
//        }
//    }
//
//
//    return;
//}
