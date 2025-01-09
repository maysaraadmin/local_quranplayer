<?php
defined('MOODLE_INTERNAL') || die();

class local_quranmemorizer_renderer extends plugin_renderer_base {
    public function render_sura_selection($sura) {
        global $OUTPUT;

        if (empty($sura)) {
            return $OUTPUT->notification(get_string('nosuraselected', 'local_quranmemorizer'), 'notifyerror');
        }

        $output = '';
        $output .= $OUTPUT->heading(get_string('selectsura', 'local_quranmemorizer'), 2);
        $output .= $this->render_sura_details($sura);
        $output .= $this->render_audio_player($sura);

        return $output;
    }

    private function render_sura_details($sura) {
        $output = '';
        $output .= html_writer::tag('h3', get_string('suraname', 'local_quranmemorizer') . ': ' . s($sura['name']));
        $output .= html_writer::tag('p', get_string('ayas', 'local_quranmemorizer') . ': ' . count($sura['ayas']));

        // Display Ayas.
        foreach ($sura['ayas'] as $aya) {
            $output .= html_writer::tag('p', s($aya));
        }

        return $output;
    }

    private function render_audio_player($sura) {
        $output = '';
        if (isset($sura['id']) && isset($sura['audio_url'])) { // Ensure 'id' and 'audio_url' keys exist.
            $audio_url = $sura['audio_url'];
            if (!empty($audio_url)) {
                // Display the audio file name for debugging.
                $audio_file_name = sprintf('%03d.mp3', $sura['id']);
                $output .= html_writer::tag('p', 'Audio file: ' . $audio_file_name);

                // Render the audio player.
                $output .= html_writer::tag('audio', '', [
                    'id' => 'quran-audio',
                    'controls' => 'controls',
                    'src' => $audio_url,
                ]);
                $output .= html_writer::tag('button', get_string('playaudio', 'local_quranmemorizer'), [
                    'onclick' => 'document.getElementById("quran-audio").play()',
                ]);
                $output .= html_writer::tag('button', get_string('stopaudio', 'local_quranmemorizer'), [
                    'onclick' => 'document.getElementById("quran-audio").pause()',
                ]);
            } else {
                $output .= html_writer::tag('p', get_string('noaudioavailable', 'local_quranmemorizer'));
            }
        } else {
            $output .= html_writer::tag('p', 'Sura ID or audio URL is missing.');
        }

        return $output;
    }
}