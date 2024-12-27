<?php

function quran_add_instance($data) {
    global $DB;
    $data->timecreated = time();
    return $DB->insert_record('quran', $data);
}

function quran_update_instance($data) {
    global $DB;
    $data->timemodified = time();
    return $DB->update_record('quran', $data);
}

function quran_delete_instance($id) {
    global $DB;
    return $DB->delete_records('quran', ['id' => $id]);
}

function quran_save_progress($userid, $chapter, $verse, $memorized) {
    global $DB;
    $record = $DB->get_record('quran_progress', ['userid' => $userid, 'chapter' => $chapter, 'verse' => $verse]);
    if ($record) {
        $record->memorized = $memorized;
        return $DB->update_record('quran_progress', $record);
    } else {
        $data = new stdClass();
        $data->userid = $userid;
        $data->chapter = $chapter;
        $data->verse = $verse;
        $data->memorized = $memorized;
        return $DB->insert_record('quran_progress', $data);
    }
}

function quran_get_progress($userid) {
    global $DB;
    return $DB->get_records('quran_progress', ['userid' => $userid]);
}