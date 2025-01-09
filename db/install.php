<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_quranmemorizer_install() {
    global $DB;

    // Ensure the tables are created.
    $dbman = $DB->get_manager();

    // Define table local_quranmemorizer_suras to be created.
    $table = new xmldb_table('local_quranmemorizer_suras');
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Define table local_quranmemorizer_ayas to be created.
    $table = new xmldb_table('local_quranmemorizer_ayas');
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('sura_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('text', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
    $table->add_key('sura_id', XMLDB_KEY_FOREIGN, array('sura_id'), 'local_quranmemorizer_suras', array('id'));
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Define table local_quranmemorizer_audio to be created.
    $table = new xmldb_table('local_quranmemorizer_audio');
    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('sura_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('audio_path', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
    $table->add_key('sura_id', XMLDB_KEY_FOREIGN, array('sura_id'), 'local_quranmemorizer_suras', array('id'));
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    return true;
}