<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/quranmemorizer/classes/form/sura_selection_form.php');
require_once($CFG->dirroot . '/local/quranmemorizer/classes/quran_memorizer.php');

global $PAGE, $OUTPUT;

$PAGE->set_url(new moodle_url('/local/quranmemorizer/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'local_quranmemorizer'));
$PAGE->set_heading(get_string('pluginname', 'local_quranmemorizer'));

echo $OUTPUT->header();

$form = new sura_selection_form();

if ($form->is_cancelled()) {
    // Handle form cancel operation, if needed.
} else if ($data = $form->get_data()) {
    $sura = local_quranmemorizer::get_sura($data->sura);
    echo $OUTPUT->render_sura_selection($sura);
} else {
    $form->display();
}

echo $OUTPUT->footer();