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
$site_logo = getSetting('site_logo', 'pipilika-logo.png');
$site_favicon = getSetting('site_favicon', 'pipilika-favicon.png');
$theme_color = '#E90330';
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

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon"
        href="<?php echo ASSETS_URL; ?>/images/<?php echo htmlspecialchars($site_favicon); ?>">

    <!-- Theme Color -->
    <meta name="theme-color" content="<?php echo htmlspecialchars($theme_color); ?>" />

    <!-- Stylesheets -->
    <link href="<?php echo ASSETS_URL; ?>/css/styles.css" rel="stylesheet" />

    <?php if (isset($additional_css)): ?>
        <?php echo $additional_css; ?>
    <?php endif; ?>
</head>

<body>