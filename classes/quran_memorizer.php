<?php
class local_quranmemorizer {
    public static function get_sura($sura_id) {
        // Load Sura data from quran.json.
        $quran_data_path = __DIR__ . '/../../quran_data/quran.json';
        if (file_exists($quran_data_path)) {
            $quran_data = json_decode(file_get_contents($quran_data_path), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($quran_data as $sura) {
                    if ($sura['id'] == $sura_id) { // Use 'id' instead of 'number' as per your JSON structure.
                        // Extract verses text into an array.
                        $verses = array_map(function($verse) {
                            return $verse['text'];
                        }, $sura['verses']);

                        return array(
                            'name' => $sura['name'],
                            'ayas' => $verses, // Use the extracted verses.
                            'audio_url' => self::get_audio_url($sura_id),
                        );
                    }
                }
            }
        }

        // Fallback data if quran.json is not found or invalid.
        return array(
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
        $audio_url = 'http://127.0.0.1/dev/local/quranmemorizer/audio/Qari1/' . $audio_file;

        // Check if the audio file exists (optional, if you want to validate the URL).
        // Note: This is a basic check and may not work for remote URLs.
        $headers = @get_headers($audio_url);
        if ($headers && strpos($headers[0], '200') !== false) {
            return $audio_url;
        } else {
            return ''; // Return empty if audio file not found.
        }
    }
}