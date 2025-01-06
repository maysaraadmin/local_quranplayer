<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/quranmemorizer/classes/form/sura_selection_form.php');
require_once($CFG->dirroot . '/local/quranmemorizer/classes/quran_memorizer.php');

global $PAGE, $OUTPUT, $USER;

// Require the capability to view the plugin.
require_capability('local/quranmemorizer:view', context_system::instance());

// Set up the page.
$PAGE->set_url(new moodle_url('/local/quranmemorizer/index.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'local_quranmemorizer'));
$PAGE->set_heading(get_string('pluginname', 'local_quranmemorizer'));

// Add a link to the plugin in the navigation menu.
$PAGE->navbar->add(get_string('pluginname', 'local_quranmemorizer'), new moodle_url('/local/quranmemorizer/index.php'));

// Use the plugin's renderer.
$renderer = $PAGE->get_renderer('local_quranmemorizer');

echo $OUTPUT->header();

// Display the Sura selection form.
$form = new sura_selection_form();

if ($form->is_cancelled()) {
    // Handle form cancel operation, if needed.
} else if ($data = $form->get_data()) {
    if (!empty($data->sura)) {
        $sura = local_quranmemorizer::get_sura($data->sura);
        echo $renderer->render_sura_selection($sura); // Use the plugin's renderer.
    } else {
        debugging('No Sura selected', DEBUG_DEVELOPER);
    }
} else {
    $form->display();
}

echo $OUTPUT->footer();