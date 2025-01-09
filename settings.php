<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_quranmemorizer', get_string('pluginname', 'local_quranmemorizer'));
    $ADMIN->add('localplugins', $settings);

    // Add a link to the data insertion page in the admin menu.
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_quranmemorizer_insert_data',
        get_string('insertdata', 'local_quranmemorizer'),
        new moodle_url('/local/quranmemorizer/insert_data.php')
    ));
}