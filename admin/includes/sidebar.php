<?php
/**
 * Admin Sidebar Template
 * PipilikaX Admin Panel
 */

$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
$user_role = $current_user['role'];

// Get unread message count for badge
$unread_messages_count = 0;
if (in_array($user_role, ['admin', 'editor'])) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'");
    $unread_messages_count = $stmt->fetchColumn();
}
?>
<aside class="admin-sidebar">
    <div class="logo">
        <img src="<?php echo ASSETS_URL; ?>/images/pipilika-logo-main-white.png" alt="PipilikaX">
    </div>

    <nav>
        <ul>
            <li>
                <a href="<?php echo ADMIN_URL; ?>/index.php"
                    class="<?php echo ($current_page == 'index.php' && $current_dir == 'admin') ? 'active' : ''; ?>">
                    <i class="fas fa-dashboard"></i> Dashboard
                </a>
            </li>

            <?php if (in_array($user_role, ['admin', 'editor', 'author'])): ?>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/posts/index.php"
                        class="<?php echo $current_dir == 'posts' ? 'active' : ''; ?>">
                        <i class="fas fa-file-alt"></i> Blog Posts
                    </a>
                </li>
            <?php endif; ?>

            <?php if (in_array($user_role, ['admin', 'editor'])): ?>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/categories/index.php"
                        class="<?php echo $current_dir == 'categories' ? 'active' : ''; ?>">
                        <i class="fas fa-folder"></i> Categories
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($user_role == 'admin'): ?>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/team/index.php"
                        class="<?php echo $current_dir == 'team' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Team Members
                    </a>
                </li>
            <?php endif; ?>

            <?php if (in_array($user_role, ['admin', 'editor'])): ?>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/messages/index.php"
                        class="<?php echo $current_dir == 'messages' ? 'active' : ''; ?>">
                        <i class="fas fa-envelope"></i> Messages (
                        <?php if ($unread_messages_count > 0): ?>
                            <span class="sidebar-badge"><?php echo $unread_messages_count; ?></span> )
                        <?php endif; ?>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($user_role == 'admin'): ?>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/settings/index.php"
                        class="<?php echo $current_dir == 'settings' ? 'active' : ''; ?>">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
                <li>
                    <a href="<?php echo ADMIN_URL; ?>/users/index.php"
                        class="<?php echo $current_dir == 'users' ? 'active' : ''; ?>">
                        <i class="fas fa-user-shield"></i> Users
                    </a>
                </li>
            <?php endif; ?>

            <li>
                <a href="<?php echo SITE_URL; ?>" target="_blank">
                    <i class="fas fa-external-link"></i> View Site
                </a>
            </li>
        </ul>
    </nav>
</aside>