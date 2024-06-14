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

global $CFG;
defined('MOODLE_INTERNAL') || die();

require_once __DIR__ . '/inc.php';
// require_once($CFG->libdir.'/adminlib.php');
require_once("$CFG->libdir/formslib.php");


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


            // $mform->addElement(new admin_setting_heading('progress', new lang_string('completion','completion'), ''));
            // $mform->addElement(new admin_setting_configselect('moodlecourse/enablecompletion', new lang_string('completion', 'completion'),
            //     new lang_string('enablecompletion_help', 'completion'), 1, array(0 => new lang_string('no'), 1 => new lang_string('yes'))));

            // $a = new admin_setting_heading('progress', new lang_string('completion','completion'), '');
            // $b = new admin_setting_configselect('moodlecourse/enablecompletion', new lang_string('completion', 'completion'),
            //     new lang_string('enablecompletion_help', 'completion'), 1, array(0 => new lang_string('no'), 1 => new lang_string('yes')));


            // before printing the fragefaecher: write a title that explains what to do
            $mform->addElement('html', '<h5>' . get_string('points_per', 'block_exaquest') . '</h5>');
            // Add the quiz questions count / points count settings
            // for every fragefach, add one input
            foreach ($fragefaecher as $fragefach) {
                $mform->addElement('text', 'exaquest_points_per_fragefach' . $fragefach->id, $fragefach->categoryname, array('size' => '10'));
                // add help button
                $mform->addHelpButton('exaquest_points_per_fragefach' . $fragefach->id, 'points_per', 'block_exaquest');
                $mform->setType('exaquest_points_per_fragefach' . $fragefach->id, PARAM_INT);
                if ($quizid) {
                    $mform->setDefault('exaquest_points_per_fragefach' . $fragefach->id, $fragefach->questioncount);
                } else {
                    $mform->setDefault('exaquest_points_per_fragefach' . $fragefach->id, 0);
                }


            }


            // before printing the fragefaecher: write a title that explains what to do
            $mform->addElement('html', '<h5>' . get_string('exaquest_minimum_required_percentage_per_fragefach', 'block_exaquest') . '</h5>');
            // Add the minimum percentage a student needs per fragefach to pass the exam.
            // for every fragefach, add one input
            foreach ($fragefaecher as $fragefach) {
                $mform->addElement('text', 'exaquest_minimum_required_percentage_per_fragefach' . $fragefach->id, $fragefach->categoryname, array('size' => '10'));
                // add help button
                $mform->addHelpButton('exaquest_minimum_required_percentage_per_fragefach' . $fragefach->id, 'minimum_percentage', 'block_exaquest');
                $mform->setType('exaquest_minimum_required_percentage_per_fragefach' . $fragefach->id, PARAM_INT);
                if ($quizid) {
                    $mform->setDefault('exaquest_minimum_required_percentage_per_fragefach' . $fragefach->id, $fragefach->percentage);
                } else {
                    $mform->setDefault('exaquest_minimum_required_percentage_per_fragefach' . $fragefach->id, 0);
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


        $fragefaecher = block_exaquest_get_fragefaecher_by_courseid($course->id);
        foreach ($fragefaecher as $fragefach) {
            // set the question count per fragefach
            $setting = 'exaquest_points_per_fragefach' . $fragefach->id;
            if (isset($data->$setting)) {
                block_exaquest_set_questioncount_for_exaquestcategory($quizid, $fragefach->id, $data->$setting);
            }

            // set the minimum required percentage per fragefach
            $setting = 'exaquest_minimum_required_percentage_per_fragefach' . $fragefach->id;
            if (isset($data->$setting)) {
                block_exaquest_set_minimum_required_percentage_for_exaquestcategory($quizid, $fragefach->id, $data->$setting);
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

function block_exaquest_before_standard_html_head() {
    global $CFG, $PAGE;

    // get path within the moodle dir + fix slashes in windows
    $subpath = str_replace(
        str_replace('\\', '/', $CFG->dirroot),
        '',
        str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']));

    $js = '';

    $PAGE->requires->js('/blocks/exaquest/js/exaquest.js');

    if ($subpath == '/question/bank/editquestion/question.php') {
        // TODO: über $PAGE->context kann man die kurs-kategorie rausfinden und die Logik nur dann aktivieren

        // beim Anlegen von neuen Fragen:
        // * Felder ausblenden
        // * Default Werte befüllen
        // * Expertenmodus
        ob_start();
        ?>
        <script>
            require(['jquery'], function ($) {
                var $form = $('form[data-qtype]');

                var isExpertMode = localStorage.getItem('exaquest_expert_mode') == '1';
                var isNewQuestion = $form.find(':input[name="id"]').val() == 0; // true for '' and '0'
                var isLocalDev = window.location.hostname == 'localhost';

                function get_field(name) {
                    var $field = $form.find(':input[name="' + name + '"]');

                    if ($field.filter('[type="checkbox"]').length) {
                        $field = $field.not('[type="hidden"]');
                    }

                    if ($field.length == 0) {
                        console.error('field ' + name + ' not found');
                    }
                    if ($field.length > 1) {
                        console.error('field ' + name + ' found ' + $field.length + ' times');
                    }

                    return $field;
                }

                function toggle_field(name) {
                    get_field(name).closest('.row').toggle(isExpertMode);
                }

                // chatgpt
                function roundToSixSignificantDigits(num) {
                    let rounded = num.toPrecision(7);

                    // Convert to number and then back to string to remove unnecessary trailing zeros
                    let result = Number(rounded).toString();

                    // Check if the result is an integer (no decimal point present)
                    if (!result.includes('.')) {
                        result += '.0'; // Add .0 to ensure at least one digit after the decimal point
                    }

                    return result;
                }

                var $bewertungDivs = $form.find('.form-group.row[id*=fraction_]');

                // berechnet den Ausgewählten Wert der Select Dropdowns erneut
                function updateBewertungen() {
                    var richtigeAntwortenCnt = $bewertungDivs.find('input:checked').length;

                    $bewertungDivs.map(function (i, div) {
                        var $div = $(div);
                        var $select = $div.find('select');
                        var $checkbox = $div.find('input[type="checkbox"], input[type="radio"]');

                        if ($checkbox.prop('checked')) {
                            $select.val(roundToSixSignificantDigits(1 / richtigeAntwortenCnt));
                        } else {
                            $select.val('0.0');
                        }
                    });
                }

                function updateFields() {
                    toggle_field('answernumbering');
                    toggle_field('status');
                    toggle_field('defaultmark');
                    toggle_field('generalfeedback[text]');
                    toggle_field('idnumber');
                    toggle_field('showstandardinstruction');

                    toggle_field('shuffleanswers');
                    toggle_field('feedback[0][text]');
                    toggle_field('feedback[1][text]');
                    toggle_field('feedback[2][text]');
                    toggle_field('feedback[3][text]');
                    toggle_field('feedback[4][text]');

                    $form.find('.form-group.row[id*=fraction_]')
                        .css('margin-bottom', isExpertMode ? '' : '2em') // margin zwischen den Antworten fixen
                        .each(function () {
                            // border bottom anzeigen (gleicher style wie borderLeft verwenden)
                            $(this).css('border-bottom', isExpertMode ? '' : window.getComputedStyle(this).borderLeft);
                        });

                    $form.find('fieldset#id_combinedfeedbackhdr').toggle(isExpertMode);
                    $form.find('fieldset#id_multitriesheader').toggle(isExpertMode);

                    // TODO: "nur eine Option für Tags" - was ist damit gemeint?!?

                    $bewertungDivs.find('.richtig_falsch').remove();
                    if (isExpertMode) {
                        $bewertungDivs.find('select').show();
                    } else {
                        $bewertungDivs.find('select').hide();

                        $bewertungDivs.find('select').each(function (i) {
                            var $select = $(this);

                            // Single Choice - "Nur eine Antwort erlauben"
                            // Multiple Choice - "Mehrere erlauben...
                            $('<label class="richtig_falsch"><input name="richtig_falsch_input" value="' + i + '" type="' + (get_field('single').val() == '1' ? 'radio' : 'checkbox') + '">' +
                                '&nbsp;&nbsp;richtig</label>')
                                .insertAfter($select)
                                .find('input')
                                .prop('checked', $select.val() > 0)
                                .click(updateBewertungen);
                        });
                    }
                }

                updateFields();

                get_field('single').change(function () {
                    updateFields();

                    if (!isExpertMode) {
                        updateBewertungen();
                    }
                })

                if (isNewQuestion) {
                    // TODO: immer automatisch das Modul auswählen, in dem die Frage erstellt wird

                    // diese sind eigentlich eh die default Werte. d.h. Logik nicht unbedingt notwendig
                    get_field('answernumbering').val('abc');
                    get_field('defaultmark').val('1');
                    get_field('shuffleanswers').prop('checked', true);

                    // Änderungen am Formular:
                    get_field('showstandardinstruction').val('1');
                }

                // expert modus switch
                var $button = $('<div style="display: inline-block"><div style="display: flex">' +
                    '    <label class="ml-4 mr-2 mb-0 " for="expert_mode_switch">' +
                    '        Experten Modus' +
                    '    </label>' +
                    '    <div class="custom-control custom-switch">' +
                    '        <input type="checkbox" name="setmode" id="expert_mode_switch" class="custom-control-input">' +
                    '        <span class="custom-control-label">&nbsp;</span>' +
                    '    </div>' +
                    '</div></div>');
                $button.find(':checkbox').prop('checked', isExpertMode);

                $button.on('click', function (e) {
                    e.preventDefault();

                    isExpertMode = !isExpertMode;
                    localStorage.setItem('exaquest_expert_mode', isExpertMode ? '1' : '0');

                    $(this).find(':checkbox').prop('checked', isExpertMode);

                    updateFields();
                });
                $('.collapsible-actions').append($button);
            });
        </script>
        <?php
        $js .= ob_get_clean();
    }

    // remove the script tag, which was used just for the editor to format the code nicely
    $js = trim(preg_replace('!</?script>!i', '', $js));

    if ($js) {
        $PAGE->requires->js_init_code($js);
    }
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
