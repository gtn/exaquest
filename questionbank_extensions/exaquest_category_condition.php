<?php

//namespace core_question\local\bank;
namespace qbank_managecategories; // 4.3


use qbank_managecategories\helper;

class exaquest_category_condition extends category_condition {


    /**
     * Called by question_bank_view to display the GUI for selecting a category
     */
    public function display_options() {
        global $PAGE;
        $displaydata = [];
        $catmenu = helper::question_category_options($this->contexts, true, 0,
            true, -1, false);
        $displaydata['categoryselect'] = \html_writer::select($catmenu, 'category', $this->cat, [],
            array('class' => 'searchoptions custom-select', 'id' => 'id_selectacategory'));
        $displaydata['categorydesc'] = '';
        if ($this->category) {
            $displaydata['categorydesc'] = $this->print_category_info($this->category);
        }
        return '';
    }

    /**
     * Displays the recursion checkbox GUI.
     * question_bank_view places this within the section that is hidden by default
     */
    public function display_options_adv() {
        global $PAGE;
        $displaydata = [];
        if ($this->recurse) {
            $displaydata['checked'] = 'checked';
        }
        return '';
    }

}
