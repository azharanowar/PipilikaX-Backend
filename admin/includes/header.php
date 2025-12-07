<?php
/**
 * Admin Header Template
 * PipilikaX Admin Panel
 */

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/auth-check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' : ''; ?>Admin Panel - PipilikaX</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Admin CSS -->
    <link href="<?php echo ADMIN_URL; ?>/assets/css/admin-style.css" rel="stylesheet">

    <?php if (isset($additional_css)): ?>
        <?php echo $additional_css; ?>
    <?php endif; ?>
</head>

<body>
    <div class="admin-container">
        <?php include __DIR__ . '/sidebar.php'; ?>

        <div class="admin-main">
            <header class="admin-header">
                <h1><?php echo isset($page_heading) ? htmlspecialchars($page_heading) : 'Dashboard'; ?></h1>
                <div class="user-menu">
                    <div class="user-info">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($current_user['username'], 0, 1)); ?>
                        </div>
                        <div class="user-details">
                            <span
                                class="user-name"><?php echo htmlspecialchars($current_user['full_name'] ?? $current_user['username']); ?></span>
                            <span class="user-role"><?php echo ucfirst($current_user['role']); ?></span>
                        </div>
                    </div>
                    <a href="<?php echo ADMIN_URL; ?>/logout.php" class="btn btn-sm btn-danger" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </header>

            <main class="admin-content">
                <?php
                // Display flash messages
                $flash = getFlash();
                if ($flash):
                    ?>
                    <div class="alert alert-<?php echo $flash['type']; ?>">
                        <?php echo htmlspecialchars($flash['message']); ?>
                    </div>
                <?php endif; ?>