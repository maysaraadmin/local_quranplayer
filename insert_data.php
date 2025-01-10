<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Set up the admin page.
admin_externalpage_setup('local_quranmemorizer_insert_data');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('insertdata', 'local_quranmemorizer'));

// Define the path to quran.json.
$quran_data_path = __DIR__ . '/../quran_data/quran.json';

// Check if the file exists.
if (!file_exists($quran_data_path)) {
    echo $OUTPUT->notification(get_string('quranjsonnotfound', 'local_quranmemorizer'), 'notifyerror');
    echo $OUTPUT->footer();
    die();
}

// Load and decode the JSON file.
$quran_data = json_decode(file_get_contents($quran_data_path), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo $OUTPUT->notification(get_string('quranjsondecodeerror', 'local_quranmemorizer') . ': ' . json_last_error_msg(), 'notifyerror');
    echo $OUTPUT->footer();
    die();
}

// Validate JSON structure.
if (!is_array($quran_data)) {
    echo $OUTPUT->notification(get_string('quranjsondecodeerror', 'local_quranmemorizer') . ': Invalid JSON structure.', 'notifyerror');
    echo $OUTPUT->footer();
    die();
}

// Add a button to insert data.
echo html_writer::tag('button', get_string('insertdata', 'local_quranmemorizer'), [
    'onclick' => 'insertData()',
    'style' => 'margin-bottom: 20px;'
]);

echo html_writer::tag('div', '', ['id' => 'insert-data-result']);

// JavaScript function to handle the data insertion via AJAX.
echo "
<script>
function insertData() {
    var resultDiv = document.getElementById('insert-data-result');
    resultDiv.innerHTML = 'Inserting data...';

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'insert_data_ajax.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            resultDiv.innerHTML = xhr.responseText;
        } else {
            resultDiv.innerHTML = 'Error: ' + xhr.statusText;
        }
    };
    xhr.onerror = function() {
        resultDiv.innerHTML = 'Request failed';
    };
    xhr.send();
}
</script>
";

echo $OUTPUT->footer();