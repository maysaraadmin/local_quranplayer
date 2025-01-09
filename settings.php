<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Create a new settings page for the plugin.
    $settings = new admin_settingpage('local_quranmemorizer', get_string('pluginname', 'local_quranmemorizer'));

    // Add the settings page to the "Local plugins" category in the admin menu.
    $ADMIN->add('localplugins', $settings);

    // Add a setting to enable/disable the plugin (optional).
    $settings->add(new admin_setting_configcheckbox(
        'local_quranmemorizer/enabled',
        get_string('enableplugin', 'local_quranmemorizer'),
        get_string('enableplugin_desc', 'local_quranmemorizer'),
        1 // Default value (1 = enabled, 0 = disabled).
    ));

    // Add a setting for the path to the Quran JSON file.
    $settings->add(new admin_setting_configtext(
        'local_quranmemorizer/quranjsonpath',
        get_string('quranjsonpath', 'local_quranmemorizer'),
        get_string('quranjsonpath_desc', 'local_quranmemorizer'),
        '/local/quranmemorizer/quran_data/quran.json', // Default path.
        PARAM_TEXT // Parameter type.
    ));

    // Add a link to the data insertion page in the admin menu.
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_quranmemorizer_insert_data',
        get_string('insertdata', 'local_quranmemorizer'),
        new moodle_url('/local/quranmemorizer/insert_data.php')
    ));
}