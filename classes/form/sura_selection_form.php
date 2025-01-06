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
        $quran_data_path = __DIR__ . '/../../quran_data/quran.json';
        if (file_exists($quran_data_path)) {
            $quran_data = json_decode(file_get_contents($quran_data_path), true);
            $suras = [];
            foreach ($quran_data as $sura) {
                $suras[$sura['number']] = $sura['name'];
            }
            return $suras;
        } else {
            return array(
                1 => 'الفاتحة',
                2 => 'البقرة',
                3 => 'آل عمران',
                // Add all Suras here as fallback.
            );
        }
    }
}