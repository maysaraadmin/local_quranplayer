<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_quranmemorizer', get_string('pluginname', 'local_quranmemorizer'));
    $ADMIN->add('localplugins', $settings);
}