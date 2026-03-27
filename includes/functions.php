<?php
/**
 * Sanitizes input data to prevent XSS and generic injections.
 *
 * @param string $data The user input to sanitize.
 * @return string The sanitized string.
 */
function sanitize_input($data) {
    if ($data === null) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}
?>
