<?php
require_once("$CFG->libdir/formslib.php");

class sura_selection_form extends moodleform {
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // Sura selection dropdown.
        $suras = $this->get_suras();
        $mform->addElement('select', 'sura', get_string('sura', 'local_quranmemorizer'), $suras);
        $mform->setDefault('sura', 1); // Default to Sura 1 (Al-Fatiha).

        // Submit button.
        $mform->addElement('submit', 'submitbutton', get_string('startmemorization', 'local_quranmemorizer'));
    }

    private function get_suras() {
        global $DB;

        // Fetch Sura names from the database.
        $suras = $DB->get_records('local_quranmemorizer_suras', [], 'id ASC', 'id, name');
        $sura_list = [];
        foreach ($suras as $sura) {
            $sura_list[$sura->id] = $sura->name;
        }

        return $sura_list;
    }
}