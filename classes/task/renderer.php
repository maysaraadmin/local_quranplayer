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
        global $DB;
    
        $output = html_writer::tag('h2', get_string('yourprogress', 'mod_quran'), ['id' => 'quran-progress-heading']);
        $output .= html_writer::start_tag('form', ['method' => 'post', 'action' => 'save_progress.php', 'aria-labelledby' => 'quran-progress-heading']);
    
        foreach ($chapters as $chapter => $verses) {
            $output .= html_writer::tag('h3', get_string('chapter', 'mod_quran', $chapter), ['id' => 'chapter-' . $chapter]);
            $output .= html_writer::start_tag('ul', ['class' => 'chapter-verses', 'aria-labelledby' => 'chapter-' . $chapter]);
    
            foreach ($verses as $verse => $memorized) {
                $verse_data = quran_get_verses($chapter, $verse);
                $verse_text = $verse_data ? format_text($verse_data->text, FORMAT_PLAIN) : '';
    
                $checkbox = html_writer::checkbox(
                    "memorized[$chapter][$verse]",
                    1,
                    $memorized,
                    get_string('verse', 'mod_quran', $verse) . ': ' . $verse_text,
                    ['class' => 'memorization-checkbox', 'aria-label' => get_string('verse', 'mod_quran', $verse)]
                );
                $output .= html_writer::tag('li', $checkbox, ['class' => 'verse-item']);
            }
    
            $output .= html_writer::end_tag('ul');
        }
    
        $output .= html_writer::tag('button', get_string('saveprogress', 'mod_quran'), [
            'type' => 'submit',
            'class' => 'btn btn-primary',
            'aria-label' => get_string('saveprogress', 'mod_quran')
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
        $output = html_writer::tag('h2', get_string('studentprogress', 'mod_quran'), ['id' => 'teacher-dashboard-heading']);

        $table = new html_table();
        $table->head = [
            get_string('studentname', 'mod_quran'),
            get_string('progress', 'mod_quran')
        ];
        $table->attributes['aria-labelledby'] = 'teacher-dashboard-heading';

        foreach ($students as $student) {
            $name = html_writer::tag('span', $student->name, ['aria-label' => 'Student Name']);
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
            'style' => "width: {$percentage}%;",
            'aria-label' => get_string('progresscompleted', 'mod_quran', round($percentage))
        ]);

        return html_writer::div($progress, 'progress-bar-outer', ['role' => 'progressbar', 'aria-valuenow' => $percentage, 'aria-valuemin' => '0', 'aria-valuemax' => '100']);
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