<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_quranmemorizer_insert_data');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('insertdata', 'local_quranmemorizer'));

// Load quran.json
$quran_data_path = __DIR__ . '/../quran_data/quran.json';
if (!file_exists($quran_data_path)) {
    echo $OUTPUT->notification(get_string('quranjsonnotfound', 'local_quranmemorizer'), 'notifyerror');
    echo $OUTPUT->footer();
    die();
}

$quran_data = json_decode(file_get_contents($quran_data_path), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo $OUTPUT->notification(get_string('quranjsondecodeerror', 'local_quranmemorizer'), 'notifyerror');
    echo $OUTPUT->footer();
    die();
}

// Insert data into the database
global $DB;
$transaction = $DB->start_delegated_transaction();

try {
    foreach ($quran_data as $sura) {
        // Insert Sura
        $sura_id = $DB->insert_record('local_quranmemorizer_suras', [
            'id' => $sura['id'],
            'name' => $sura['name'],
        ]);

        // Insert Ayas
        foreach ($sura['verses'] as $verse) {
            $DB->insert_record('local_quranmemorizer_ayas', [
                'sura_id' => $sura['id'],
                'text' => $verse['text'],
            ]);
        }

        // Insert Audio Path
        $audio_file = sprintf('%03d.mp3', $sura['id']);
        $audio_path = '/local/quranmemorizer/audio/Qari1/' . $audio_file;
        $DB->insert_record('local_quranmemorizer_audio', [
            'sura_id' => $sura['id'],
            'audio_path' => $audio_path,
        ]);
    }

    $transaction->allow_commit();
    echo $OUTPUT->notification(get_string('datainsertedsuccessfully', 'local_quranmemorizer'), 'notifysuccess');
} catch (Exception $e) {
    $transaction->rollback($e);
    echo $OUTPUT->notification(get_string('datainsertionfailed', 'local_quranmemorizer'), 'notifyerror');
}

echo $OUTPUT->footer();