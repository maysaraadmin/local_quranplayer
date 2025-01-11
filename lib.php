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

class local_quranplayer {

    public static function render_player() {
        global $CFG, $OUTPUT;

        $mp3path = get_config('local_quranplayer', 'mp3path');
        if (empty($mp3path) || !is_dir($mp3path)) {
            return '<div class="alert alert-error">' . get_string('nodirectory', 'local_quranplayer') . '</div>';
        }

        $files = array_diff(scandir($mp3path), ['..', '.']);
        if (empty($files)) {
            return '<div class="alert alert-warning">' . get_string('noaudiofiles', 'local_quranplayer') . '</div>';
        }

        $quranChapters = [
            "الفاتحة", "البقرة", "آل عمران", "النساء", "المائدة", "الأنعام", "الأعراف", "الأنفال", "التوبة", "يونس",
            "هود", "يوسف", "الرعد", "ابراهيم", "الحجر", "النحل", "الإسراء", "الكهف", "مريم", "طه",
            "الأنبياء", "الحج", "المؤمنون", "النور", "الفرقان", "الشعراء", "النمل", "القصص", "العنكبوت", "الروم",
            "لقمان", "السجدة", "الأحزاب", "سبإ", "فاطر", "يس", "الصافات", "ص", "الزمر", "غافر",
            "فصلت", "الشورى", "الزخرف", "الدخان", "الجاثية", "الأحقاف", "محمد", "الفتح", "الحجرات", "ق",
            "الذاريات", "الطور", "النجم", "القمر", "الرحمن", "الواقعة", "الحديد", "المجادلة", "الحشر", "الممتحنة",
            "الصف", "الجمعة", "المنافقون", "التغابن", "الطلاق", "التحريم", "الملك", "القلم", "الحاقة", "المعارج",
            "نوح", "الجن", "المزمل", "المدثر", "القيامة", "الانسان", "المرسلات", "النبإ", "النازعات", "عبس",
            "التكوير", "الإنفطار", "المطففين", "الإنشقاق", "البروج", "الطارق", "الأعلى", "الغاشية", "الفجر", "البلد",
            "الشمس", "الليل", "الضحى", "الشرح", "التين", "العلق", "القدر", "البينة", "الزلزلة", "العاديات",
            "القارعة", "التكاثر", "العصر", "الهمزة", "الفيل", "قريش", "الماعون", "الكوثر", "الكافرون", "النصر",
            "المسد", "الإخلاص", "الفلق", "الناس"
        ];

        $options = [];
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'mp3') {
                $surahNumber = intval(pathinfo($file, PATHINFO_FILENAME));
                if ($surahNumber >= 1 && $surahNumber <= 114) {
                    $surahName = $quranChapters[$surahNumber - 1];
                    $options[] = ['value' => $file, 'text' => "$surahNumber. $surahName"];
                }
            }
        }

        $templatecontext = [
            'selectfile' => get_string('selectfile', 'local_quranplayer'),
            'qurantext' => get_string('qurantext', 'local_quranplayer'),
            'noaudio' => get_string('noaudio', 'local_quranplayer'),
            'options' => $options
        ];

        return $OUTPUT->render_from_template('local_quranplayer/quranplayer', $templatecontext);
    }
}