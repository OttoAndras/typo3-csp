<?php

// Start configure
//TYPO3 v7
if(is_dir( dirname(__FILE__)  . '/../../../../../typo3temp/logs/')) {
    $log_file = dirname(__FILE__)  . '/../../../../../typo3temp/logs/csp-violations.log';
    //TYPO3 v8
} else if(is_dir( dirname(__FILE__)  . '/../../../../../typo3temp/var/logs/')) {
    $log_file = dirname(__FILE__)  . '/../../../../../typo3temp/var/logs/csp-violations.log';
} else {
    exit(0);
}

$log_file_size_limit = 1000000; // bytes - once exceeded no further entries are added
// End configuration

http_response_code(204); // HTTP 204 No Content

$json_data = file_get_contents('php://input');

// We pretty print the JSON before adding it to the log file
if ($json_data = json_decode($json_data)) {
    $json_data = json_encode($json_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    if (file_exists($log_file)
        && filesize($log_file) > $log_file_size_limit) {
        exit(0);
    }

    file_put_contents($log_file, $json_data, FILE_APPEND | LOCK_EX);
}