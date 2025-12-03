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
 * Upload image
 */
function uploadImage($file, $folder = 'blog')
{
    $allowed = ALLOWED_IMAGE_TYPES;
    $filename = $file['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return ['success' => false, 'message' => 'Invalid file type. Allowed: ' . implode(', ', $allowed)];
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File too large. Maximum size: ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB'];
    }

    $newname = uniqid() . '_' . time() . '.' . $ext;
    $destination = UPLOAD_PATH . $folder . '/' . $newname;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $newname];
    }

    return ['success' => false, 'message' => 'Upload failed'];
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
