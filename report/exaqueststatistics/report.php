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

use mod_quiz\local\reports\report_base;

defined('MOODLE_INTERNAL') || die();

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
		$graderanges = self::get_grade_ranges($letters);

		$final_data = [];
		foreach($graderanges as $searched_range){
			$final_data[] = self::searchRange($combined_array, $searched_range);			
		}

		$tdata = array();
		$max = 100;
		$lindex = 0;		
		foreach($letters as $boundary=>$letter) {			
			$line = array();			
			$line[] = format_float($boundary,0).'-'. format_float($max,0);
			$line[] = format_string($letter);
			$line[] = $final_data[$lindex];
			$line[] = format_float($final_data[$lindex]*2,2).' %';
			$tdata[] = $line;
			$max = $boundary - 0.01;
			$lindex++;			
		}
		$dtotal = 0;
		$ptotal = 0;
		// calculate total of participants for last row display
		foreach($tdata as $tabledata){
			$dtotal = $dtotal + $tabledata[2];
			$ptotal = $ptotal + $tabledata[2]*2;
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

        return true;
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
    public static function get_grade_ranges($letters) {		
		$bandlabels = [];
		$max = 100;
		foreach($letters as $boundary=>$letter) {
			$bandlabels[] = format_float($boundary,2).' - '. format_float($max,2);
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
