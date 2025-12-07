<?php
/**
 * Blog Posts List
 * PipilikaX Admin Panel
 */

$page_title = 'Blog Posts';
$page_heading = 'Blog Posts';
require_once __DIR__ . '/../includes/header.php';

// Check permission
if (!hasPermission('author')) {
    redirect(ADMIN_URL . '/index.php');
}

// Get filter parameters
$status_filter = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query
$where = [];
$params = [];

if ($status_filter != 'all') {
    $where[] = "p.status = ?";
    $params[] = $status_filter;
}

if (!empty($search)) {
    $where[] = "(p.title LIKE ? OR p.content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Authors can only see their own posts
if ($current_user['role'] == 'author') {
    $where[] = "p.author_id = ?";
    $params[] = $current_user['id'];
}

$where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get total count
$count_query = "SELECT COUNT(*) FROM blog_posts p $where_clause";
$stmt = $pdo->prepare($count_query);
$stmt->execute($params);
$total_posts = $stmt->fetchColumn();

// Pagination
$page = $_GET['page'] ?? 1;
$per_page = ADMIN_POSTS_PER_PAGE;
$total_pages = ceil($total_posts / $per_page);
$offset = ($page - 1) * $per_page;

// Get posts
$query = "
    SELECT p.*, u.full_name as author_name, c.name as category_name
    FROM blog_posts p
    LEFT JOIN users u ON p.author_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    $where_clause
    ORDER BY p.created_at DESC
    LIMIT $per_page OFFSET $offset
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$posts = $stmt->fetchAll();
?>

<div class="page-header">
    <h2>Blog Posts</h2>
    <div class="breadcrumb">
        <a href="<?php echo ADMIN_URL; ?>">Dashboard</a> / <span>Posts</span>
    </div>
</div>

<!-- Filters and Actions -->
<div class="card">
    <div class="card-header">
        <h3>Manage Posts (<?php echo $total_posts; ?>)</h3>
        <a href="manage.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Create New Post
        </a>
    </div>

    <div style="padding: 0 0 20px 0;">
        <form method="GET" action="" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <select name="status" class="form-control" style="width: 150px;">
                <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>All Status</option>
                <option value="published" <?php echo $status_filter == 'published' ? 'selected' : ''; ?>>Published
                </option>
                <option value="draft" <?php echo $status_filter == 'draft' ? 'selected' : ''; ?>>Draft</option>
                <option value="archived" <?php echo $status_filter == 'archived' ? 'selected' : ''; ?>>Archived</option>
            </select>

            <input type="text" name="search" class="form-control" placeholder="Search posts..."
                value="<?php echo htmlspecialchars($search); ?>" style="width: 250px;">

            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="fas fa-search"></i> Filter
            </button>

            <?php if ($status_filter != 'all' || !empty($search)): ?>
                <a href="index.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i> Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (count($posts) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Date</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                            <?php if ($post['featured_image']): ?>
                                <i class="fas fa-image" style="color: #17a2b8; margin-left: 5px;" title="Has featured image"></i>
                            <?php endif; ?>
                        </td>
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
                        <td>
                            <div class="action-buttons">
                                <?php if (canEditPost($post['author_id'])): ?>
                                    <a href="manage.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-secondary"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger"
                                        data-confirm="Are you sure you want to delete this post?" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo SITE_URL; ?>/blog/<?php echo $post['slug']; ?>" target="_blank"
                                    class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div style="display: flex; justify-content: center; gap: 5px; margin-top: 20px;">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>"
                        class="btn btn-sm btn-secondary">« Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="btn btn-sm btn-primary"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>"
                            class="btn btn-sm btn-secondary"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>"
                        class="btn btn-sm btn-secondary">Next »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-file-alt"></i>
            <p>No posts found. <?php echo empty($search) ? 'Create your first post!' : 'Try a different search.'; ?></p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>