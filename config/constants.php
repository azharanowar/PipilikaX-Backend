<?php
/**
 * Constants Configuration
 * PipilikaX Backend
 */

// Debug Mode - Set to false for production
define('DEBUG_MODE', false);

// Site URLs
define('SITE_URL', 'http://localhost/PipilikaX-Backend');
define('ADMIN_URL', SITE_URL . '/admin');
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOAD_URL', SITE_URL . '/uploads');

// File Paths
define('ROOT_PATH', __DIR__ . '/..');
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
define('INCLUDES_PATH', ROOT_PATH . '/includes/');

// Production error handling (must be after ROOT_PATH is defined)
if (!DEBUG_MODE) {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . '/logs/error.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Pagination
define('POSTS_PER_PAGE', 9);
define('ADMIN_POSTS_PER_PAGE', 20);

// Security
define('SESSION_LIFETIME', 3600); // 1 hour
define('CSRF_TOKEN_NAME', 'csrf_token');

// File Upload Limits
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

