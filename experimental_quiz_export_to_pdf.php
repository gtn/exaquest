<?php
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();

$content = '';

// quizDetails is an array of all the quiz data you want to export

foreach ($quizDetails as $question) {
    $content .= "<h1>{$question['title']}</h1>";
    $content .= "<p>Erstellt von: {$question['creator']}</p>";
    $content .= "<p>Frage ID: {$question['id']}</p>";
    $content .= "<p>Lösungen:</p>";
    foreach ($question['answers'] as $answer) {
        $content .= "<p>{$answer}</p>";
    }
    $content .= "<hr>";
}

$mpdf->WriteHTML($content);
$mpdf->Output('quiz_export.pdf', 'D');
