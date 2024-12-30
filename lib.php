<?php

function quran_add_instance($data) {
    global $DB;
    $data->timecreated = time();
    try {
        return $DB->insert_record('quran', $data);
    } catch (dml_exception $e) {
        debugging('Failed to add Quran instance: ' . $e->getMessage(), DEBUG_DEVELOPER);
        return false;
    }
}

function quran_update_instance($data) {
    global $DB;
    $data->timemodified = time();
    try {
        return $DB->update_record('quran', $data);
    } catch (dml_exception $e) {
        debugging('Failed to update Quran instance: ' . $e->getMessage(), DEBUG_DEVELOPER);
        return false;
    }
}

function quran_delete_instance($id) {
    global $DB;
    try {
        return $DB->delete_records('quran', ['id' => $id]);
    } catch (dml_exception $e) {
        debugging('Failed to delete Quran instance: ' . $e->getMessage(), DEBUG_DEVELOPER);
        return false;
    }
}

function quran_save_progress($userid, $chapter, $verse, $memorized) {
    global $DB;
    try {
        $record = $DB->get_record('quran_progress', ['userid' => $userid, 'chapter' => $chapter, 'verse' => $verse]);
        if ($record) {
            $record->memorized = $memorized;
            $record->timemodified = time();
            return $DB->update_record('quran_progress', $record);
        } else {
            $data = new stdClass();
            $data->userid = $userid;
            $data->chapter = $chapter;
            $data->verse = $verse;
            $data->memorized = $memorized;
            $data->timemodified = time();
            return $DB->insert_record('quran_progress', $data);
        }
    } catch (dml_exception $e) {
        debugging('Failed to save Quran progress: ' . $e->getMessage(), DEBUG_DEVELOPER);
        return false;
    }
}

function quran_get_progress($userid) {
    global $DB;
    try {
        return $DB->get_records('quran_progress', ['userid' => $userid]);
    } catch (dml_exception $e) {
        debugging('Failed to fetch Quran progress: ' . $e->getMessage(), DEBUG_DEVELOPER);
        return [];
    }
}

function quran_load_verses() {
    $file = __DIR__ . '/quran-uthmani.txt';
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    // Parse the content based on the file's structure
    // Example parsing logic (adjust according to actual file format)
    $verses = [];
    $lines = explode("\n", $content);
    $chapter = 1;
    $verse = 1;
    foreach ($lines as $line) {
        // Skip any header lines or chapter separators
        if (strpos($line, 'Chapter') === 0) {
            // Increment chapter and reset verse
            $chapter++;
            $verse = 1;
            continue;
        }
        // Skip empty lines
        if (trim($line) == '') {
            continue;
        }
        // Assign verse text to the array
        $verses[$chapter][$verse] = trim($line);
        $verse++;
    }
    return $verses;
}

function quran_load_verses_to_db() {
    global $DB;

    // Check if the table is already populated
    if ($DB->count_records('quran_verses') > 0) {
        return true; // Verses are already loaded
    }

    $file = __DIR__ . '/quran-uthmani.txt';
    if (!file_exists($file)) {
        throw new moodle_exception('filenotfound', 'mod_quran', '', $file);
    }

    $content = file_get_contents($file);
    $lines = explode("\n", $content);

    $chapter = 1;
    $verse = 1;

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) {
            continue; // Skip empty lines
        }

        // Insert the verse into the database
        $record = new stdClass();
        $record->chapter = $chapter;
        $record->verse = $verse;
        $record->text = $line;

        try {
            $DB->insert_record('quran_verses', $record);
        } catch (dml_exception $e) {
            debugging('Failed to insert verse: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return false;
        }

        $verse++;
    }

    return true;
}

function quran_get_verses($chapter = null, $verse = null) {
    global $DB;

    $params = [];
    $sql = "SELECT * FROM {quran_verses}";

    if ($chapter !== null && $verse !== null) {
        $sql .= " WHERE chapter = :chapter AND verse = :verse";
        $params['chapter'] = $chapter;
        $params['verse'] = $verse;
    } elseif ($chapter !== null) {
        $sql .= " WHERE chapter = :chapter";
        $params['chapter'] = $chapter;
    }

    try {
        if ($chapter !== null && $verse !== null) {
            return $DB->get_record_sql($sql, $params);
        } else {
            return $DB->get_records_sql($sql, $params);
        }
    } catch (dml_exception $e) {
        debugging('Failed to fetch verses: ' . $e->getMessage(), DEBUG_DEVELOPER);
        return [];
    }
}