<?php
/**
 * Helper functions for the SPK Blewah application
 */

/**
 * Clean data to prevent XSS
 */
function e($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in
 */
function check_login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Redirect to a page
 */
function redirect($path) {
    header("Location: $path");
    exit();
}
?>
