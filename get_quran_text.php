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
defined('MOODLE_INTERNAL') || die();

$file = optional_param('file', '', PARAM_TEXT);
if (empty($file) || !preg_match('/^\d{3}\.mp3$/', $file)) {
    echo get_string('noqurantext', 'local_quranplayer');
    exit;
}

$quranfile = __DIR__ . '/quran.txt';

if (!file_exists($quranfile)) {
    echo get_string('noqurantext', 'local_quranplayer');
    exit;
}

// Extract surah number from the file name (e.g., 001.mp3 -> 1)
$surahNumber = intval(pathinfo($file, PATHINFO_FILENAME));

$qurantext = file_get_contents($quranfile);
$lines = explode("\n", $qurantext);

$selectedText = '';
foreach ($lines as $line) {
    // Each line is in the format "surah|verse|text"
    list($lineSurah, $lineVerse, $text) = explode('|', $line, 3);
    if ($lineSurah == $surahNumber) {
        $selectedText .= "$lineVerse. $text\n";
    }
}

if (empty($selectedText)) {
    echo get_string('noqurantext', 'local_quranplayer');
} else {
    echo $selectedText;
}