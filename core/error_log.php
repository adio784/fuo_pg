<?php

$errorLogPath = '../app/config/error.log';

if (!file_exists($errorLogPath)) {
    $fileHandle = fopen($errorLogPath, 'w') or die('Cannot open file: ' . $errorLogPath);
    fclose($fileHandle);
}

// Set up error handling
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', $errorLogPath);

// Define a custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $error_message = "Error: [$errno] $errstr in $errfile on line $errline";
    
    // Log the error to a file
    error_log($error_message, 3, $GLOBALS['errorLogPath']);

    return true;
}

set_error_handler("customErrorHandler");

echo $undefined_variable;
?>
