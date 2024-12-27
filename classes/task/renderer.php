<?php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/quran/lib.php');

$id = required_param('id', PARAM_INT);
$context = context_module::instance($id);
require_login();

defined('MOODLE_INTERNAL') || die();

/**
 * Custom renderer for Quran Memorization plugin.
 */
class mod_quran_renderer extends plugin_renderer_base {

    /**
     * Render the progress tracking interface for students.
     *
     * @param array $chapters List of chapters with verses and progress.
     * @return string HTML output.
     */
    public function render_student_progress($chapters) {
        $output = html_writer::tag('h2', get_string('yourprogress', 'mod_quran'));
        $output .= html_writer::start_tag('form', ['method' => 'post', 'action' => 'save_progress.php']);

        foreach ($chapters as $chapter => $verses) {
            $output .= html_writer::tag('h3', get_string('chapter', 'mod_quran', $chapter));
            $output .= html_writer::start_tag('ul', ['class' => 'chapter-verses']);

            foreach ($verses as $verse => $memorized) {
                $checkbox = html_writer::checkbox(
                    "memorized[$chapter][$verse]",
                    1,
                    $memorized,
                    get_string('verse', 'mod_quran', $verse),
                    ['class' => 'memorization-checkbox']
                );
                $output .= html_writer::tag('li', $checkbox, ['class' => 'verse-item']);
            }

            $output .= html_writer::end_tag('ul');
        }

        $output .= html_writer::tag('button', get_string('saveprogress', 'mod_quran'), [
            'type' => 'submit',
            'class' => 'btn btn-primary',
        ]);

        $output .= html_writer::end_tag('form');
        return html_writer::div($output, 'memorization-progress');
    }

    /**
     * Render the teacher dashboard with student progress.
     *
     * @param array $students Progress data for each student.
     * @return string HTML output.
     */
    public function render_teacher_dashboard($students) {
        $output = html_writer::tag('h2', get_string('studentprogress', 'mod_quran'));

        $table = new html_table();
        $table->head = [
            get_string('studentname', 'mod_quran'),
            get_string('progress', 'mod_quran')
        ];

        foreach ($students as $student) {
            $name = html_writer::tag('span', $student->name);
            $progress = $this->render_progress_bar($student->progress);
            $table->data[] = [$name, $progress];
        }

        $output .= html_writer::table($table);
        return html_writer::div($output, 'teacher-dashboard');
    }

    /**
     * Render a simple progress bar.
     *
     * @param float $percentage Completion percentage.
     * @return string HTML output.
     */
    public function render_progress_bar($percentage) {
        $progress = html_writer::tag('div', round($percentage) . '%', [
            'class' => 'progress-bar-inner',
            'style' => "width: {$percentage}%;"
        ]);

        return html_writer::div($progress, 'progress-bar-outer');
    }
}

$renderer = $PAGE->get_renderer('mod_quran');

// Example data for rendering.
// In a real scenario, fetch this data from the database.
$chapters = [
    1 => [1 => true, 2 => false, 3 => false],
    2 => [1 => true, 2 => true, 3 => false],
];

echo $OUTPUT->header();
echo $renderer->render_student_progress($chapters);
echo $OUTPUT->footer();