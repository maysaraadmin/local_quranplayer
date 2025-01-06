<?php
require_once("$CFG->libdir/formslib.php");

class sura_selection_form extends moodleform {
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // Sura selection dropdown.
        $suras = $this->get_suras();
        $mform->addElement('select', 'sura', get_string('sura', 'local_quranmemorizer'), $suras);
        $mform->setDefault('sura', 1);

        // Submit button.
        $mform->addElement('submit', 'submitbutton', get_string('startmemorization', 'local_quranmemorizer'));
    }

    private function get_suras() {
        // Load Sura names from quran.json.
        $quran_data_path = __DIR__ . '/../../../quran_data/quran.json';
        if (file_exists($quran_data_path)) {
            $quran_data = json_decode(file_get_contents($quran_data_path), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $suras = [];
                foreach ($quran_data as $sura) {
                    $suras[$sura['id']] = $sura['name']; // Use 'id' instead of 'number'.
                }
                return $suras;
            } else {
                debugging('Error decoding quran.json: ' . json_last_error_msg(), DEBUG_DEVELOPER);
            }
        } else {
            debugging('quran.json file not found at ' . $quran_data_path, DEBUG_DEVELOPER);
        }

        // Fallback data if quran.json is not found or invalid.
        return array(
            1 => "الفاتحة",
            2 => "البقرة",
            // ... (rest of the Sura names)
            114 => "الناس",
        );
    }
}