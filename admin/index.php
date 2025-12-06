<?php
/**
 * Admin Dashboard
 * PipilikaX Admin Panel
 */

$page_title = 'Dashboard';
$page_heading = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

// Get statistics
$stmt = $pdo->query("SELECT COUNT(*) FROM blog_posts WHERE status='published'");
$total_posts = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM blog_posts WHERE status='draft'");
$draft_posts = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status='new'");
$new_messages = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT SUM(views) FROM blog_posts");
$total_views = $stmt->fetchColumn() ?: 0;

// Get recent posts
$recent_posts = $pdo->query("
    SELECT p.*, u.full_name as author_name, c.name as category_name
    FROM blog_posts p
    LEFT JOIN users u ON p.author_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.created_at DESC
    LIMIT 5
")->fetchAll();

// Get recent messages (for editors and admins)
$recent_messages = [];
if (hasPermission('editor')) {
    $recent_messages = $pdo->query("
        SELECT * FROM contact_messages
        ORDER BY created_at DESC
        LIMIT 5
    ")->fetchAll();
}
?>

<div class="page-header">
    <h2>Welcome back, <?php echo htmlspecialchars($current_user['full_name'] ?? $current_user['username']); ?>!</h2>
    <div class="breadcrumb">
        <span>Dashboard</span>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <h4>Published Posts</h4>
        <div class="stat-value"><?php echo $total_posts; ?></div>
    </div>

    <div class="stat-card" style="border-left-color: #ffc107;">
        <h4>Draft Posts</h4>
        <div class="stat-value" style="color: #ffc107;"><?php echo $draft_posts; ?></div>
    </div>

    <div class="stat-card" style="border-left-color: #28a745;">
        <h4>New Messages</h4>
        <div class="stat-value" style="color: #28a745;"><?php echo $new_messages; ?></div>
    </div>

    <div class="stat-card" style="border-left-color: #17a2b8;">
        <h4>Total Views</h4>
        <div class="stat-value" style="color: #17a2b8;"><?php echo number_format($total_views); ?></div>
    </div>
</div>

<!-- Recent Posts -->
<div class="card">
    <div class="card-header">
        <h3>Recent Posts</h3>
        <?php if (hasPermission('author')): ?>
            <a href="<?php echo ADMIN_URL; ?>/posts/manage.php" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create New Post
            </a>
        <?php endif; ?>
    </div>

    <?php if (count($recent_posts) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_posts as $post): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($post['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($post['author_name'] ?? 'Unknown'); ?></td>
                        <td><?php echo htmlspecialchars($post['category_name'] ?? 'Uncategorized'); ?></td>
                        <td>
                            <?php
                            $badge_class = [
                                'published' => 'badge-success',
                                'draft' => 'badge-warning',
                                'archived' => 'badge-info'
                            ][$post['status']] ?? 'badge-info';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>">
                                <?php echo ucfirst($post['status']); ?>
                            </span>
                        </td>
                        <td><?php echo number_format($post['views']); ?></td>
                        <td><?php echo formatDate($post['created_at'], 'M j, Y'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 20px;">
            <a href="<?php echo ADMIN_URL; ?>/posts/index.php" class="btn btn-secondary btn-sm">
                View All Posts
            </a>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-file-alt"></i>
            <p>No posts yet. Create your first post to get started!</p>
        </div>
    <?php endif; ?>
</div>

<!-- Recent Messages (for editors and admins) -->
<?php if (hasPermission('editor') && count($recent_messages) > 0): ?>
    <div class="card">
        <div class="card-header">
            <h3>Recent Messages <?php if ($new_messages > 0): ?><span
                        class="badge badge-success"><?php echo $new_messages; ?> new</span><?php endif; ?></h3>
            <a href="<?php echo ADMIN_URL; ?>/messages/index.php" class="btn btn-primary btn-sm">
                <i class="fas fa-envelope"></i> View All Messages
            </a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th style="width: 80px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_messages as $msg): ?>
                    <tr class="<?php echo $msg['status'] == 'new' ? 'unread-row' : ''; ?>">
                        <td><strong><?php echo htmlspecialchars($msg['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($msg['email']); ?></td>
                        <td><?php echo htmlspecialchars($msg['subject'] ?: 'No subject'); ?></td>
                        <td><?php echo formatDate($msg['created_at'], 'M j, Y'); ?></td>
                        <td>
                            <?php
                            $badge_class = [
                                'new' => 'badge-success',
                                'read' => 'badge-info',
                                'replied' => 'badge-warning',
                                'archived' => 'badge-danger'
                            ][$msg['status']] ?? 'badge-info';
                            ?>
                            <span class="badge <?php echo $badge_class; ?>">
                                <?php echo ucfirst($msg['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo ADMIN_URL; ?>/messages/view.php?id=<?php echo $msg['id']; ?>"
                                class="btn btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Quick Actions (Role-based) -->
<div class="card">
    <div class="card-header">
        <h3>Quick Actions</h3>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <?php if (hasPermission('author')): ?>
            <a href="<?php echo ADMIN_URL; ?>/posts/manage.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Post
            </a>
        <?php endif; ?>

        <?php if (hasPermission('editor')): ?>
            <a href="<?php echo ADMIN_URL; ?>/categories/index.php" class="btn btn-secondary">
                <i class="fas fa-folder"></i> Manage Categories
            </a>
        <?php endif; ?>

        <?php if ($user_role == 'admin'): ?>
            <a href="<?php echo ADMIN_URL; ?>/team/index.php" class="btn btn-secondary">
                <i class="fas fa-users"></i> Manage Team
            </a>
            <a href="<?php echo ADMIN_URL; ?>/settings/index.php" class="btn btn-secondary">
                <i class="fas fa-cog"></i> Site Settings
            </a>
        <?php endif; ?>

        <a href="<?php echo SITE_URL; ?>" target="_blank" class="btn btn-secondary">
            <i class="fas fa-external-link"></i> View Website
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>