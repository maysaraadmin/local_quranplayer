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

require_once('../../config.php');
require_once($CFG->dirroot . '/local/quranplayer/lib.php');

defined('MOODLE_INTERNAL') || die();

require_login();
if (!has_capability('local/quranplayer:view', context_system::instance())) {
    throw new moodle_exception('nopermissiontoviewpage', 'error');
}

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/quranplayer/index.php'));
$PAGE->set_title(get_string('pluginname', 'local_quranplayer'));
$PAGE->set_heading(get_string('pluginname', 'local_quranplayer'));

echo $OUTPUT->header();
echo local_quranplayer::render_player();
echo $OUTPUT->footer();