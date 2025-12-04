<?php
/**
 * Admin Logout Handler
 * PipilikaX Admin Panel
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Log activity before destroying session
if (isLoggedIn()) {
    logActivity('logout', 'user', $_SESSION['user_id'], 'User logged out');
}

// Destroy session
session_unset();
session_destroy();

// Delete session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to login page
redirect(ADMIN_URL . '/login.php');
