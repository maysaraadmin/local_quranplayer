<?php
defined('MOODLE_INTERNAL') || die();

class local_quranmemorizer_renderer extends plugin_renderer_base {
    public function render_sura_selection($sura) {
        global $OUTPUT;

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
        $audio_url = $this->get_audio_url($sura['id']); // Use 'id' instead of 'number'.
        if (!empty($audio_url)) {
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

        return $output;
    }

    private function get_audio_url($sura_number) {
        $audio_file = sprintf('%03d.mp3', $sura_number);
        $audio_path = __DIR__ . '/../../audio/Qari1/' . $audio_file;
        if (file_exists($audio_path)) {
            return new moodle_url('/local/quranmemorizer/audio/Qari1/' . $audio_file);
        } else {
            return ''; // Return empty if audio file not found.
        }
    }
}