<?php
// Standard GPL and phpdocs
namespace block_exaquest\output;

use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

class dashboard implements renderable, templatable {
    var $questions = null;
    private $capabilities;
    private $courseid;
    private $userid;
    private $request_questions_popup;
    private $questions_for_me_to_create_popup;
    private $coursecategoryid;

    public function __construct($userid, $courseid, $capabilities, $fragenersteller, $questions_to_create, $coursecategoryid,
        $fachlichepruefer, $exams_to_create) {
        $this->courseid = $courseid;
        $this->capabilities = $capabilities;
        $this->userid = $userid;
        //$this->questions = $questions;
        // when using subtemplates: call them HERE and add the capabilities and other data that is needed in the parameters
        // ... see "class search_form implements renderable, templatable {"
        //$this->fragenersteller = $fragenersteller; // not needed here, since it is given to popup_request_questions and only needed there
        $this->request_questions_popup = new popup_request_questions($fragenersteller);
        $this->questions_for_me_to_create_popup = new popup_questions_for_me_to_create($questions_to_create);
        $this->exams_for_me_to_create_popup = new popup_exams_for_me_to_create($exams_to_create);
        $this->coursecategoryid = $coursecategoryid;
        $this->request_exams_popup = new popup_request_exams($fachlichepruefer);
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE, $COURSE;
        $data = new stdClass();
        $data->capabilities = $this->capabilities;
        $data->questions_count = block_exaquest_get_questionbankentries_by_coursecategoryid_count($this->coursecategoryid);
        $data->questions_to_review_count = block_exaquest_get_questionbankentries_to_be_reviewed_count($this->coursecategoryid);
        $data->questions_fachlich_reviewed_count =
            block_exaquest_get_questionbankentries_fachlich_reviewed_count($this->coursecategoryid);
        $data->questions_formal_reviewed_count =
            block_exaquest_get_questionbankentries_formal_reviewed_count($this->coursecategoryid);
        $data->questions_finalised_count = block_exaquest_get_finalised_questionbankentries_count($this->coursecategoryid);
        $data->questions_released_count = block_exaquest_get_released_questionbankentries_count($this->coursecategoryid);
        $data->questions_released_and_to_review_count =
            block_exaquest_get_released_and_to_review_questionbankentries_count($this->coursecategoryid);
        $data->questions_to_revise_count =
            block_exaquest_get_questionbankentries_to_be_revised_count($this->coursecategoryid);
        $data->questions_new_count =
            block_exaquest_get_questionbankentries_new_count($this->coursecategoryid);

        $data->questions_for_me_to_create_count =
            block_exaquest_get_questions_for_me_to_create_count($this->coursecategoryid, $this->userid);
        $data->questions_for_me_to_review_count =
            block_exaquest_get_questions_for_me_to_review_count($this->coursecategoryid, $this->userid);
        $data->questions_for_me_to_revise_count =
            block_exaquest_get_questions_for_me_to_revise_count($this->coursecategoryid, $this->userid);
        //$data->questions_for_me_to_release_count = block_exaquest_get_questions_for_me_to_release_count($this->coursecategoryid, $this->userid);
        // TODO what should that mean? questions for me to release? questions_finalised_count for now.
        $data->exams_for_me_to_create_count =
            block_exaquest_get_exams_for_me_to_create_count($this->coursecategoryid, $this->userid);

        $data->my_questions_count =
            block_exaquest_get_my_questionbankentries_count($this->coursecategoryid, $this->userid);
        $data->my_questions_to_submit_count =
            block_exaquest_get_my_questionbankentries_to_submit_count($this->coursecategoryid, $this->userid);
        $data->my_questions_to_review_count =
            block_exaquest_get_my_questionbankentries_to_be_reviewed_count($this->coursecategoryid, $this->userid);
        $data->my_questions_finalised_count =
            block_exaquest_get_my_finalised_questionbankentries_count($this->coursecategoryid, $this->userid);

        $catAndCont = get_question_category_and_context_of_course();

        $data->my_questions_to_submit_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_MY_CREATED_QUESTIONS_TO_SUBMIT));
        $data->my_questions_to_submit_link = $data->my_questions_to_submit_link->raw_out(false);

        $data->questions_for_me_to_review_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVIEW));
        $data->questions_for_me_to_review_link = $data->questions_for_me_to_review_link->raw_out(false);

        $data->questions_for_me_to_revise_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_QUESTIONS_FOR_ME_TO_REVISE));
        $data->questions_for_me_to_revise_link = $data->questions_for_me_to_revise_link->raw_out(false);

        $data->questions_for_me_to_release_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, 'category' => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_RELEASE));
        $data->questions_for_me_to_release_link = $data->questions_for_me_to_release_link->raw_out(false);


        $data->questions_overall_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS));
        $data->questions_overall_link = $data->questions_overall_link->raw_out(false);

        $data->questions_to_review_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVIEW));
        $data->questions_to_review_link = $data->questions_to_review_link->raw_out(false);

        $data->questions_fachlich_reviewed_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVIEW));
        $data->questions_fachlich_reviewed_link = $data->questions_fachlich_reviewed_link->raw_out(false);

        $data->questions_formal_reviewed_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVIEW));
        $data->questions_formal_reviewed_link = $data->questions_formal_reviewed_link->raw_out(false);

        $data->questions_finalised_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_RELEASE));
        $data->questions_finalised_link = $data->questions_finalised_link->raw_out(false);

        $data->questions_released_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_All_RELEASED_QUESTIONS));
        $data->questions_released_link = $data->questions_released_link->raw_out(false);

        $data->questions_to_revise_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVISE));
        $data->questions_to_revise_link = $data->questions_to_revise_link->raw_out(false);

        $data->questions_new_link = new moodle_url('/blocks/exaquest/questbank.php',
            array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
                "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_ALL_NEW_QUESTIONS));
        $data->questions_new_link = $data->questions_new_link->raw_out(false);

        //$data->questions_released_link = new moodle_url('/blocks/exaquest/questbank.php',
        //    array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
        //        "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVIEW));
        //$data->questions_released_link = $data->questions_released_link->raw_out(false);
        //
        //$data->questions_released_and_to_review_link = new moodle_url('/blocks/exaquest/questbank.php',
        //    array('courseid' => $this->courseid, "category" => $catAndCont[0] . ',' . $catAndCont[1],
        //        "filterstatus" => BLOCK_EXAQUEST_FILTERSTATUS_ALL_QUESTIONS_TO_REVIEW));
        //$data->questions_released_and_to_review_link = $data->questions_released_and_to_review_link->raw_out(false);

        // REQUEST NEW QUESTIONS
        // this adds the subtemplate. The data, in this case fragenersteller, does not have to be given to THIS data, because it is in the data for request_questions_popup already
        if ($this->capabilities["releasequestion"]) {
            $data->request_questions_popup = $this->request_questions_popup->export_for_template($output);
        }

        $data->show_exams_heading = false;
        if ($this->capabilities["releasequestion"]) {
            $data->request_exams_popup = $this->request_exams_popup->export_for_template($output);
            $data->show_exams_heading = true;
        }

        if ($this->capabilities["fachlicherpruefer"]) {;
            $data->show_exams_heading = true;
        }



        $data->questions_for_me_to_create_popup = $this->questions_for_me_to_create_popup->export_for_template($output);
        $data->exams_for_me_to_create_popup = $this->exams_for_me_to_create_popup->export_for_template($output);

        // similarity comparison button
        $data->buttons = [
            compare_questions::createShowOverviewButton(new moodle_url('/blocks/exaquest/similarity_comparison.php',
                array('courseid' => $this->courseid,
                    'substituteid' => 0, 'hidepreviousq' => 0, 'sort' => 0, 'category' => $catAndCont[0] . ',' . $catAndCont[1])),
                $this->courseid)
        ];

        $data->buttons = [
            compare_questions::createShowOverviewButton(new moodle_url('/blocks/exaquest/similarity_comparison.php',
                array('courseid' => $this->courseid,
                    'substituteid' => 0, 'hidepreviousq' => 0, 'sort' => 0, 'category' => $catAndCont[0] . ',' . $catAndCont[1])),
                $this->courseid)
        ];

        return $data;
    }
}