<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_quranplayer', get_string('pluginname', 'local_quranplayer'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configtext(
        'local_quranplayer/mp3path',
        get_string('mp3path', 'local_quranplayer'),
        get_string('mp3path_desc', 'local_quranplayer'),
        $CFG->dirroot . '/local/quranplayer/mp3/',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configcheckbox(
        'local_quranplayer/enablelogging',
        get_string('enablelogging', 'local_quranplayer'),
        get_string('enablelogging_desc', 'local_quranplayer'),
        0
    ));
}