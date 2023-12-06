<?php

class Sanitizer {
    
    public static function sanitizeInput($input) {
        // Remove leading and trailing whitespaces
        $input = trim($input);

        // Remove backslashes
        $input = stripslashes($input);

        // Convert special characters to HTML entities
        $input = htmlspecialchars($input);

        return $input;
    }
}

?>