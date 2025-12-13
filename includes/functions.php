<?php
/**
 * Helper Functions
 * PipilikaX Backend
 */

/**
 * Sanitize user input
 */
function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to another page
 */
function redirect($url)
{
    header("Location: $url");
    exit();
}

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get user role
 */
function getUserRole()
{
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}

/**
 * Check if user has permission
 */
function hasPermission($required_role, $action = null)
{
    if (!isLoggedIn()) {
        return false;
    }

    $current_role = $_SESSION['user_role'];
    $user_id = $_SESSION['user_id'];

    // Role hierarchy
    $roles = [
        'admin' => 4,
        'editor' => 3,
        'author' => 2,
        'subscriber' => 1
    ];

    // Admin can do everything
    if ($current_role === 'admin') {
        return true;
    }

    // Check basic role level
    if ($roles[$current_role] >= $roles[$required_role]) {
        return true;
    }

    return false;
}

/**
 * Check if user can edit a specific post
 */
function canEditPost($post_author_id)
{
    if (!isLoggedIn()) {
        return false;
    }

    $role = $_SESSION['user_role'];
    $user_id = $_SESSION['user_id'];

    // Admin and Editor can edit any post
    if (in_array($role, ['admin', 'editor'])) {
        return true;
    }

    // Author can only edit their own posts
    if ($role === 'author' && $post_author_id == $user_id) {
        return true;
    }

    return false;
}

/**
 * Generate CSRF token
 */
function generateCSRFToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Format date
 */
function formatDate($date, $format = 'F j, Y')
{
    return date($format, strtotime($date));
}

/**
 * Create slug from title
 */
function createSlug($text)
{
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

/**
 * Validate if a slug is URL-friendly
 * Only allows lowercase letters (a-z), numbers (0-9), and hyphens (-)
 * Returns true if valid, false otherwise
 */
function isValidSlug($slug)
{
    // Empty slugs will be auto-generated, so they're valid
    if (empty($slug)) {
        return true;
    }

    // Check if slug only contains allowed characters: a-z, 0-9, and -
    // Also ensure it doesn't start or end with a hyphen
    return preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/', $slug) === 1;
}

/**
 * Upload image with security validation
 * Validates both extension AND actual file content (MIME type)
 */
function uploadImage($file, $folder = 'blog')
{
    $allowed_extensions = ALLOWED_IMAGE_TYPES;
    $allowed_mimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp'
    ];

    $filename = $file['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Check extension first
    if (!in_array($ext, $allowed_extensions)) {
        return ['success' => false, 'message' => 'Invalid file type. Allowed: ' . implode(', ', $allowed_extensions)];
    }

    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File too large. Maximum size: ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB'];
    }

    // Validate actual file content using getimagesize()
    // This checks if the file is a real image, not just a renamed file
    $image_info = @getimagesize($file['tmp_name']);
    if ($image_info === false) {
        return ['success' => false, 'message' => 'Invalid image file. The file content does not match a valid image format.'];
    }

    // Verify MIME type matches what we expect for this extension
    $detected_mime = $image_info['mime'];
    $expected_mime = $allowed_mimes[$ext] ?? null;

    if (!$expected_mime || $detected_mime !== $expected_mime) {
        // Allow jpeg to have either extension
        if (
            !($ext === 'jpg' && $detected_mime === 'image/jpeg') &&
            !($ext === 'jpeg' && $detected_mime === 'image/jpeg')
        ) {
            return ['success' => false, 'message' => 'File content does not match the file extension. Please upload a genuine image file.'];
        }
    }

    // Ensure upload directory exists
    $upload_dir = UPLOAD_PATH . $folder . '/';
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) {
            return ['success' => false, 'message' => 'Could not create upload directory. Please check server permissions.'];
        }
    }

    // Check if directory is writable
    if (!is_writable($upload_dir)) {
        return ['success' => false, 'message' => 'Upload directory is not writable. Please check folder permissions.'];
    }

    $newname = uniqid() . '_' . time() . '.' . $ext;
    $destination = $upload_dir . $newname;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $newname];
    }

    // More detailed error for debugging
    $error_msg = 'Upload failed.';
    if (!file_exists($file['tmp_name'])) {
        $error_msg = 'Temporary file not found. The file may be too large for PHP settings.';
    } elseif (!is_uploaded_file($file['tmp_name'])) {
        $error_msg = 'Invalid upload detected.';
    }

    return ['success' => false, 'message' => $error_msg];
}

/**
 * Truncate text
 */
function truncate($text, $length = 150)
{
    $text = strip_tags($text);
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}

/**
 * Get setting value
 */
function getSetting($key, $default = '')
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();

    return $result ? $result['setting_value'] : $default;
}

/**
 * Log activity
 */
function logActivity($action, $entity_type = null, $entity_id = null, $description = null)
{
    global $pdo;

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $stmt = $pdo->prepare("
        INSERT INTO activity_log (user_id, action, entity_type, entity_id, description, ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([$user_id, $action, $entity_type, $entity_id, $description, $ip, $user_agent]);
}

/**
 * Flash message functions
 */
function setFlash($message, $type = 'success')
{
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

function getFlash()
{
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'];
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}
