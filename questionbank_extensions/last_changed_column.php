<?php

namespace qbank_openquestionforreview;


use core_question\local\bank\column_base;

/**
 * A column type for the name of the question creator.
 *
 * @package   qbank_viewcreator
 * @copyright 2009 Tim Hunt
 * @author    2021 Ghaly Marc-Alexandre <marc-alexandreghaly@catalyst-ca.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class last_changed_column extends column_base {

    public function get_name(): string {
        return 'lastchanged';
    }

    public function get_title(): string {
        return get_string('lastchanged', 'block_exaquest');
    }

    protected function display_content($question, $rowclasses): void {
        global $PAGE;
        $displaydata = [];

        if (!empty($question->creatorfirstname) && !empty($question->creatorlastname)) {
            $u = new \stdClass();
            $u = username_load_fields_from_object($u, $question, 'creator');
            $displaydata['date'] = userdate($question->timecreated, get_string('strftimedatetime', 'langconfig'));
            $displaydata['creator'] = fullname($u);
            echo $PAGE->get_renderer('qbank_viewcreator')->render_creator_name($displaydata);
        }
    }

    public function get_extra_joins(): array {
        return ['uc' => 'LEFT JOIN {user} uc ON uc.id = q.createdby'];
    }

    public function get_required_fields(): array {
        $allnames = \core_user\fields::get_name_fields();
        $requiredfields = [];
        foreach ($allnames as $allname) {
            $requiredfields[] = 'uc.' . $allname . ' AS creator' . $allname;
        }
        $requiredfields[] = 'q.timecreated';
        return $requiredfields;
    }

    public function is_sortable(): array {
        return [
            'firstname' => ['field' => 'uc.firstname', 'title' => get_string('firstname')],
            'lastname' => ['field' => 'uc.lastname', 'title' => get_string('lastname')],
            'timecreated' => ['field' => 'q.timecreated', 'title' => get_string('date')]
        ];
    }

    public function get_extra_classes(): array {
        return ['pr-3'];
    }

}