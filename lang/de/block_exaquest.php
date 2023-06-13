<?php
$string['pluginname'] = 'Fragenverwaltungstool';
$string['exaquest'] = 'Fragenverwaltungstool';
$string['exaquest:addinstance'] = 'Neuen exaquest-block hinzufügen';
$string['exaquest:myaddinstance'] = 'Neuen exaquest-block zu meiner Moodleseite hinzufügen';
$string['exaquest:view'] = 'Exaquest Block anzeigen.';

// Block
$string['dashboard'] = 'Übersicht';
$string['dashboard_of_course'] = 'Übersicht Kurs "{$a}"';
$string['get_questionbank'] = 'Fragensammlung';
$string['similarity'] = 'Ähnlichkeitsübersicht';
$string['exams'] = 'Prüfungen';
$string['category_settings'] = 'Kategorieeinstellungen';
$string['save_and_return'] = 'Speichern und zu Exaquest';
$string['todos_are_open'] = ' TODOs sind offen.';

$string['request_questions'] = 'Neue Frage anfordern';
$string['request_questions_label'] = 'Neue Frage anforderen von ...';
$string['request_questions_comment_placeholder'] = 'Welche Art von Frage wird benötigt? Verpflichtendes Feld.';
$string['comment_placeholder_mandatory'] = 'Kommentar. Verpflichtendes Feld.';
$string['comment_placeholder'] = 'Kommentar.';
$string['request_questions_button'] = 'Anfordern';
$string['revise_questions_label'] = 'Folgende Fragen sind zur Überabeitung markiert:';
$string['formal_review_questions_label'] = 'Folgende Fragen sind zur formalen Finalisierung markiert:';
$string['fachlich_review_questions_label'] = 'Folgende Fragen sind zur fachlichen Finalisierung markiert:';
$string['write_a_comment'] = 'Schreiben Sie einen Kommentar:';

$string['request_exams'] = 'Neue Prüfung anfordern';
$string['request_exams_label'] = 'Neue Prüfung anforderen von ...';
$string['request_exams_comment_placeholder'] = 'Welche Art von Prüfung wird benötigt? Optional.';
$string['request_exams_button'] = 'Anfordern';


$string['set_quizquestioncount'] = 'Benötigte Fragenanzahl pro Fragefach festlegen';

$string['mark_as_done'] = 'Als erledigt markieren';
$string['mark_selected_as_done_button'] = 'Selektierte Anfragen als erledigt markieren';

$string['assign_addquestions'] = 'Fragenzuweisung anfordern';
$string['assign_addquestions_label'] = 'Fragenzuweisung anfordern von ...';
$string['assign_addquestions_comment_placeholder'] = 'Kommentar an die ausgewählten Personen';

// Messages
$string['messageprovider:fillexam'] = 'Neue Fragen wurden angefordert';
$string['please_create_new_questions'] =
    'Bitte erstellen Sie eine neue Frage in <a href="{$a->url}">{$a->fullname}</a>. Kommentar: {$a->requestcomment}';
$string['please_create_new_questions_subject'] = 'Bitte erstellen Sie eine neue Frage in {$a->fullname}';

$string['messageprovider:fillexam'] = 'Eine Prüfung soll befüllt werden';
$string['please_fill_exam'] =
    'Bitte befüllen Sie die Prüfung <a href="{$a->url}">{$a->fullname}</a> mit Fragen. Kommentar: {$a->requestcomment}';
$string['please_fill_exam_subject'] = 'Bitte befüllen Sie die Prüfung {$a->fullname} mit Fragen';

$string['messageprovider:newexamsrequest'] = 'Neue Prüfungen wurden angefordert';
$string['please_create_new_exams'] =
    'Bitte erstellen Sie eine neue Prüfung in <a href="{$a->url}">{$a->fullname}</a>. Kommentar: {$a->requestcomment}';
$string['please_create_new_exams_subject'] = 'Bitte erstellen Sie eine neue Prüfung in {$a->fullname}';

$string['messageprovider:revisequestion'] = 'Fragen wurden zur Überarbeitung zugeteilt';
$string['please_revise_question'] =
    'Bitte überarbeiten Sie die Frage <a href="{$a->url}">{$a->fullname}</a>. Kommentar: {$a->requestcomment}';
$string['please_revise_question_subject'] = 'Bitte überarbeiten Sie die Frage {$a->fullname}';

$string['messageprovider:releasequestion'] = 'Fragen wurden begutachtet und können freigegeben werden';
$string['please_release_question'] = 'Bitte geben Sie die Frage <a href="{$a->url}">{$a->fullname}</a> frei.';
$string['please_release_question_subject'] = 'Bitte geben Sie die Frage {$a->fullname} frei.';

$string['messageprovider:reviewquestion'] = 'Fragen wurden zum Beurteilen zugeteilt';
$string['please_review_question'] =
    'Bitte beurteilen Sie die Frage in <a href="{$a->url}">{$a->fullname}</a>. Kommentar: {$a->requestcomment}';
$string['please_review_question_subject'] = 'Bitte beurteilen Sie die Frage {$a->fullname}';

$string['messageprovider:dailytodos'] = 'Tägliche todo Nachricht';
$string['dailytodos'] = 'Sie haben folgende TODOs: <br> {$a->todosmessage}';
$string['dailytodos_subject'] = 'Exaquest TODOs';
$string['todos_in_course'] = '{$a->todoscount} TODOs in Kurs <a href="{$a->url}">{$a->fullname}</a><br>';

$string['messageprovider:daily_released_questions'] = 'Gestern veröffentlichte Fragen';
$string['daily_released_questions'] =
    'Es wurden gestern Fragen in folgenden Kursen veröffentlicht: <br> {$a->daily_released_questions_message}';
$string['daily_released_questions_subject'] = 'Exaquest veröffentlichte Fragen';
$string['daily_released_questions_in_course'] =
    '{$a->daily_released_questions} Fragen wurden gestern in  <a href="{$a->url}">{$a->fullname}</a> veröffentlicht.<br>';

// Roles and Capabilities
$string['exaquest:fragenersteller'] = 'Erstellen von Fragen in Exaquest';
$string['exaquest:modulverantwortlicher'] = 'Verantwortlich für ein Modul';
$string['setuproles'] = 'Erstellen von Rollen und Erlaubnissen';

// Exams

$string['create_new_exam'] = 'Neue Prüfung erstellen';
$string['add_questions_to_quiz'] = 'Fragen zu Prüfung hinzufügen';
$string['add_to_quiz'] = 'Zur Prüfung hinzufügen';
$string['questionbank_selected_quiz'] = 'Fragenlist mit ausgewählter Prüfung: ';
$string['exams_overview'] = 'Exams overview';
$string['new_exams'] = 'Neue Prüfungen';
$string['created_exams'] = 'Mit fragen befüllte Prüfungen';
$string['fachlich_released_exams'] = 'Fachlich freigegebene Prüfungen';
$string['formal_released_exams'] = 'Formal freigegebene Prüfungen';
$string['active_exams'] = 'Prüfungen in Durchführung';
$string['finished_exams'] = 'Abgeschlossene Prüfungen';
$string['exams_grades_released'] = 'Prüfungen, von denen die Noten freigegeben wurden';
$string['add_questions_to_these_exams'] = 'Fügen Sie Fragen zu folgenden Prüfungen hinzu:';
$string['usage_check_column'] = 'Verwendungsüberprüfung';
$string['check_added_questions'] = 'Zugeordnete Fragen überprüfen';
$string['remove_from_quiz'] = 'Frage von Prüfung entfernen';

// Dasboardcard
$string['questions_overview_title'] = 'FRAGEN';
$string['exams_overview_title'] = 'PRÜFUNGEN';
$string['my_questions_title'] = 'MEINE FRAGEN';
$string['examinations_title'] = 'PRÜFUNGEN';
$string['todos_title'] = 'TODOs';
$string['statistics_title'] = 'STATISTIKEN';
$string['overview'] = 'Überblick';

$string['questions_overall_count'] = 'Fragen insgesamt';
$string['questions_new_count'] = 'Neue Fragen welche noch nicht zur Begutachtung freigegeben wurden';
$string['questions_reviewed_count'] = 'Vollständig begutachtete Fragen';
$string['questions_to_review_count'] = 'Fragen zu begutachten';
$string['questions_to_revise_count'] = 'Fragen zu überarbeiten';
$string['questions_fachlich_reviewed_count'] = 'Nur fachlich begutachtete Fragen. Formal noch zu begutachten';
$string['questions_formal_reviewed_count'] = 'Nur formal begutachtete Fragen. Fachlich noch zu begutachten';
$string['questions_finalised_count'] = 'Finalisierte Fragen';
$string['questions_released_count'] = 'Freigegebene Fragen';
$string['questions_released_and_to_review_count'] = 'Freigegebene Fragen die überarbeitet werden müssen';

$string['my_questions_count'] = 'Fragen von mir erstellt';
$string['my_questions_finalised_count'] = 'Meine begutachteten Fragen';
$string['my_questions_to_review_count'] = 'Meine Fragen zu begutachten';

$string['list_of_exams_with_status'] = 'Liste von Prüfungen mit Status:';
$string['create_new_exam_button'] = 'Neue Prüfung erstellen';

$string['questions_for_me_to_review'] = 'Fragen soll ich begutachten';
$string['questions_for_me_to_submit'] = 'Fragen soll ich einreichen';
$string['questions_for_me_to_create'] = 'Folgende Fragen sind noch zu erstellen';
$string['questions_for_me_to_create_title'] = 'Fragen die ich erstellen soll';
$string['questions_for_me_to_revise'] = 'Fragen soll ich überarbeiten';
$string['questions_for_me_to_release'] = 'Fragen soll ich freigeben';
$string['compare_questions'] = 'Fragen vergleichen';
$string['exams_for_me_to_create'] = 'Prüfungen soll ich erstellen';
$string['exams_for_me_to_fill'] = 'Prüfungen soll ich mit Fragen befüllen';
$string['exams_for_me_to_fill_title'] = 'Prüfungen die ich mit Fragen befüllen soll';

//Questionbank

$string['show_all_questions'] = 'Alle Fragen anzeigen';
$string['show_all_imported_questions'] = 'Importierte Fragen anzeigen';
$string['show_all_new_questions'] = 'Alle neuen Fragen, welche noch nicht veröffentlich wurden anzeigen';
$string['show_my_created_questions'] = 'Meine erstelleten Fragen anzeigen';
$string['show_my_created_questions_to_submit'] = 'Meine erstelleten Fragen die ich noch veröffentlichen muss anzeigen';
$string['show_all_questions_to_review'] = 'Fragen zur Begutachtung anzeigen';
$string['show_all_fachlich_reviewed_questions_to_review'] = 'Fachlich begutachtete Fragen anzeigen. Formal noch zu begutachten';
$string['show_all_formal_reviewed_questions_to_review'] = 'Formal begutachtete Fragen anzeigen. Fachlich noch zu begutachten';
$string['show_questions_for_me_to_review'] = 'Meine Fragen zur Begutachtung anzeigen';
$string['show_questions_to_revise'] = 'Fragen zur Überabeitung anzeigen';
$string['show_questions_for_me_to_revise'] = 'Meine Fragen zur Überabeitung anzeigen';
$string['show_questions_to_release'] = 'Freizugebende Fragen anzeigen';
$string['show_questions_for_me_to_release'] = 'Meine freizugebenden Fragen anzeigen';
$string['show_all_released_questions'] = 'Alle freigegebenen Fragen anzeigen';

$string['created'] = 'Erstellt:';
$string['review'] = 'Begutachten:';
$string['revise'] = 'Überarbeiten:';
$string['release'] = 'Freigeben:';

$string['open_question_for_review'] = 'Frage zur Begutachtung freigeben';
$string['formal_review_done'] = 'Formal finalisieren';
$string['fachlich_review_done'] = 'Fachlich finalisieren';
$string['revise_question'] = 'Frage zur Überarbeitung schicken';
$string['release_question'] = 'Frage freigeben';
$string['skip_and_release_question'] = 'Überspringen und Freigeben';
$string['release_question_warning'] =
    'Sind Sie sicher, dass Sie die Begutachtung überspringen und die Frage direkt freigeben wollen?';
$string['release_question_warning_title'] = 'Warnung';
$string['change_status'] = 'Status verändern';
$string['notification_will_be_sent_to_pk'] =
    'Die Prüfungskoordination wird auch eine Benachrichtigung erhalten, die formale Begutachtung durchzuführen.';

$string['new_question'] = 'Neu erstellt';
$string['to_revise'] = 'Zu überabeiten';
$string['to_assess'] = 'Zu begutachten';
$string['formal_done'] = 'Formal finalisiert';
$string['fachlich_done'] = 'Fachlich finalisiert';
$string['finalised'] = 'Finalisiert';
$string['released'] = 'Freigegeben';
$string['imported_question'] = 'Importiert';

$string['question_id'] = 'Fragen ID';
$string['ownername'] = 'Erstellt von';
$string['lastchanged'] = 'Zuletzt verändert';


$string['open_for_review_text'] = 'Begutachtung zuteilen zu..';
$string['revise_text'] = 'Überarbeitung zuteilen zu..';
$string['open_for_review_title'] = 'Begutachtung';
$string['revise_title'] = 'Überarbeitung';

//Category Settings
$string['settings_title'] = 'Kategorieeinstellungen';
$string['settings_description'] = 'Bitte nur eine Kategorie pro Zeile einfügen';

$string['category_options'] = 'Fragekategorien';

$string['edit'] = "Bearbeiten";
$string['submit'] = "Bestätigen";
$string['add_category'] = "Kategorie hinzufügen";

// Similarity Comparison
$string['exaquest:similarity_title'] = 'Ähnlichkeitsvergleich';
$string['exaquest:similarity_button_tooltip'] = "Zur Ähnlichkeitsvergleichsübersicht wechseln";
$string['exaquest:similarity_button_label'] = "Ähnlichkeitsvergleichsübersicht";
$string['exaquest:similarity_refresh_button_label'] = "Ähnlichkeitsvergleichsübersicht aktualisieren";
$string['exaquest:similarity_update_button_label'] = "Speichern und aktualisieren";
$string['exaquest:similarity_compute_button_label'] = "Ähnlichkeit berechnen";
$string['exaquest:similarity_persist_button_label'] = "Anhaltende Ähnlichkeit berechnen";
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

// capabilites
$string['exaquest:myaddinstance'] = 'myaddinstance';
$string['exaquest:addinstance'] = 'addinstance';

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
$string['exaquest:viewdashboardtab'] = 'View dashboard tab in exaquest';
$string['exaquest:viewquestionbanktab'] = 'View question tab in exaquest';
$string['exaquest:viewquestionstorelease'] = 'View questions to release';
$string['exaquest:viewdashboardoutsidecourse'] = 'View exaquest dashboard in moodle dashboard';
$string['exaquest:requestnewexam'] = 'Allow requesting a new exam in exaquest';
$string['exaquest:viewcreatedexams'] = 'View created exams in exaquest';
$string['exaquest:viewfinishedexams'] = 'View finished exams in exaquest';
$string['exaquest:viewgradesreleasedexams'] = 'View released exams in exaquest';
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
$string['check_active_exams'] =
    'Update alle bereits beendeten Prüfungen welche den Status "aktiv" in Exaquest haben, und aktualisiere den Status.';
$string['clean_up_tables'] = 'Säubere die Exaquest Tabellen, falls fehlerhafte Daten bestehen.';
$string['create_daily_notifications'] = 'Erstelle die täglichen Exaquest-Nachrichten.';
$string['set_up_roles'] = 'Rollen für Exaquest aufsetzen.';