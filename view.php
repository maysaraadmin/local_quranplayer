<?php
require_once(__DIR__ . '/../../config.php');
$PAGE->requires->css('/mod/quran/styles.css');

$id = required_param('id', PARAM_INT); // Course module ID.
$context = context_module::instance($id);
require_login();

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('yourprogress', 'mod_quran'), ['id' => 'progress-heading']);

// Fetch progress.
global $DB, $USER;
try {
    $progress = $DB->get_records('quran_progress', ['userid' => $USER->id]);
} catch (dml_exception $e) {
    debugging('Failed to fetch progress: ' . $e->getMessage(), DEBUG_DEVELOPER);
    $progress = [];
}

// Display progress.
if (!empty($progress)) {
    foreach ($progress as $record) {
        echo html_writer::div(
            get_string('chapter', 'mod_quran', $record->chapter) . ", " .
            get_string('verse', 'mod_quran', $record->verse) . ", " .
            ($record->memorized ? 'Memorized' : 'Not Memorized'),
            'progress-item',
            ['aria-label' => 'Progress Item']
        );
    }
} else {
    echo html_writer::tag('p', 'No progress recorded yet.', ['aria-label' => 'No Progress']);
}

echo $OUTPUT->footer();