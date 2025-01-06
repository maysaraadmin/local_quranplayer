<?php
class local_quranmemorizer {
    public static function get_sura($sura_id) {
        // Load Sura data from quran.json.
        $quran_data_path = __DIR__ . '/../../quran_data/quran.json';
        if (file_exists($quran_data_path)) {
            $quran_data = json_decode(file_get_contents($quran_data_path), true);
            foreach ($quran_data as $sura) {
                if ($sura['number'] == $sura_id) {
                    return array(
                        'name' => $sura['name'],
                        'ayas' => $sura['verses'],
                        'audio_url' => self::get_audio_url($sura_id),
                    );
                }
            }
        }

        // Fallback data if quran.json is not found.
        return array(
            'name' => 'الفاتحة',
            'ayas' => array(
                'بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ',
                'ٱلْحَمْدُ لِلَّهِ رَبِّ ٱلْعَٰلَمِينَ',
                // Add all Ayas here.
            ),
            'audio_url' => self::get_audio_url(1),
        );
    }

    private static function get_audio_url($sura_number) {
        $audio_file = sprintf('%03d.mp3', $sura_number);
        $audio_path = __DIR__ . '/../../audio/Qari1/' . $audio_file;
        if (file_exists($audio_path)) {
            return new moodle_url('/local/quranmemorizer/audio/Qari1/' . $audio_file);
        } else {
            return ''; // Return empty if audio file not found.
        }
    }
}