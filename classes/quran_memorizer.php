<?php
class local_quranmemorizer {
    public static function get_sura($sura_id) {
        global $DB;

        try {
            // Retrieve Sura information.
            $sura = $DB->get_record('local_quranmemorizer_suras', ['id' => $sura_id]);
            if (!$sura) {
                return null; // Return null if Sura is not found.
            }

            // Retrieve Ayas (verses) for the selected Sura.
            $ayas = $DB->get_records('local_quranmemorizer_ayas', ['sura_id' => $sura_id], 'id ASC');
            $verses = array_map(function($aya) {
                return $aya->text;
            }, $ayas);

            // Retrieve audio file path for the selected Sura.
            $audio = $DB->get_record('local_quranmemorizer_audio', ['sura_id' => $sura_id]);

            return [
                'id' => $sura->id,
                'name' => $sura->name,
                'ayas' => $verses,
                'audio_url' => $audio ? $audio->audio_path : '',
            ];
        } catch (dml_exception $e) {
            // Log the error and return null.
            debugging('Error fetching sura: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return null;
        }
    }
}