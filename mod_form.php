<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_quran_mod_form extends moodleform_mod {
    public function definition() {
        $mform = $this->_form;

        // Add general module settings.
        $this->standard_coursemodule_elements();

        // Add Quran-specific settings.
        $mform->addElement('header', 'quransettings', get_string('quransettings', 'mod_quran'));

        // Example field: Memorization target chapter.
        $mform->addElement('text', 'targetchapter', get_string('targetchapter', 'mod_quran'), array('size' => '4'));
        $mform->setType('targetchapter', PARAM_INT);
        $mform->addRule('targetchapter', null, 'required', null, 'client');

        // Example field: Memorization target verse.
        $mform->addElement('text', 'targetverse', get_string('targetverse', 'mod_quran'), array('size' => '4'));
        $mform->setType('targetverse', PARAM_INT);
        $mform->addRule('targetverse', null, 'required', null, 'client');

        // Standard grading and completion settings.
        $this->add_intro_editor();
        $this->standard_grading_coursemodule_elements();
        $this->standard_hidden_coursemodule_elements();

        // Add action buttons.
        $this->add_action_buttons();
    }
}
