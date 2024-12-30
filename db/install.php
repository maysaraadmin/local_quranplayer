<?php
function xmldb_quran_install() {
    global $DB;
    require_once(__DIR__ . '/../lib.php');
    quran_load_verses_to_db();
    return true;
}