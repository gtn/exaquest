<?php

namespace qbank_openquestionforreview;


use core_question\local\bank\column_base;

/**
 * A column that shows the current status of the question.
 *
 * @package   qbank_viewcreator
 * @copyright 2009 Tim Hunt
 * @author    2021 Ghaly Marc-Alexandre <marc-alexandreghaly@catalyst-ca.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class status_column extends column_base {

    public function get_name(): string {
        return 'status';
    }

    public function get_title(): string {
        return "Status";
    }

    protected function display_content($question, $rowclasses): void {

        switch (intval($question->teststatus)) {
            case BLOCK_EXAQUEST_QUESTIONSTATUS_IMPORTED:
                echo get_string("imported_question","block_exaquest");
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_NEW:
                echo get_string("new_question","block_exaquest");
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_TO_REVISE:
                echo get_string("to_revise","block_exaquest");
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_TO_ASSESS:
                echo get_string("to_assess","block_exaquest");
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_FORMAL_REVIEW_DONE:
                echo get_string("formal_done","block_exaquest");
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_FACHLICHES_REVIEW_DONE:
                echo get_string("fachlich_done","block_exaquest");
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_FINALISED:
                echo get_string("finalised","block_exaquest");
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_RELEASED:
                echo get_string("released","block_exaquest");
                break;
            case BLOCK_EXAQUEST_QUESTIONSTATUS_LOCKED:
                echo get_string("locked","block_exaquest");
                break;
        }
    }
}