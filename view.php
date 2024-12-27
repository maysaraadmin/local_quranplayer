<?php
require_once(__DIR__ . '/../../config.php');
$PAGE->requires->css('/mod/quran/styles.css');

$id = required_param('id', PARAM_INT); // Course module ID.
$context = context_module::instance($id);
require_login();

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('yourprogress', 'mod_quran'));

// Fetch progress.
global $DB, $USER;
$progress = $DB->get_records('quran_progress', ['userid' => $USER->id]);

// Display progress.
if (!empty($progress)) {
    foreach ($progress as $record) {
        echo html_writer::div(
            get_string('chapter', 'mod_quran', $record->chapter) . ", " .
            get_string('verse', 'mod_quran', $record->verse) . ", " .
            ($record->memorized ? 'Memorized' : 'Not Memorized'),
            'progress-item'
        );
    }
} else {
    echo html_writer::tag('p', 'No progress recorded yet.');
}

echo $OUTPUT->footer();