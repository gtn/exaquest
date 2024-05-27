<?php
$string['pluginname'] = 'Question Management Tool';
$string['exaquest'] = 'Question Management Tool';
$string['exaquest:addinstance'] = 'Add a new exaquest block';
$string['exaquest:myaddinstance'] = 'Add a new exaquest block to the My Moodle page';
$string['exaquest:view'] = 'View the exaquest block';

// Block
$string['dashboard'] = 'Dashboard';
$string['dashboard_of_course'] = 'Dashboard of course "{$a}"';
$string['questionbank_of_course'] = 'Questionbank of course "{$a}"';
$string['similarity_of_course'] = 'Similarity comparison of course "{$a}"';
$string['exams_of_course'] = 'Exams of course "{$a}"';
$string['category_settings_of_course'] = 'Category settings of course "{$a}"';
$string['get_questionbank'] = 'Questionbank';
$string['similarity'] = 'Similarity overview';
$string['exams'] = 'Exams';
$string['category_settings'] = 'Category settings';
$string['save_and_return'] = 'Save and return to Exaquest';
$string['todos_are_open'] = ' TODOs are open.';

$string['request_questions'] = 'Request new questions';
$string['request_questions_label'] = 'Request new questions from ...';
$string['request_questions_comment_placeholder'] = 'Which kind of question is needed? Comment mandatory';
$string['comment_placeholder_mandatory'] = 'Comment mandatory';
$string['comment_placeholder'] = 'Comment';
$string['request_questions_button'] = 'Request';
$string['revise_questions_label'] = 'The following questions are marked for revision: ';
$string['formal_review_questions_label'] = 'The following questions are marked for formal finalisation: ';
$string['fachlich_review_questions_label'] = 'The following questions are marked for specialist finalisation: ';
$string['write_a_comment'] = 'Write a comment:';

$string['request_exams'] = 'Request new exams';
$string['request_exams_label'] = 'Request new exams from ...';
$string['request_exams_comment_placeholder'] = 'Which kind of exam is needed? Optional';
$string['request_exams_button'] = 'Request';

$string['set_quizquestioncount'] = 'Request';
$string['exaquest_minimum_required_percentage_per_fragefach'] = 'Minimum % for Fragefach';

$string['mark_as_done'] = 'Mark as done';
$string['mark_selected_as_done_button'] = 'Mark selected requests as done';

$string['assign_addquestions'] = 'Fragenzuweisung anfordern';
$string['assign_addquestions_label'] = 'Fragenzuweisung anfordern von ...';
$string['assign_addquestions_comment_placeholder'] = 'Kommentar an die ausgewählten Personen';
$string['assign_add_questions_button'] = 'Request';

// Messages
$string['messageprovider:newquestionsrequest'] = 'New questions have been requested';
$string['messageprovider:fillexam'] = 'An exam has to be filled';
$string['please_create_new_questions'] =
    'Please create new questions in <a href="{$a->url}">{$a->fullname}</a>. Comment: {$a->requestcomment}';
$string['please_create_new_questions_subject'] = 'Please create new questions in {$a->fullname}';

$string['please_fill_exam'] =
    'Please fill exam <a href="{$a->url}">{$a->fullname}</a> mit Fragen. Kommentar: {$a->requestcomment}';
$string['please_fill_exam_subject'] = 'Please fill exam {$a->fullname} mit Fragen';

$string['messageprovider:gradeexam'] = 'An exam has to be graded';
$string['please_grade_exam'] = 'Please grade the exam <a href="{$a->url}">{$a->fullname}</a>. Comment: {$a->requestcomment}';
$string['please_grade_exam_subject'] = 'Please grade exam {$a->fullname}';
$string['selected_questions'] = 'The following questions have been assigned to you';

$string['messageprovider:checkexamgrading'] = 'Exam grading has to be reviewed';
$string['please_check_exam_grading'] = 'Please check the grading of the exam <a href="{$a->url}">{$a->fullname}</a>. Comment: {$a->requestcomment}';
$string['please_check_exam_grading_subject'] = 'Please review the grading of {$a->fullname}';

$string['messageprovider:newexamsrequest'] = 'New exams have been requested';
$string['please_create_new_exams'] = 'Please create new exams in <a href="{$a->url}">{$a->fullname}</a>. Comment: {$a->requestcomment}';
$string['please_create_new_exams_subject'] = 'Please create new exams in {$a->fullname}';

$string['messageprovider:revisequestion'] = 'Questions have been assigned for revision';
$string['please_revise_question'] = 'Please revise question <a href="{$a->url}">{$a->fullname}</a>. Comment: {$a->requestcomment}';
$string['please_revise_question_subject'] = 'Please revise question {$a->fullname}';

$string['messageprovider:releasequestion'] = 'Questions are finlaized and can be released';
$string['please_release_question'] = 'Please release question <a href="{$a->url}">{$a->fullname}</a>.';
$string['please_release_question_subject'] = 'Please release question {$a->fullname}';

$string['messageprovider:reviewquestion'] = 'Questions have been assigned for review';
$string['please_review_question'] = 'Please review question <a href="{$a->url}">{$a->fullname}</a>. Comment: {$a->requestcomment}';
$string['please_review_question_subject'] = 'Please review question {$a->fullname}';

$string['messageprovider:dailytodos'] = 'Daily todo message';
$string['dailytodos'] = 'You have the following TODOs: <br> {$a->todosmessage}';
$string['dailytodos_subject'] = 'Exaquest TODOs';
$string['todos_in_course'] = '{$a->todoscount} TODOs in course <a href="{$a->url}">{$a->fullname}</a><br>';

$string['messageprovider:daily_released_questions'] = 'Daily released questions message';
$string['daily_released_questions'] =
    'Questions have been released the following courses: <br> {$a->daily_released_questions_message}';
$string['daily_released_questions_subject'] = 'Exaquest questions released';
$string['daily_released_questions_in_course'] =
    '{$a->daily_released_questions} questions released in course <a href="{$a->url}">{$a->fullname}</a><br>';


$string['messageprovider:quizfinishedgradingopen'] = 'Prüfung abgeschlossen und Beurteilung abgeschlossen TODO TRANSLATE';
$string['quiz_finished_grading_open'] = 'Die Prüfung <a href="{$a->url}">{$a->fullname}</a> wurde abgeschlossen und die Beurteilungen wurden vergeben, müssen aber noch begutachtet werden. TODO TRANSLATE';
$string['quiz_finished_grading_open_subject'] = 'Prüfung {$a->fullname} abgeschlossen aber nicht beurteilt. TODO TRANSLATE';
$string['quiz_finished_grading_open_comment'] = 'Diese Prüfung ist abgeschlossen, es sind aber noch Fragen zu beurteilen. TODO TRANSLATE';


$string['messageprovider:quizfinishedgradingdone'] = 'Prüfung abgeschlossen mit unvollständiger Beurteilung TODO TRANSLATE';
$string['quiz_finished_grading_done'] = 'Die Prüfung <a href="{$a->url}">{$a->fullname}</a> wrude abgeschlossen und es sind noch Beurteilungen offen. TODO TRANSLATE';
$string['quiz_finished_grading_done_subject'] = 'Prüfung {$a->fullname} abgeschlossen und beurteilt. TODO TRANSLATE';
$string['quiz_finished_grading_done_comment'] = 'Diese Prüfung ist abgeschlossen, die Fragen sind beurteilt, die Beurteilungskontrolle steht noch aus. TODO TRANSLATE';

// Roles and Capabilities

$string['setuproles'] = 'Set up roles and capabilities';

// Exams

$string['create_new_exam'] = 'Create new exam';
$string['add_questions_to_quiz'] = 'Add Questions to quiz';
$string['add_to_quiz'] = 'Add to quiz';
$string['questionbank_selected_quiz'] = 'Questionbank with Exam selected: ';
$string['exams_overview'] = 'Exams overview';
$string['new_exams'] = 'New exams';
$string['created_exams'] = 'Created exams';
$string['fachlich_released_exams'] = 'Fachlich released exams';
$string['formal_released_exams'] = 'Formal released exams';
$string['active_exams'] = 'Active exams';
$string['finished_exams'] = 'Finished exams';
$string['exams_grades_released'] = 'Exams where grades have been released';
$string['add_questions_to_these_exams'] = 'Add questions to these exams';
$string['usage_check_column'] = 'Usage checker';
$string['check_added_questions'] = 'Check added questions';
$string['remove_from_quiz'] = 'Remove question from exam';
$string['go_to_exam_report_overview'] = 'go to reports';
// Dasboardcard
$string['questions_overview_title'] = 'QUESTIONS';
$string['exams_overview_title'] = 'EXAMS';
$string['my_questions_title'] = 'MY QUESTIONS';
$string['examinations_title'] = 'EXAMINATIONS';
$string['todos_title'] = 'TODOs';
$string['statistics_title'] = 'STATISTICS';
$string['overview'] = 'Overview';

$string['questions_overall_count'] = 'questions overall';
$string['questions_new_count'] = 'questions that have been created but not yet sent to review';
$string['questions_reviewed_count'] = 'questions are finalised / reviewed';
$string['questions_to_review_count'] = 'questions have to be reviewed';
$string['questions_to_revise_count'] = 'questions have to be revised';
$string['questions_fachlich_reviewed_count'] = 'Fachlich reviewed questions';
$string['questions_formal_reviewed_count'] = 'Formal reviewed questions';
$string['questions_finalised_count'] = 'questions finalised';
$string['questions_released_count'] = 'questions released';
$string['questions_locked_count'] = 'questions locked';
$string['questions_released_and_to_review_count'] = 'questions are released and should be reviewed again';

$string['my_questions_count'] = 'questions from me';
$string['my_questions_finalised_count'] = 'of my questions are finalised / reviewed';
$string['my_questions_to_review_count'] = 'of my questions have to be reviewed';

$string['list_of_exams_with_status'] = 'List of exams with status:';
$string['create_new_exam_button'] = 'create new exam';

$string['questions_for_me_to_review'] = 'Questions for me to review';
$string['questions_for_me_to_submit'] = 'Questions for me to submit';
$string['questions_for_me_to_create'] = 'Questions for me to create';
$string['questions_for_me_to_create_title'] = 'Questions for me to create';
$string['questions_for_me_to_revise'] = 'Questions for me to revise';
$string['questions_for_me_to_release'] = 'Questions for me to release';
$string['compare_questions'] = 'Compare questions';
$string['exams_for_me_to_create'] = 'Exams for me to create';
$string['exams_for_me_to_fachlich_release'] = 'Exams for me to fachlich release';
$string['exams_for_me_to_fill'] = 'Exams for me to fill with questions';
$string['exams_for_me_to_fill_title'] = 'Exams for me to fill with questions';
$string['exams_finished_grading_open'] = 'Exams where grading has to be done';
$string['exams_finished_grading_done'] = 'Exams where grading has to be checked';

//Questionbank

$string['show_all_questions'] = 'Show all questions';
$string['show_all_imported_questions'] = 'Show imported questions';
$string['show_all_new_questions'] = 'Show all new questions';
$string['show_my_created_questions'] = 'Show my created questions';
$string['show_my_created_questions_to_submit'] = 'Show my created questions that I still have to submit';
$string['show_all_questions_to_review'] = 'Show all questions to review';
$string['show_all_fachlich_reviewed_questions_to_review'] =
    'Show all questions that are fachlich reviewed and need to be formally reviewed';
$string['show_all_formal_reviewed_questions_to_review'] =
    'Show all questions that are formal reviewed and need to be fachlich reviewed';
$string['show_questions_for_me_to_review'] = 'Show questions for me to review';
$string['show_questions_to_revise'] = 'Show questions to revise';
$string['show_questions_for_me_to_revise'] = 'Show questions for me to revise';
$string['show_questions_to_release'] = 'Show questions to release';
$string['show_questions_for_me_to_release'] = 'Show questions for me to release';
$string['show_all_released_questions'] = 'Show all released questions';
$string['show_all_locked_questions'] = 'Show all locked questions';

$string['created'] = 'Created:';
$string['review'] = 'Review:';
$string['revise'] = 'Revise:';
$string['release'] = 'Release:';
$string['locked_filter'] = 'Locked:';
$string['imported_questions'] = 'Imported';

$string['open_question_for_review'] = 'Open question for review';
$string['formal_review_done'] = 'Finish formal review';
$string['fachlich_review_done'] = 'Finish fachlich review';
$string['revise_question'] = 'Assign question to be revised';
$string['release_question'] = 'Release question';
$string['skip_and_release_question'] = 'Skip and release question';
$string['release_question_warning'] = 'Are you sure you want to skip the review and release the question right away?';
$string['release_question_warning_title'] = 'Warning';
$string['change_status'] = 'change status';
$string['notification_will_be_sent_to_pk'] =
    'The Prüfungskoordination will also get a notification that the formal review should be done.';

$string['new_question'] = 'Newly created';
$string['to_revise'] = 'To revise';
$string['to_assess'] = 'To assess';
$string['formal_done'] = 'Formally finalized';
$string['fachlich_done'] = 'Fachlich finalized';
$string['finalised'] = 'Finalized';
$string['released'] = 'Released';
$string['imported_question'] = 'Imported';

$string['question'] = 'Question:';

$string['question_id'] = 'Question ID';
$string['ownername'] = 'Created by';
$string['lastchanged'] = 'Last changed';

$string['open_for_review_text'] = 'Assign review to..';
$string['revise_text'] = 'Assign revision to..';
$string['open_for_review_title'] = 'Review';
$string['revise_title'] = 'Revision';
$string['change_owner_title'] = 'Change Creator';
$string['change_owner_text'] = 'Change Creator to..';
$string['change_owner'] = 'Change Creator';
$string['unlock_question'] = 'Unlock';
$string['lock_question'] = 'Lock Question';
$string['locked'] = 'Locked';
$string['missing_category_tooltip'] = 'Not all categories are assigned to the quesiton';

//Category Settings
$string['settings_title'] = 'Category Settings';
$string['settings_description'] = 'Please enter one category per row';

$string['category_options'] = 'Category options';

$string['edit'] = "Edit";
$string['submit'] = "Submit";
$string['add_category'] = "Add Category";
$string['delete'] = "Delete";
$string['delete_check'] = "I am certain that I want to delete this category.";
$string['actions'] = "Actions";

// Similarity Comparison
$string['exaquest:similarity_title'] = 'Similarity Comparison';
$string['exaquest:similarity_button_tooltip'] = "Go to the Similarity Comparison overview";
$string['exaquest:similarity_button_label'] = "Question Similarity Comparison overview";
$string['exaquest:similarity_refresh_button_label'] = "Refresh Similarity Comparison overview";
$string['exaquest:similarity_update_button_label'] = "Save & Update";
$string['exaquest:similarity_compute_button_label'] = "Compute similarity";
$string['exaquest:similarity_persist_button_label'] = "Compute and persist Similarity";
$string['exaquest:similarity_substitute_checkbox_label'] = "Substitute IDs";
$string['exaquest:similarity_hide_checkbox_label'] = "Hide previous versions";
$string['exaquest:similarity_sort_select_label'] = "Sort By";
$string['exaquest:similarity_true'] = "True";
$string['exaquest:similarity_false'] = "False";
$string['exaquest:similarity_col_qid1'] = "Q1";
$string['exaquest:similarity_col_qid2'] = "Q2";
$string['exaquest:similarity_col_issimilar'] = "Similar";
$string['exaquest:similarity_col_similarity'] = "Similarity";
$string['exaquest:similarity_col_timestamp'] = "Timestamp";
$string['exaquest:similarity_col_threshold'] = "Threshold";
$string['exaquest:similarity_col_algorithm'] = "Algorithm";
$string['exaquest:similarity_edit_question_button'] = 'Edit question';
$string['exaquest:similarity_stat_total_count'] = "Total questions (unique ID)";
$string['exaquest:similarity_stat_total_latest_count'] = "Total (Latest, unique ID)";
$string['exaquest:similarity_stat_total_similar_q'] = "Total similar questions (unique ID)";
$string['exaquest:similarity_stat_total_dissimilar_q'] = "Total dissimilar (unique ID)";
$string['exaquest:similarity_stat_total_latest_similar_q'] = "Total similar (Latest, unique ID)";
$string['exaquest:similarity_stat_total_latest_dissimilar_q'] = "Total dissimilar (Latest, unique ID)";
$string['exaquest:similarity_stat_ratio_similar'] = "Ratio similar (unique ID)";
$string['exaquest:similarity_stat_ratio_dissimilar'] = "Ratio dissimilar (unique ID)";
$string['exaquest:similarity_stat_ratio_latest_similar'] = "Ratio similar (Latest, unique ID)";
$string['exaquest:similarity_stat_ratio_latest_dissimilar'] = "Ratio dissimilar (Latest, unique ID)";
$string['exaquest:similarity_settings_algorithm'] = 'Similarity Comparison Algorithm';
$string['exaquest:similarity_settings_algorithm_desc'] = 'Similarity Comparison Algorithm to use';
$string['exaquest:similarity_settings_algorithm_jarowinkler'] = 'Jaro Winkler';
$string['exaquest:similarity_settings_algorithm_smithwaterman'] = 'Smith Waterman Gotoh';
$string['exaquest:similarity_settings_threshold'] = 'Threshold [0.0-1.0]';
$string['exaquest:similarity_settings_threshold_desc'] =
    'Defines the threshold for considering two questions similar, range [0.0-1.0]';
$string['exaquest:similarity_settings_jwminprefixlength'] = 'Jaro Winkler - Minimum Prefix Length';
$string['exaquest:similarity_settings_jwminprefixlength_desc'] =
    'Jaro–Winkler similarity uses a prefix scale p which gives more favorable ratings to strings that match from the beginning for a set prefix length';
$string['exaquest:similarity_settings_jwprefixscale'] = 'Jaro Winkler - Minimum Prefix Scale';
$string['exaquest:similarity_settings_jwprefixscale_desc'] =
    'The prefix scale should not exceed 1/minPrefixLength, otherwise the similarity may be greater than 1, i.e. for a prefix length of 4, the scale should not exceed 0.25';
$string['exaquest:similarity_settings_swgmatchmalue'] = 'Smith Waterman Gotoh - Match Value';
$string['exaquest:similarity_settings_swgmatchmalue_desc'] = 'value when characters are equal (must be greater than mismatchValue)';
$string['exaquest:similarity_settings_swgmismatchvalue'] = 'Smith Waterman Gotoh - Mismatch Value';
$string['exaquest:similarity_settings_swgmismatchvalue_desc'] = 'penalty when characters are not equal';
$string['exaquest:similarity_settings_swggapvalue'] = 'Smith Waterman Gotoh - Gap Value';
$string['exaquest:similarity_settings_swggapvalue_desc'] = 'a non-positive gap penalty';
$string['exaquest:similarity_settings_nrofthreads'] = 'Number of threads';
$string['exaquest:similarity_settings_nrofthreads_desc'] =
    'if this value is greater than 1, it will utilize a multi-threaded implementation to compute the similarity, which should be much more performant for greater datasets';
$string['exaquest:similarity_exam_select_label'] = 'Wählen Sie eine Prüfung oder "alle Fragen" aus';
$string['exaquest:similarity_exam_select_default'] = 'Alle Fragen';

// capabilites
$string['exaquest:fragenersteller'] = 'Fragenersteller';
$string['exaquest:modulverantwortlicher'] = 'Modulverantwortlicher';
$string['exaquest:admintechnpruefungsdurchf'] = 'Admintechnischeprüfungsdurchführung';
$string['exaquest:pruefungskoordination'] = 'Prüfungskoordination';
$string['exaquest:pruefungsstudmis'] = 'Prüfungsstudentischermitarbeiter';
$string['exaquest:fachlfragenreviewer'] = 'Fachlicherfragenreviewer';
$string['exaquest:beurteilungsmitwirkende'] = 'Beurteilungsmitwirkende';
$string['exaquest:fachlicherpruefer'] = 'Fachlicherprüfer';
$string['exaquest:pruefungsmitwirkende'] = 'Prüfungsmitwirkende';
$string['exaquest:fachlicherzweitpruefer'] = 'Fachlicherzweitprüfer';
$string['exaquest:fachlfragenreviewerlight'] = 'Fachlfragenreviewerlight';
$string['exaquest:fragenerstellerlight'] = 'Fragenerstellerlight';
$string['exaquest:sekretariat'] = 'Sekretariat';

$string['exaquest:readallquestions'] = 'Lesen aller Fragen im freigegebene Fragenpool des zugeordneten Moduls (Module)';
$string['exaquest:readquestionstatistics'] = 'Fragenstatistik abfragen (Anzahl Fragen je Status, je Kategorie, je QS-Kennzahl...)';
$string['exaquest:changestatusofreleasedquestions'] =
    'Kommentar / Markierung von freigegebenen fremden Fragen im Fragenpool (zB Status-Änderung auf "Qualitätsprüfung erforderlich" Kommentar hier verpflichtend – Frage wird aus Fragenpool entfernt, kann nicht mehr zur Prüfung verwendet werden, bis wieder "Frage freigegeben"). ';
$string['exaquest:createquestion'] = 'Erstellen einer neuen Frage';
$string['exaquest:setstatustoreview'] =
    'Neue / geänderte eigene Frage zum Review freigeben (Status "zum Review": fachlichen Reviewer eintragen, automatisch auch zur Prüfungskoordination) (Eventuell optional ZML klärt mit MOVER)';
$string['exaquest:reviseownquestion'] = 'Überarbeitung der eigenen Fragen';
$string['exaquest:setstatustofinalised'] = 'Eigene Frage für Fragenpool freigeben (Status "Frage finalisiert")';
$string['exaquest:viewownrevisedquestions'] = 'Anzeige der begutachteten eigenen Fragen im Dashboard ';
$string['exaquest:viewquestionstoreview'] =
    'Anzeige zum Review zugeordnete Fragen (nur vom Modul, wo Zugriff besteht) im Dashboard';
$string['exaquest:editquestiontoreview'] =
    'Zum Review zugeordnete Fragen bearbeiten (neue Version erstellen) und/oder kommentieren  (Status: "Frage begutachtet" - dann automatisch zurück zum Fragenersteller – wenn keine Änderungen/Kommentar dann Status: "Frage finalisiert" – dann automatisch zum MOVE)';
$string['exaquest:viewfinalisedquestions'] = 'Anzeige der finalisierten Fragen im Dashboard';
$string['exaquest:viewquestionstorevise'] =
    'Anzeige der zur Begutachtung markierten Fragen ("Qualitätsprüfung erforderlich") im Dashboard';
$string['exaquest:releasequestion'] = 'Frage in Fragenpool aufnehmen (Status "Frage freigegeben")';
$string['exaquest:editallquestions'] = 'Überarbeitung aller Fragen im zugeordneten Fragenpool inkl. Statusänderung';
$string['exaquest:addquestiontoexam'] = 'Frage einer Prüfung zuordnen (Status "Frage zur Prüfung zugeordnet")';
$string['exaquest:releaseexam'] = 'Prüfung zur Durchführung freigeben (Status "Prüfung freigegeben")';
$string['exaquest:technicalreview'] = 'Formale Prüfung (existiert bereits) (Status: "Prüfung formal freigegeben"';
$string['exaquest:executeexam'] =
    'Prüfung durchführen - Passwort vergeben/bekannt geben, Prüfung sichtbar stellen, Dauer einstellen - automatische Abgabe (Status: "Prüfung in Durchführung")';
$string['exaquest:assignsecondexaminator'] = 'Zweitprüfer für kommissionelle Prüfung festlegen (zwei Zweitprüfer)';
$string['exaquest:definequestionblockingtime'] = 'Zeitraum für Fragensperre festlegen (default 1 Jahr)';
$string['exaquest:viewexamresults'] = 'Prüfungsergebnisse einsehen (Status: "Prüfung abgeschlossen")';
$string['exaquest:gradeexam'] = 'Prüfung beurteilen (Status "Prüfung beurteilt")';
$string['exaquest:createexamstatistics'] = '(Qualitäts-)Statistik zur Prüfung und der verwendeten Fragen generieren';
$string['exaquest:viewexamstatistics'] = '(Qualitäts-)Statistik zur Prüfung und der verwendeten Fragen ansehen';
$string['exaquest:correctexam'] =
    'Prüfung korrigieren (Fragen aus der Beurteilung nehmen – Zweitprüfer festlegen: Eine Person) (Status Prüfung: "Beurteilung geändert")';
$string['exaquest:acknowledgeexamcorrection'] = 'Korrigierte Prüfung bestätigen (Status Prüfung; "Beurteilungsänderung bestätigt")';
$string['exaquest:releaseexamgrade'] = 'Beurteilung freigeben (Status "Prüfungsbeurteilung freigegeben")';
$string['exaquest:releasecommissionalexamgrade'] =
    'Beurteilung kommissioneller Prüfung freigeben (Status "Kommissionelle Prüfungsbeurteilung freigegeben") VERPFLICHTEND von 3 Personen';
$string['exaquest:exportgradestokusss'] = 'Notenexport ins KUSSS';
$string['exaquest:executeexamreview'] = 'Prüfungseinsicht durchführen';
$string['exaquest:addparticipanttomodule'] = 'Personen einem Modul zuordnen';
$string['exaquest:assignroles'] = 'Rollen zuordnen';
$string['exaquest:changerolecapabilities'] =
    'Rechte in den Rollen ändern (doppelte Anmeldung: neue Nutzerdatenanmeldung, um nicht versehentlich etwas zu ändern)';
$string['exaquest:createroles'] =
    'Neue Rolle anlegen und Rechte zuweisen (doppelte Anmeldung: neue Nutzerdatenanmeldung, um nicht versehentlich etwas zu ändern)';

// Defined during development
$string['exaquest:viewstatistic'] = 'View statistics tab in exaquest';
$string['exaquest:viewsimilaritytab'] = 'View similarity tab in exaquest';
$string['exaquest:viewexamstab'] = 'View exams tab in exaquest';
$string['exaquest:viewcategorytab'] = 'View category tab in exaquest';
$string['check_active_exams'] = 'Check status of active Exaquest quizzes and update if finished.';
$string['exaquest:viewdashboardtab'] = 'View dashboard tab in exaquest';
$string['exaquest:viewquestionbanktab'] = 'View question tab in exaquest';
$string['exaquest:viewquestionstorelease'] = 'View questions to release';
$string['exaquest:viewdashboardoutsidecourse'] = 'View exaquest dashboard in moodle dashboard';
$string['exaquest:requestnewexam'] = 'Allow requesting a new exam in exaquest';
$string['exaquest:viewcreatedexams'] = 'View created exams in exaquest';
$string['exaquest:viewfinishedexams'] = 'View finished exams in exaquest';
$string['exaquest:viewnewexams'] = 'View new exams in exaquest';
$string['exaquest:viewgradesreleasedexams'] = 'View exams where grades have been released in exaquest';
$string['exaquest:assignaddquestions'] = 'Assign user to add questions to exams';
$string['exaquest:createexam'] = 'Create exams in exaquest';
$string['exaquest:dofachlichreview'] = 'Do fachlich review';
$string['exaquest:doformalreview'] = 'Do formal review';
$string['exaquest:exaquestuser'] = 'Is an exaquest user';
$string['exaquest:viewactiveexams'] = 'View active exams in exaquest';
$string['exaquest:viewownquestions'] = 'View own questions in exaquest';
$string['exaquest:viewreleasedexams'] = 'View released exams in exaquest';

// tasks
$string['check_active_exams_task'] = 'Update the status of all finished exams which have the satus "active" in Exaquest.';
$string['clean_up_tables'] = 'Clean the Exaquest tables from orphaned entries';
$string['create_daily_notifications'] = 'Create daily Exaquest notifications.';
$string['set_up_roles'] = 'Set up roles for Exaquest.';

$string['points_per'] = 'Points for Fragefach ';
$string['exaquest_settings'] = 'Exaquest Settings';

$string['assign_to_revise_from_quiz'] = 'Assign to revise';
$string['revise_question_from_quiz'] = 'Revise question';
$string['lock_from_quiz'] = 'Lock';

$string['send_exam_to_review'] = 'Send exam to review';
$string['force_send_exam_to_review'] = 'Prüfung sofort zur Begutachtung schicken TODO TRANSLATE';
$string['fachlich_release_exam'] = 'Fachlich release exam';
$string['assign_check_exam_grading'] = 'Assign exam to check grading';
$string['assign_check_exam_grading_label'] = 'Assign exam to check grading';
$string['assign_check_exam_grading_comment_placeholder'] = 'Comment';
$string['assign_check_exam_grading_button'] = 'Assign';
$string['assign_kommissionell_check_exam_grading'] = 'Assign exam for kommissionell check grading';
$string['assign_kommissionell_check_exam_grading_label'] = 'Assign the following fachliche Prüfer';
$string['assign_kommissionell_check_exam_grading_comment_placeholder'] = 'Comment';
$string['assign_kommissionell_check_exam_grading_button'] = 'Assign';
$string['select_students_label'] = 'Anfordern von folgenden Schüler:innen';
$string['assign_gradeexam'] = 'Assign exam to grade';
$string['assign_gradeexam_label'] = 'Assign exam to grade';
$string['select_questions_label'] = 'Select questions';
$string['select_filter_label'] = 'Select filter:';
$string['assign_gradeexam_comment_placeholder'] = 'Comment';
$string['exams_for_me_to_check_grading'] = 'Exams for me to check grading';
$string['exams_for_me_to_check_grading_title'] = 'Exams for me to check grading';
$string['kommissionell_exams_for_me_to_check_grading'] = 'Kommissionell exams for me to check grading';
$string['kommissionell_exams_for_me_to_check_grading_title'] = 'Kommissionell exams for me to check grading';
$string['exams_for_me_to_grade'] = 'Exams for me to grade';
$string['exams_for_me_to_grade_title'] = 'Exams for me to grade';
$string['please_change_exam_grading'] = 'Please change the grading of the exam <a href="{$a->url}">{$a->fullname}</a>. Comment: {$a->requestcomment}... Requested by: {$a->requester}';
$string['please_change_exam_grading_subject'] = 'Please change the grading of the exam {$a->fullname}';
$string['please_kommissionell_check_exam_grading'] = 'Bitte überprüfen Sie die Beurteilung der kommissionellen Prüfung <a href="{$a->url}">{$a->fullname}</a>. Kommentar: {$a->requestcomment}... Angefordert von: {$a->requester}';
$string['please_kommissionell_check_exam_grading_subject'] = 'Bitte überprüfen Sie die Beurteilung der kommissionellen Prüfung {$a->fullname}';
$string['assign_change_exam_grading'] = 'Request Change of Grading';
$string['assign_change_exam_grading_label'] = 'Request Change of Grading';
$string['assign_change_exam_grading_comment_placeholder'] = 'Comment';
$string['assign_change_exam_grading_button'] = 'Request';
$string['exams_for_me_to_change_grading'] = 'Exams for me to change grading';
$string['exams_for_me_to_change_grading_title'] = 'Exams for me to change grading';
$string['selected_student'] = 'Selected student';

$string['exams_for_me_to_fachlich_release_title'] = 'Exams to fachlich release';

# popuptexts:
$string['already_requested_from'] = 'Already requested from:';
$string['already_done'] = 'already done';

$string['Test-Name'] = 'Name der Prüfung';
$string['quizdatum'] = 'Quiz date and time';
$string['timelimit'] = 'Quiz Duration';
$string['quizattempt'] = 'Number of participants';
$string['quizcancelattempt'] = 'Number of cancelled attempt';
$string['statisticsheader'] = 'Distribution';
$string['statisticspoints'] = 'Points';
$string['statisticsgrade'] = 'Grade';
$string['numericaldist'] = 'Numerical Distribution';
$string['percentagedist'] = 'Percentag Distribution';

$string['go_to_exam_report_overview'] = 'Zum Prüfungsbericht TODO TRANSLATE';
$string['go_to_exam_report_grading'] = 'Zur Prüfungsbeurteilung TODO TRANSLATE';
$string['send_exam_to_revise'] = 'Prüfung zur Überarbeitung zurückschicken TODO TRANSLATE';

$string['points_per_help'] = 'This is the number of points per category. Please specify the points carefully.';
$string['minimum_percentage_help'] = 'Geben Sie die Bestehensgrenze für dieses Fragefach an, falls es ein Kernfach ist. Wenn es kein Kernfach ist, lassen Sie 0% stehen. TODO TRANSLATE';
$string['minimum_percentage'] = 'Minimum percentage to pass';
$string['change_status_and_remove_from_quiz'] = 'Change status of question to "to revise" and remove from quiz';

$string['questionstatisticsheader'] = 'Distribution of question subjects';
$string['questiontype'] = 'Question Type';
$string['numberofquestion'] = 'Number of Questions';
$string['difficultyavg'] = 'Avg. Difficulty (p) * in percent';
$string['selectnessavg'] = 'Avg. Selectiveness (r) * in percent';

$string['send'] = 'Send';
