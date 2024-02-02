<?php
error_reporting(1);
// Register an autoloader function
spl_autoload_register(function ($class) {
    // Define the base directory for your classes
    $baseDir = __DIR__ . '/../';

    // Replace backslashes with forward slashes for compatibility
    $class = str_replace('\\', '/', $class);

    // Combine the base directory with the class namespace and file extension
    $file = $baseDir . $class . '.php';

    // If the class file exists, include it
    if (file_exists($file)) {
        require $file;
    }
});
?>
