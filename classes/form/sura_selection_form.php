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
            if (json_last_error() === JSON_ERROR_NONE) {
                $suras = [];
                foreach ($quran_data as $sura) {
                    $suras[$sura['id']] = $sura['name']; // Use 'id' instead of 'number'.
                }
                return $suras;
            }
        }

        // Fallback data if quran.json is not found or invalid.
        return array(
            1 => "الفاتحة",
            2 => "البقرة",
            3 => "آل عمران",
            4 => "النساء",
            5 => "المائدة",
            6 => "الأنعام",
            7 => "الأعراف",
            8 => "الأنفال",
            9 => "التوبة",
            10 => "يونس",
            11 => "هود",
            12 => "يوسف",
            13 => "الرعد",
            14 => "ابراهيم",
            15 => "الحجر",
            16 => "النحل",
            17 => "الإسراء",
            18 => "الكهف",
            19 => "مريم",
            20 => "طه",
            21 => "الأنبياء",
            22 => "الحج",
            23 => "المؤمنون",
            24 => "النور",
            25 => "الفرقان",
            26 => "الشعراء",
            27 => "النمل",
            28 => "القصص",
            29 => "العنكبوت",
            30 => "الروم",
            31 => "لقمان",
            32 => "السجدة",
            33 => "الأحزاب",
            34 => "سبإ",
            35 => "فاطر",
            36 => "يس",
            37 => "الصافات",
            38 => "ص",
            39 => "الزمر",
            40 => "غافر",
            41 => "فصلت",
            42 => "الشورى",
            43 => "الزخرف",
            44 => "الدخان",
            45 => "الجاثية",
            46 => "الأحقاف",
            47 => "محمد",
            48 => "الفتح",
            49 => "الحجرات",
            50 => "ق",
            51 => "الذاريات",
            52 => "الطور",
            53 => "النجم",
            54 => "القمر",
            55 => "الرحمن",
            56 => "الواقعة",
            57 => "الحديد",
            58 => "المجادلة",
            59 => "الحشر",
            60 => "الممتحنة",
            61 => "الصف",
            62 => "الجمعة",
            63 => "المنافقون",
            64 => "التغابن",
            65 => "الطلاق",
            66 => "التحريم",
            67 => "الملك",
            68 => "القلم",
            69 => "الحاقة",
            70 => "المعارج",
            71 => "نوح",
            72 => "الجن",
            73 => "المزمل",
            74 => "المدثر",
            75 => "القيامة",
            76 => "الانسان",
            77 => "المرسلات",
            78 => "النبإ",
            79 => "النازعات",
            80 => "عبس",
            81 => "التكوير",
            82 => "الإنفطار",
            83 => "المطففين",
            84 => "الإنشقاق",
            85 => "البروج",
            86 => "الطارق",
            87 => "الأعلى",
            88 => "الغاشية",
            89 => "الفجر",
            90 => "البلد",
            91 => "الشمس",
            92 => "الليل",
            93 => "الضحى",
            94 => "الشرح",
            95 => "التين",
            96 => "العلق",
            97 => "القدر",
            98 => "البينة",
            99 => "الزلزلة",
            100 => "العاديات",
            101 => "القارعة",
            102 => "التكاثر",
            103 => "العصر",
            104 => "الهمزة",
            105 => "الفيل",
            106 => "قريش",
            107 => "الماعون",
            108 => "الكوثر",
            109 => "الكافرون",
            110 => "النصر",
            111 => "المسد",
            112 => "الإخلاص",
            113 => "الفلق",
            114 => "الناس",
        );
    }
}