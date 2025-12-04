<?php
/**
 * Delete Blog Post
 * PipilikaX Admin Panel
 */

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/auth-check.php';

// Check permission
if (!hasPermission('author')) {
    redirect(ADMIN_URL . '/index.php');
}

$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    setFlash('Invalid post ID.', 'danger');
    redirect(ADMIN_URL . '/posts/index.php');
}

// Get post
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    setFlash('Post not found.', 'danger');
    redirect(ADMIN_URL . '/posts/index.php');
}

// Check if user can delete this post
if (!canEditPost($post['author_id'])) {
    setFlash('You do not have permission to delete this post.', 'danger');
    redirect(ADMIN_URL . '/posts/index.php');
}

// Handle deletion confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    // Delete featured image if exists
    if ($post['featured_image']) {
        $image_path = UPLOAD_PATH . 'blog/' . $post['featured_image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Delete the post
    $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->execute([$post_id]);

    logActivity('delete', 'blog_post', $post_id, "Deleted post: " . $post['title']);
    setFlash('Post deleted successfully!', 'success');
    redirect(ADMIN_URL . '/posts/index.php');
}

// Show confirmation page
$page_title = 'Delete Post';
$page_heading = 'Delete Post';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <h2>Delete Post</h2>
    <div class="breadcrumb">
        <a href="<?php echo ADMIN_URL; ?>">Dashboard</a> /
        <a href="<?php echo ADMIN_URL; ?>/posts/index.php">Posts</a> /
        <span>Delete</span>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 style="color: var(--admin-danger);">
            <i class="fas fa-exclamation-triangle"></i> Confirm Deletion
        </h3>
    </div>

    <div class="alert alert-warning">
        <strong>Warning:</strong> This action cannot be undone!
    </div>

    <p>Are you sure you want to delete the following post?</p>

    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <h4 style="margin: 0 0 10px 0;"><?php echo htmlspecialchars($post['title']); ?></h4>
        <p style="margin: 0; color: var(--admin-text-light);">
            <strong>Status:</strong> <?php echo ucfirst($post['status']); ?> |
            <strong>Views:</strong> <?php echo number_format($post['views']); ?> |
            <strong>Created:</strong> <?php echo formatDate($post['created_at'], 'F j, Y'); ?>
        </p>
        <?php if ($post['featured_image']): ?>
            <p style="margin: 10px 0 0 0;">
                <img src="<?php echo UPLOAD_URL; ?>/blog/<?php echo htmlspecialchars($post['featured_image']); ?>"
                    alt="Featured image" style="max-width: 200px; border-radius: 8px; margin-top: 10px;">
            </p>
        <?php endif; ?>
    </div>

    <form method="POST" action="" style="display: flex; gap: 10px;">
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash"></i> Yes, Delete Post
        </button>
        <a href="<?php echo ADMIN_URL; ?>/posts/index.php" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
        </a>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>