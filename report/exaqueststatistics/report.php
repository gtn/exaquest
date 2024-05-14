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

use core_question\statistics\responses\analyser;
use mod_quiz\local\reports\report_base;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/mod/quiz/report/statistics/statisticslib.php');


/**
 * Quiz report for exaquest. Based on the grading report.
 * Extends report_base but the rest of it is created manually.
 */
class quiz_exaqueststatistics_report extends report_base {
    const DEFAULT_PAGE_SIZE = 5;
    const DEFAULT_ORDER = 'random';

    /** @var string Positive integer regular expression. */
    const REGEX_POSITIVE_INT = '/^[1-9]\d*$/';

    /** @var array URL parameters for what is being displayed when grading. */
    protected $viewoptions = [];

    /** @var int the current group, 0 if none, or NO_GROUPS_ALLOWED. */
    protected $currentgroup;

    /** @var array from quiz_report_get_significant_questions. */
    protected $questions;

    /** @var stdClass the course settings. */
    protected $course;

    /** @var stdClass the course_module settings. */
    protected $cm;

    /** @var stdClass the quiz settings. */
    protected $quiz;

    /** @var context the quiz context. */
    protected $context;

    /** @var quiz_exaqueststatistics_renderer Renderer of Quiz Exaqueststatistics. */
    protected $renderer;

    /** @var string fragment of SQL code to restrict to the relevant users. */
    protected $userssql;

    /** @var array extra user fields. */
    protected $extrauserfields = [];

    public function display($quiz, $cm, $course) {
		global $DB, $OUTPUT, $PAGE;
        // simply returning true / doing nothing in the display() function leads to an error
        // "Invalid state passed to moodle_page::set_state. We are in state 0 and state 3 was requested."
        //$this->print_header_and_tabs($cm, $course, $quiz, 'grading');

        $whichattempts = optional_param('whichattempts', $quiz->grademethod, PARAM_INT);
        $quizstats = new \quiz_statistics\calculated($whichattempts);
        $quizinfo = $quizstats->get_formatted_quiz_info_data($course, $cm, $quiz);

		/** Chandran Code start here **/
		// Added New info
		$quizinfo["timelimit"] = format_time($quiz->timelimit);
		$quizinfo["quizattempt"] = $quizinfo[get_string('allattemptscount', 'quiz_statistics')];

		// Remove unnecessary Info
		unset($quizinfo[get_string('quizclose', 'quiz')],$quizinfo[get_string('duration', 'quiz_statistics')], $quizinfo[get_string('firstattemptscount', 'quiz_statistics')], $quizinfo[get_string('allattemptscount', 'quiz_statistics')]);
		$quizinfo["quizcancelattempt"] = $DB->count_records_sql('SELECT count(*) FROM {quiz_attempts} WHERE quiz = ? AND state IN (?)',[$quiz->id, quiz_attempt::ABANDONED]);
		// we change key because get_string not working with umlaut
		$quizinfo = $this->change_key($quizinfo, get_string('quizopen', 'quiz'), 'quizdatum');
		// Replace label for german
		$quizinfo = $this->check_and_replace_plugin_label($quizinfo);
		/** Chandran Code end here **/

        echo $this->output_quiz_info_table($quizinfo);

		$context = context_module::instance($cm->id);
		$letters = grade_get_letters($context);
		$heading = get_string('statisticsheader', 'block_exaquest');

		echo $OUTPUT->heading($heading);

		list($bands, $bandwidth) = self::get_bands_count_and_width($quiz);
		$labels = self::get_bands_labels($bands, $bandwidth, $quiz);
		$data = quiz_report_grade_bands($bandwidth, $bands, $quiz->id, new \core\dml\sql_join());

		$combined_array = array_combine($labels, $data);

		$gradelabels = self::get_grade_labels($letters);
		$graderanges = self::get_grade_ranges($quiz, $letters);

		$final_data = [];
		foreach($graderanges as $searched_range){
			$final_data[] = self::searchRange($combined_array, $searched_range);
		}

		$tdata = array();
		$max = 100;
		$lindex = 0;
		foreach($letters as $boundary=>$letter) {
			$line = array();
			$line[] = round($quiz->grade*format_float($boundary,2)/100).' - '. round($quiz->grade*format_float($max,2)/100);
			$line[] = format_string($letter);
			$line[] = $final_data[$lindex];
			$tdata[] = $line;
			$max = $boundary - 0.01;
			$lindex++;
		}
		$dtotal = 0;
		// calculate total of participants for last row display
		foreach($tdata as $tabledata){
			$dtotal = $dtotal + $tabledata[2];
		}
		// calculate percentage of participants
		$rowindex = 0;
		$ptotal = 0;
		foreach($letters as $boundary=>$letter) {
				$tdata[$rowindex][3] = format_float(0,2).' %';
				if($dtotal > 0){
					$tdata[$rowindex][3] = format_float(($final_data[$rowindex]/$dtotal)*100,2).' %';
				}
				$ptotal = $ptotal + $tdata[$rowindex][3];
				$rowindex++;
		}
		$tdata[] = array('','',$dtotal,format_float($ptotal, 2).' %');

		$table = new html_table();
		$table->id = 'grade-letters-view';
		$table->head  = array(get_string('statisticspoints', 'block_exaquest'), get_string('letter', 'grades'),get_string('numericaldist', 'block_exaquest'),get_string('percentagedist', 'block_exaquest'));
		$table->size  = array('25%', '25%','25%','25%');
		$table->align = array('left', 'left', 'left', 'left');
		$table->width = '30%';
		$table->data  = $tdata;
		$table->tablealign  = 'center';
		echo html_writer::table($table);

		$output = $PAGE->get_renderer('mod_quiz');
		$chart = self::get_chart($gradelabels, $final_data);
		$graphname = get_string('overviewreportgraph', 'quiz_overview');
		// Numerical range data should display in LTR even for RTL languages.
		echo $output->chart($chart, $graphname, ['dir' => 'ltr']);

		$heading = get_string('questionstatisticsheader', 'block_exaquest');

		echo $OUTPUT->heading($heading);

		$questionDetails = self::block_exaquest_get_category_question($quiz->id);
		$categoryoptionidcount = block_exaquest_get_category_question_count($quiz->id);
		$categoryoptionidkeys = array_keys($categoryoptionidcount);
		$categoryoptions = block_exaquest_get_category_names_by_ids($categoryoptionidkeys,true);
		$options = [];
		foreach ($categoryoptions as $categoryoption) {
			$options[$categoryoption->categorytype][$categoryoption->id] = $categoryoption->categoryname;
		}
		$categorys_required_counts = block_exaquest_get_fragefaecher_by_courseid_and_quizid($course->id, $quiz->id);
		// if there are no questions of a category, this category will now show up in the options. ==> add the required fragefaecher so they always show up
		foreach ($categorys_required_counts as $cat_required) {
			// first check if it is even an array, or if it is completely empty still
			// then check, if for THIS specific cat there is already an entry in the array or not
			if (!is_array($options[BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH]) ||
					!array_key_exists($cat_required->id, $options[BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH])) {

				$options[BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH][$cat_required->id] = $cat_required->categoryname;
			}
		}
		$quiz = $DB->get_record('quiz', ['id' => $quiz->id], '*', MUST_EXIST);
		$questions = self::load_and_initialise_questions_for_calculations($quiz);

        $questionstats = self::block_exaquest_get_all_stats_and_analysis($quiz,
            $quiz->grademethod, question_attempt::ALL_TRIES, new \core\dml\sql_join(),
            $questions, null);
		/**echo '<pre>';
		print_r($questionstats->questionstats);
		echo '</pre>';
		echo '<pre>';
		print_r($questionDetails);
		echo '</pre>';**/

		$tcontent = array();
		foreach ($options as $key => $option) {
			foreach ($option as $categoryid => $name) {
				if ($key == BLOCK_EXAQUEST_CATEGORYTYPE_FRAGEFACH) {
					[$points,$questionids] = self::block_exaquest_calculate_question_points($questionDetails,$categoryid);
					[$facilityValue,$discriminationValue] = self::block_exaquest_calculate_avg_index($questionstats->questionstats, $questionids[$categoryid]);
					$line = array();
					$line[] = $name;
					$line[] = $categoryoptionidcount[$categoryid] ?: 0;
					$line[] = $facilityValue;
					$line[] = $discriminationValue;
					$line[] = $points;
					$tcontent[] = $line;
				}
			}
		}

		$table = new html_table();
		$table->id = 'question-type-view';
		$table->head  = array(get_string('questiontype', 'block_exaquest'), get_string('numberofquestion', 'block_exaquest'),get_string('difficultyavg', 'block_exaquest'),get_string('selectnessavg', 'block_exaquest') , get_string('statisticspoints', 'block_exaquest'));
		$table->size  = array('30%', '15%','20%','20%','15%');
		$table->align = array('left', 'left', 'left', 'left');
		$table->width = '30%';
		$table->data  = $tcontent;
		$table->tablealign  = 'center';
		echo html_writer::table($table);

        return true;
    }

    // inspired by public function get_all_stats_and_analysis from mod/quiz/report/statistics/report.php.
    // difference: no analyse_responses_for_all_questions_and_subquestions, and only return questionstats
    protected function block_exaquest_get_all_stats_and_analysis(
            $quiz, $whichattempts, $whichtries, \core\dml\sql_join $groupstudentsjoins,
            $questions, $progress = null, bool $calculateifrequired = true, bool $performanalysis = true) {

        if ($progress === null) {
            $progress = new \core\progress\none();
        }

        $qubaids = quiz_statistics_qubaids_condition($quiz->id, $groupstudentsjoins, $whichattempts);

        $qcalc = new \core_question\statistics\questions\calculator($questions, $progress);

        $quizcalc = new \quiz_statistics\calculator($progress);

        $progress->start_progress('', 4);

        // Get a lock on this set of qubaids before performing calculations. This prevents the same calculation running
        // concurrently and causing database deadlocks. We use a long timeout here as a big quiz with lots of attempts may
        // take a long time to process.
        $lockfactory = \core\lock\lock_config::get_lock_factory('quiz_statistics_get_stats');
        $lock = $lockfactory->get_lock($qubaids->get_hash_code(), 0);
        if (!$lock) {
            if (!$calculateifrequired) {
                // We're not going to do the calculation in this request anyway, so just give up here.
                $progress->progress(4);
                $progress->end_progress();
                return [null, null];
            }
            $locktimeout = get_config('quiz_statistics', 'getstatslocktimeout');
            $lock = \core\lock\lock_utils::wait_for_lock_with_progress(
                    $lockfactory,
                    $qubaids->get_hash_code(),
                    $progress,
                    $locktimeout,
                    get_string('getstatslockprogress', 'quiz_statistics'),
            );
            if (!$lock) {
                // Lock attempt timed out.
                $progress->progress(4);
                $progress->end_progress();
                debugging('Could not get lock on ' .
                        $qubaids->get_hash_code() . ' (Quiz ID ' . $quiz->id . ') after ' .
                        $locktimeout . ' seconds');
                return [null, null];
            }
        }

        try {
            if ($quizcalc->get_last_calculated_time($qubaids) === false) {
                if (!$calculateifrequired) {
                    $progress->progress(4);
                    $progress->end_progress();
                    $lock->release();
                    return [null, null];
                }

                // Recalculate now.
                $questionstats = $qcalc->calculate($qubaids);
                $progress->progress(2);

                $quizstats = $quizcalc->calculate(
                        $quiz->id,
                        $whichattempts,
                        $groupstudentsjoins,
                        count($questions),
                        $qcalc->get_sum_of_mark_variance()
                );
                $progress->progress(3);
            } else {
                $quizstats = $quizcalc->get_cached($qubaids);
                $progress->progress(2);
                $questionstats = $qcalc->get_cached($qubaids);
                $progress->progress(3);
            }

            if ($quizstats->s() && $performanalysis) {
                $subquestions = $questionstats->get_sub_questions();
                $this->analyse_responses_for_all_questions_and_subquestions(
                        $questions,
                        $subquestions,
                        $qubaids,
                        $whichtries,
                        $progress
                );
            }
            $progress->progress(4);
            $progress->end_progress();
        } finally {
            $lock->release();
        }

        return $questionstats;
    }

    /**
     * From mod/quiz/report/statistics/report.php.
     *
     * Analyse responses for all questions and sub questions in this quiz.
     *
     * @param stdClass[] $questions as returned by self::load_and_initialise_questions_for_calculations
     * @param stdClass[] $subquestions full question objects.
     * @param qubaid_condition $qubaids the question usages whose responses to analyse.
     * @param string $whichtries which tries to analyse \question_attempt::FIRST_TRY, LAST_TRY or ALL_TRIES.
     * @param null|\core\progress\base $progress Used to indicate progress of task.
     */
    protected function analyse_responses_for_all_questions_and_subquestions($questions, $subquestions, $qubaids,
        $whichtries, $progress = null) {
        if ($progress === null) {
            $progress = new \core\progress\none();
        }

        // Starting response analysis tasks.
        $progress->start_progress('', count($questions) + count($subquestions));

        $done = $this->analyse_responses_for_questions($questions, $qubaids, $whichtries, $progress);

        $this->analyse_responses_for_questions($subquestions, $qubaids, $whichtries, $progress, $done);

        // Finished all response analysis tasks.
        $progress->end_progress();
    }

    /**
     * From mod/quiz/report/statistics/report.php.
     *
     * Analyse responses for an array of questions or sub questions.
     *
     * @param stdClass[] $questions  as returned by self::load_and_initialise_questions_for_calculations.
     * @param qubaid_condition $qubaids the question usages whose responses to analyse.
     * @param string $whichtries which tries to analyse \question_attempt::FIRST_TRY, LAST_TRY or ALL_TRIES.
     * @param null|\core\progress\base $progress Used to indicate progress of task.
     * @param int[] $done array keys are ids of questions that have been analysed before calling method.
     * @return array array keys are ids of questions that were analysed after this method call.
     */
    protected function analyse_responses_for_questions($questions, $qubaids, $whichtries, $progress = null, $done = []) {
        $countquestions = count($questions);
        if (!$countquestions) {
            return [];
        }
        if ($progress === null) {
            $progress = new \core\progress\none();
        }
        $progress->start_progress('', $countquestions, $countquestions);
        foreach ($questions as $question) {
            $progress->increment_progress();
            if (question_bank::get_qtype($question->qtype, false)->can_analyse_responses()  && !isset($done[$question->id])) {
                $responesstats = new analyser($question, $whichtries);
                $responesstats->calculate($qubaids, $whichtries);
            }
            $done[$question->id] = 1;
        }
        $progress->end_progress();
        return $done;
    }

	protected static function block_exaquest_grade_method_sql($grademethod, $quizattemptsalias = 'quiza') {
		switch ($grademethod) {
			case QUIZ_GRADEHIGHEST :
				return "($quizattemptsalias.state = 'finished' AND NOT EXISTS (
							   SELECT 1 FROM {quiz_attempts} qa2
								WHERE qa2.quiz = $quizattemptsalias.quiz AND
									qa2.userid = $quizattemptsalias.userid AND
									 qa2.state = 'finished' AND (
					COALESCE(qa2.sumgrades, 0) > COALESCE($quizattemptsalias.sumgrades, 0) OR
				   (COALESCE(qa2.sumgrades, 0) = COALESCE($quizattemptsalias.sumgrades, 0) AND qa2.attempt < $quizattemptsalias.attempt)
									)))";

			case QUIZ_GRADEAVERAGE :
				return '';

			case QUIZ_ATTEMPTFIRST :
				return "($quizattemptsalias.state = 'finished' AND NOT EXISTS (
							   SELECT 1 FROM {quiz_attempts} qa2
								WHERE qa2.quiz = $quizattemptsalias.quiz AND
									qa2.userid = $quizattemptsalias.userid AND
									 qa2.state = 'finished' AND
								   qa2.attempt < $quizattemptsalias.attempt))";

			case QUIZ_ATTEMPTLAST :
				return "($quizattemptsalias.state = 'finished' AND NOT EXISTS (
							   SELECT 1 FROM {quiz_attempts} qa2
								WHERE qa2.quiz = $quizattemptsalias.quiz AND
									qa2.userid = $quizattemptsalias.userid AND
									 qa2.state = 'finished' AND
								   qa2.attempt > $quizattemptsalias.attempt))";
		}
	}

	protected static function block_exaquest_attempts_sql($quizid, \core\dml\sql_join $groupstudentsjoins,
        $whichattempts = QUIZ_GRADEAVERAGE, $includeungraded = false) {
		$fromqa = "{quiz_attempts} quiza ";
		$whereqa = 'quiza.quiz = :quizid AND quiza.preview = 0 AND quiza.state = :quizstatefinished';
		$qaparams = ['quizid' => (int)$quizid, 'quizstatefinished' => quiz_attempt::FINISHED];

		if (!empty($groupstudentsjoins->joins)) {
			$fromqa .= "\nJOIN {user} u ON u.id = quiza.userid
				{$groupstudentsjoins->joins} ";
			$whereqa .= " AND {$groupstudentsjoins->wheres}";
			$qaparams += $groupstudentsjoins->params;
		}

		$whichattemptsql = self::block_exaquest_grade_method_sql($whichattempts);
		if ($whichattemptsql) {
			$whereqa .= ' AND ' . $whichattemptsql;
		}

		if (!$includeungraded) {
			$whereqa .= ' AND quiza.sumgrades IS NOT NULL';
		}

		return [$fromqa, $whereqa, $qaparams];
	}

	protected static function block_exaquest_qubaids_condition($quizid, \core\dml\sql_join $groupstudentsjoins,
			$whichattempts = QUIZ_GRADEAVERAGE, $includeungraded = false) {
		list($fromqa, $whereqa, $qaparams) = self::block_exaquest_attempts_sql(
				$quizid, $groupstudentsjoins, $whichattempts, $includeungraded);
		return new qubaid_join($fromqa, 'quiza.uniqueid', $whereqa, $qaparams);
	}

	protected static function block_exaquest_calculate_avg_index($questionstats, $indexarray){
		$avgvalue = 0;
		$avgindex = 0;
		$results = self::filterObjectsByIds($questionstats, $indexarray);
		foreach ($results as $result) {
			$avgvalue = $avgvalue + ($result->facility*100);
			$avgindex = $avgindex + ($result->discriminationindex);
		}
		$facilityValue = format_float(($avgvalue/count($results)),2).' %';
		$discriminationValue = format_float(($avgindex/count($results)),2).' %';

		return [$facilityValue,$discriminationValue];
	}

	// Define the function to filter the array of objects based on the provided IDs.
	protected static function filterObjectsByIds($objects, $ids) {
		// Create an empty array to store the filtered results.
		$filteredResults = [];

		// Iterate over the array of objects.
		foreach ($objects as $key => $object) {
			// Check if the object has a 'questionid' property.
			if (isset($object->questionid)) {
				// Check if the 'questionid' is in the list of provided IDs.
				if (in_array($object->questionid, $ids)) {
					// Add the object to the filtered results.
					$filteredResults[$key] = $object;
				}
			}
		}

		// Return the filtered results.
		return $filteredResults;
	}
	protected static function block_exaquest_calculate_question_points($questiondetails, $categoryid){
		$points = 0;
		foreach ($questiondetails as $questiondetail) {
			if($questiondetail->value == $categoryid){
				$questionids[$categoryid][] = $questiondetail->questionid;
				$points = $points + $questiondetail->maxmark;
			}
		}

		return [$points, $questionids];
	}

	protected static  function block_exaquest_get_category_question($quizid) {
		global $DB;
		// sql retrieves all categories for each questions inside this view
		$customfieldvalues = $DB->get_records_sql("SELECT *
		  FROM {quiz_slots} qusl
		  JOIN {question_references} qref ON qusl.id = qref.itemid
		  JOIN {question_versions} qv ON qv.questionbankentryid = qref.questionbankentryid
		  JOIN {customfield_data} cfd ON cfd.instanceid = qv.questionid
		  WHERE qv.version = (SELECT Max(v.version)
				FROM   {question_versions} v
				JOIN {question_bank_entries} be
				ON be.id = v.questionbankentryid
				WHERE  be.id = qref.questionbankentryid) AND qusl.quizid=:quizid",
		  array('quizid' => $quizid));

		return $customfieldvalues;
	}


	protected static function load_and_initialise_questions_for_calculations($quiz) {
        // Load the questions.
        $questions = quiz_report_get_significant_questions($quiz);

        $questiondata = [];
        foreach ($questions as $qs => $question) {
            if ($question->qtype === 'random') {
                $question->id = 0;
                $question->name = get_string('random', 'quiz');
                $question->questiontext = get_string('random', 'quiz');
                $question->parenttype = 'random';
                $questiondata[$question->slot] = $question;
            } else if ($question->qtype === 'missingtype') {
                $question->id = is_numeric($question->id) ? (int) $question->id : 0;
                $questiondata[$question->slot] = $question;
                $question->name = get_string('deletedquestion', 'qtype_missingtype');
                $question->questiontext = get_string('deletedquestiontext', 'qtype_missingtype');
            } else {
                $q = question_bank::load_question_data($question->id);
                $q->maxmark = $question->maxmark;
                $q->slot = $question->slot;
                $q->number = $question->number;
                $q->parenttype = null;
                $questiondata[$question->slot] = $q;
            }
        }

        return $questiondata;
    }

	protected static function quiz_report_get_significant_questions($quiz) {
		$quizobj = mod_quiz\quiz_settings::create($quiz->id);
		$structure = \mod_quiz\structure::create_for_quiz($quizobj);
		$slots = $structure->get_slots();

		$qsbyslot = [];
		$number = 1;
		foreach ($slots as $slot) {
			// Ignore 'questions' of zero length.
			if ($slot->length == 0) {
				continue;
			}

			$slotreport = new \stdClass();
			$slotreport->slot = $slot->slot;
			$slotreport->id = $slot->questionid;
			$slotreport->qtype = $slot->qtype;
			$slotreport->length = $slot->length;
			$slotreport->number = $number;
			$number += $slot->length;
			$slotreport->maxmark = $slot->maxmark;
			$slotreport->category = $slot->category;

			$qsbyslot[$slotreport->slot] = $slotreport;
		}

		return $qsbyslot;
	}

	protected static function searchRange($combined_array, $searched_range) {
		// Initialize result array
		$sum = 0;

		// Parse the lower and upper bounds of the searched range
		list($search_lower, $search_upper) = array_map('floatval', explode(' - ', $searched_range));

		// Iterate over the combined array
		foreach ($combined_array as $range => $value) {
			// Parse the lower and upper bounds of each range in the combined array
			list($range_lower, $range_upper) = array_map('floatval', explode(' - ', $range));

			// Check if the searched range falls within the current range
			if ($search_lower < $range_upper && $search_upper > $range_lower) {
				// If it does, add the range and its corresponding value to the result
				$sum += $value;
			}
		}

		// Return the result array
		return $sum;
	}
    /**
     * Get a chart.
     *
     * @param string[] $labels Chart labels.
     * @param int[] $data The data.
     * @return \core\chart_base
     */
    protected static function get_chart($labels, $data) {
        $chart = new \core\chart_bar();
        $chart->set_labels($labels);
        $chart->get_xaxis(0, true)->set_label(get_string('gradenoun'));

        $yaxis = $chart->get_yaxis(0, true);
        $yaxis->set_label(get_string('participants'));
        $yaxis->set_stepsize(max(1, round(max($data) / 10)));

        $series = new \core\chart_series(get_string('participants'), $data);
        $chart->add_series($series);
        return $chart;
    }


    /**
     * Get the grade ranges.
     *
     * @param string[] $letters The letter of grades.
     * @return string[] The labels.
     */
    public static function get_grade_ranges($quiz, $letters) {
		$bandlabels = [];
		$max = 100;
		foreach($letters as $boundary=>$letter) {
			$bandlabels[] = round($quiz->grade*format_float($boundary,2)/100).' - '. round($quiz->grade*format_float($max,2)/100);
			$max = $boundary - 0.01;
		}

        return $bandlabels;
    }


    /**
     * Get the grade labels.
     *
     * @param string[] $letters The letter of grades.
     * @return string[] The labels.
     */
    public static function get_grade_labels($letters) {
		$bandlabels = [];
		foreach($letters as $boundary=>$letter) {
			$bandlabels[] = format_string($letter);
		}

        return $bandlabels;
    }

    /**
     * Get the bands labels.
     *
     * @param int $bands The number of bands.
     * @param int $bandwidth The band width.
     * @param stdClass $quiz The quiz object.
     * @return string[] The labels.
     */
    public static function get_bands_labels($bands, $bandwidth, $quiz) {
        $bandlabels = [];
        for ($i = 1; $i <= $bands; $i++) {
            $bandlabels[] = quiz_format_grade($quiz, ($i - 1) * $bandwidth) . ' - ' . quiz_format_grade($quiz, $i * $bandwidth);
        }
        return $bandlabels;
    }

    /**
     * Get the bands configuration for the quiz.
     *
     * This returns the configuration for having between 11 and 20 bars in
     * a chart based on the maximum grade to be given on a quiz. The width of
     * a band is the number of grade points it encapsulates.
     *
     * @param stdClass $quiz The quiz object.
     * @return array Contains the number of bands, and their width.
     */
    public static function get_bands_count_and_width($quiz) {
        $bands = $quiz->grade;

        while ($bands > 20 || $bands <= 10) {
            if ($bands > 50) {
                $bands /= 5;
            } else if ($bands > 20) {
                $bands /= 2;
            }
            if ($bands < 4) {
                $bands *= 5;
            } else if ($bands <= 10) {
                $bands *= 2;
            }
        }

        // See MDL-34589. Using doubles as array keys causes problems in PHP 5.4, hence the explicit cast to int.
        $bands = (int) ceil($bands);

        return [$bands, $quiz->grade / $bands];
    }

    /**
     * @param mixed $array
     * @param mixed $old_key
     * @param mixed $new_key
     * @return mixed
     */
    protected function change_key( $array, $old_key, $new_key ) {
		if( ! array_key_exists( $old_key, $array ) )
			return $array;

		$keys = array_keys( $array );
		$keys[ array_search( $old_key, $keys ) ] = $new_key;

		return array_combine( $keys, $array );
	}

    /**
     * @param mixed $quizinfo
     * @return mixed
     */
	protected function check_and_replace_plugin_label($quizinfo){
		foreach ($quizinfo as $heading => $value) {
			if (get_string_manager()->string_exists($heading, 'block_exaquest')) {
				$quizinfo = $this->change_key($quizinfo, $heading, get_string($heading, 'block_exaquest'));
			}
		}

		return $quizinfo;
	}

    /**
     * Return HTML for table of overall quiz statistics.
     *
     * @param array $quizinfo as returned by {@link get_formatted_quiz_info_data()}.
     * @return string the HTML.
     */
    protected function output_quiz_info_table($quizinfo) {

        $quizinfotable = new html_table();
        $quizinfotable->align = ['center', 'center'];
        $quizinfotable->width = '60%';
        $quizinfotable->attributes['class'] = 'generaltable titlesleft';
        $quizinfotable->data = [];

        foreach ($quizinfo as $heading => $value) {
            $quizinfotable->data[] = [$heading, $value];
        }

        return html_writer::table($quizinfotable);
    }

    ///**
    // * Initialise some parts of $PAGE and start output.
    // *
    // * @param stdClass $cm the course_module information.
    // * @param stdClass $course the course settings.
    // * @param stdClass $quiz the quiz settings.
    // * @param string $reportmode the report name.
    // */
    //public function print_header_and_tabs($cm, $course, $quiz, $reportmode = 'overview') {
    //    global $PAGE;
    //    $this->renderer = $PAGE->get_renderer('quiz_exaqueststatistics');
    //    parent::print_header_and_tabs($cm, $course, $quiz, $reportmode);
    //}

}
