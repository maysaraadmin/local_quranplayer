<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Set up the admin page.
admin_externalpage_setup('local_quranmemorizer_insert_data');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('insertdata', 'local_quranmemorizer'));

// Define the path to quran.json.
$quran_data_path = __DIR__ . '/../quran_data/quran.json';

// Check if the file exists.
if (!file_exists($quran_data_path)) {
    echo $OUTPUT->notification(get_string('quranjsonnotfound', 'local_quranmemorizer'), 'notifyerror');
    echo $OUTPUT->footer();
    die();
}

// Load and decode the JSON file.
$quran_data = json_decode(file_get_contents($quran_data_path), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo $OUTPUT->notification(get_string('quranjsondecodeerror', 'local_quranmemorizer'), 'notifyerror');
    echo $OUTPUT->footer();
    die();
}

// Insert data into the database.
global $DB;
$transaction = $DB->start_delegated_transaction();

try {
    foreach ($quran_data as $sura) {
        // Insert Sura.
        $sura_record = new stdClass();
        $sura_record->id = $sura['id'];
        $sura_record->name = $sura['name'];
        $DB->insert_record('local_quranmemorizer_suras', $sura_record);

        // Insert Ayas.
        foreach ($sura['verses'] as $verse) {
            $aya_record = new stdClass();
            $aya_record->sura_id = $sura['id'];
            $aya_record->text = $verse['text'];
            $DB->insert_record('local_quranmemorizer_ayas', $aya_record);
        }

        // Insert Audio Path.
        $audio_record = new stdClass();
        $audio_record->sura_id = $sura['id'];
        $audio_record->audio_path = '/local/quranmemorizer/audio/Qari1/' . sprintf('%03d.mp3', $sura['id']);
        $DB->insert_record('local_quranmemorizer_audio', $audio_record);
    }

    // Commit the transaction if everything is successful.
    $transaction->allow_commit();
    echo $OUTPUT->notification(get_string('datainsertedsuccessfully', 'local_quranmemorizer'), 'notifysuccess');
} catch (Exception $e) {
    // Rollback the transaction in case of an error.
    $transaction->rollback($e);
    echo $OUTPUT->notification(get_string('datainsertionfailed', 'local_quranmemorizer'), 'notifyerror');
}

echo $OUTPUT->footer();