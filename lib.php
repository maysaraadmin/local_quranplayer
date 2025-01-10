<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_quranmemorizer_install() {
    global $DB;

    // Ensure the tables are created.
    $dbman = $DB->get_manager();

    // Define and create tables.
    $tables = [
        [
            'name' => 'local_quranmemorizer_suras',
            'fields' => [
                ['id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE],
                ['name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL],
            ],
            'primary' => ['id']
        ],
        [
            'name' => 'local_quranmemorizer_ayas',
            'fields' => [
                ['id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE],
                ['sura_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL],
                ['text', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL],
            ],
            'keys' => ['sura_id']
        ],
        [
            'name' => 'local_quranmemorizer_audio',
            'fields' => [
                ['id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE],
                ['sura_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL],
                ['audio_path', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL],
            ],
            'keys' => ['sura_id']
        ]
    ];

    return true;
}