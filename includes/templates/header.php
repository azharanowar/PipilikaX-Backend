<?php
/**
 * Header Template
 * PipilikaX Frontend
 */

// Load constants first (required by session.php)
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../functions.php';

// Get site settings
$site_name = getSetting('site_name', 'PipilikaX');
$site_favicon = getSetting('site_favicon', '');
$theme_color = '#E90330';

// Determine favicon URL - check uploads first, then assets
$favicon_url = null;
if ($site_favicon) {
    if (file_exists(UPLOAD_PATH . 'settings/' . $site_favicon)) {
        $favicon_url = UPLOAD_URL . '/settings/' . $site_favicon;
    } elseif (file_exists(ROOT_PATH . '/assets/images/' . $site_favicon)) {
        $favicon_url = ASSETS_URL . '/images/' . $site_favicon;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>
        <?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' . htmlspecialchars($site_name) : htmlspecialchars($site_name) . ' - Your Space for AutoNotes'; ?>
    </title>

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Favicon -->
    <?php if ($favicon_url): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars($favicon_url); ?>">
    <?php endif; ?>

    <!-- Theme Color -->
    <meta name="theme-color" content="<?php echo htmlspecialchars($theme_color); ?>" />

    <!-- Stylesheets -->
    <link href="<?php echo ASSETS_URL; ?>/css/styles.css" rel="stylesheet" />

    <?php if (isset($additional_css)): ?>
        <?php echo $additional_css; ?>
    <?php endif; ?>
</head>

<body>