<?php
class local_quranmemorizer {
    public static function get_sura($sura_id) {
        // Correct path to quran.json.
        $quran_data_path = __DIR__ . '/../../../quran_data/quran.json';
        if (file_exists($quran_data_path)) {
            $quran_data = json_decode(file_get_contents($quran_data_path), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($quran_data as $sura) {
                    if ($sura['id'] == $sura_id) {
                        // Extract verses text into an array.
                        $verses = array_map(function($verse) {
                            return $verse['text'];
                        }, $sura['verses']);

                        return array(
                            'id' => $sura['id'], // Ensure 'id' is included.
                            'name' => $sura['name'],
                            'ayas' => $verses,
                            'audio_url' => self::get_audio_url($sura['id']),
                        );
                    }
                }
            } else {
                debugging('Error decoding quran.json: ' . json_last_error_msg(), DEBUG_DEVELOPER);
            }
        } else {
            debugging('quran.json file not found at ' . $quran_data_path, DEBUG_DEVELOPER);
        }

        // Fallback data if quran.json is not found or invalid.
        return array(
            'id' => 1, // Ensure 'id' is included.
            'name' => 'الفاتحة',
            'ayas' => array(
                'بِسۡمِ ٱللَّهِ ٱلرَّحۡمَٰنِ ٱلرَّحِيمِ',
                'ٱلۡحَمۡدُ لِلَّهِ رَبِّ ٱلۡعَٰلَمِينَ',
                'ٱلرَّحۡمَٰنِ ٱلرَّحِيمِ',
                'مَٰلِكِ يَوۡمِ ٱلدِّينِ',
                'إِيَّاكَ نَعۡبُدُ وَإِيَّاكَ نَسۡتَعِينُ',
                'ٱهۡدِنَا ٱلصِّرَٰطَ ٱلۡمُسۡتَقِيمَ',
                'صِرَٰطَ ٱلَّذِينَ أَنۡعَمۡتَ عَلَيۡهِمۡ غَيۡرِ ٱلۡمَغۡضُوبِ عَلَيۡهِمۡ وَلَا ٱلضَّآلِّينَ'
            ),
            'audio_url' => self::get_audio_url(1),
        );
    }

    private static function get_audio_url($sura_number) {
        $audio_file = sprintf('%03d.mp3', $sura_number); // Ensure the file name is in the format 001.mp3, 002.mp3, etc.
        $audio_path = __DIR__ . '/../../../audio/Qari1/' . $audio_file;

        if (file_exists($audio_path)) {
            return new moodle_url('/local/quranmemorizer/audio/Qari1/' . $audio_file);
        } else {
            debugging('Audio file not found: ' . $audio_path, DEBUG_DEVELOPER);
            return ''; // Return empty if audio file not found.
        }
    }
}