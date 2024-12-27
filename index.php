<?php
require_once(__DIR__ . '/../../config.php');

$id = required_param('id', PARAM_INT); // Course module ID.
$cm = get_coursemodule_from_id('quran', $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$context = context_module::instance($cm->id);

require_login($course, true, $cm);
$PAGE->set_url('/mod/quran/index.php', ['id' => $id]);
$PAGE->set_title(format_string($cm->name));
$PAGE->set_heading($course->fullname);

// Get the plugin renderer.
$renderer = $PAGE->get_renderer('mod_quran');

// Check user role.
$is_teacher = has_capability('mod/quran:viewreports', $context);
$is_student = has_capability('mod/quran:trackprogress', $context);

echo $OUTPUT->header();

// Display teacher or student dashboard.
if ($is_teacher) {
    echo html_writer::tag('h2', get_string('teacherdashboard', 'mod_quran'));
    // Fetch student progress data from the database.
    // Example: $students = $DB->get_records_sql('SELECT ...');
    $students = [
        (object)['name' => 'John Doe', 'progress' => 45],
        (object)['name' => 'Jane Smith', 'progress' => 75],
    ];
    echo $renderer->render_teacher_dashboard($students);
} elseif ($is_student) {
    echo html_writer::tag('h2', get_string('studentprogress', 'mod_quran'));
    // Fetch student progress data from the database.
    // Example: $chapters = $DB->get_records_sql('SELECT ...');
    $chapters = [
        1 => [1 => true, 2 => false, 3 => false],
        2 => [1 => true, 2 => true, 3 => false],
    ];
    echo $renderer->render_student_progress($chapters);
} else {
    echo html_writer::tag('p', get_string('norights', 'mod_quran'));
}

echo $OUTPUT->footer();
