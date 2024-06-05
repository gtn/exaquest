<?php

namespace block_exaquest\output;

defined('MOODLE_INTERNAL') || die();

use core\chart_base;
use core\chart_series;

class scatter_chart extends chart_base {
    public function __construct() {
        parent::__construct();
        $this->type = 'scatter';
    }

    public function add_series(chart_series $series) {
        $this->series[] = $series;
    }
}