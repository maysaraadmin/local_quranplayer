<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Require the capability to insert data.
require_capability('local/quranmemorizer:view', context_system::instance());

// Define the path to quran.json.
$quran_data_path = __DIR__ . '/../quran_data/quran.json';

// Check if the file exists.
if (!file_exists($quran_data_path)) {
    echo $OUTPUT->notification(get_string('quranjsonnotfound', 'local_quranmemorizer'), 'notifyerror');
    die();
}

// Load and decode the JSON file.
$quran_data = json_decode(file_get_contents($quran_data_path), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo $OUTPUT->notification(get_string('quranjsondecodeerror', 'local_quranmemorizer') . ': ' . json_last_error_msg(), 'notifyerror');
    die();
}

// Validate JSON structure.
if (!is_array($quran_data)) {
    echo $OUTPUT->notification(get_string('quranjsondecodeerror', 'local_quranmemorizer') . ': Invalid JSON structure.', 'notifyerror');
    die();
}

// Insert data into the database.
global $DB;
$transaction = $DB->start_delegated_transaction();

try {
    foreach ($quran_data as $sura) {
        // Validate Sura data.
        if (!isset($sura['id']) || !isset($sura['name']) || !isset($sura['verses'])) {
            throw new Exception('Invalid Sura data structure in JSON.');
        }

        // Check if Sura already exists.
        if (!$DB->record_exists('local_quranmemorizer_suras', ['id' => $sura['id']])) {
            // Insert Sura.
            $sura_record = new stdClass();
            $sura_record->id = $sura['id'];
            $sura_record->name = $sura['name'];
            $DB->insert_record('local_quranmemorizer_suras', $sura_record);
        }

        // Insert Ayas.
        foreach ($sura['verses'] as $verse) {
            if (!isset($verse['text'])) {
                throw new Exception('Invalid Aya data structure in JSON.');
            }

            // Check if Aya already exists.
            if (!$DB->record_exists('local_quranmemorizer_ayas', ['sura_id' => $sura['id'], 'text' => $verse['text']])) {
                $aya_record = new stdClass();
                $aya_record->sura_id = $sura['id'];
                $aya_record->text = $verse['text'];
                $DB->insert_record('local_quranmemorizer_ayas', $aya_record);
            }
        }

        // Insert Audio Path.
        $audio_file_path = '/local/quranmemorizer/audio/Qari1/' . sprintf('%03d.mp3', $sura['id']);
        if (!$DB->record_exists('local_quranmemorizer_audio', ['sura_id' => $sura['id']])) {
            $audio_record = new stdClass();
            $audio_record->sura_id = $sura['id'];
            $audio_record->audio_path = $audio_file_path;
            $DB->insert_record('local_quranmemorizer_audio', $audio_record);
        }
    }

    // Commit the transaction if everything is successful.
    $transaction->allow_commit();
    echo $OUTPUT->notification(get_string('datainsertedsuccessfully', 'local_quranmemorizer'), 'notifysuccess');
} catch (Exception $e) {
    // Rollback the transaction in case of an error.
    $transaction->rollback($e);
    echo $OUTPUT->notification(get_string('datainsertionfailed', 'local_quranmemorizer') . ': ' . $e->getMessage(), 'notifyerror');
}