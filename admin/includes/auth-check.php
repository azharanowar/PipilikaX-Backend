<?php
/**
 * Admin Authentication Check
 * Include this file at the top of every protected admin page
 */

// Check if user is logged in
if (!isLoggedIn()) {
    redirect(ADMIN_URL . '/login.php');
    exit;
}

// Check session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
    session_unset();
    session_destroy();
    redirect(ADMIN_URL . '/login.php?timeout=1');
    exit;
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Set user info for easy access
$current_user = [
    'id' => $_SESSION['user_id'],
    'username' => $_SESSION['username'],
    'full_name' => $_SESSION['full_name'],
    'role' => $_SESSION['user_role']
];
