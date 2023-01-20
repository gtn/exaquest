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
    ?>
    <script type="text/javascript">
        function changeFormAction() {
            document.getElementsByClassName("mform")[0].action = "../blocks/exaquest/create_or_update_exam_redirect.php";
        }
    </script>

    <?php

    // un-comment this, to have the button
    $mform->addElement('submit', 'saveandreturnexaquest', 'Save and return to the Exaquest page', 'onClick="changeFormAction()"');

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

