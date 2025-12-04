<?php
/**
 * Contact Messages Viewer
 * PipilikaX Admin Panel
 */

$page_title = 'Contact Messages';
$page_heading = 'Contact Messages';
require_once __DIR__ . '/../includes/header.php';

// Check permission
if (!hasPermission('editor')) {
    redirect(ADMIN_URL . '/index.php');
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $message_id = $_POST['message_id'] ?? null;
    $new_status = $_POST['status'] ?? 'new';

    if ($message_id) {
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $message_id]);
        setFlash('Message status updated!', 'success');
        redirect($_SERVER['PHP_SELF']);
    }
}

// Get filter parameters
$status_filter = $_GET['status'] ?? 'all';

// Build query
$where = [];
$params = [];

if ($status_filter != 'all') {
    $where[] = "status = ?";
    $params[] = $status_filter;
}

$where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get total count
$count_query = "SELECT COUNT(*) FROM contact_messages $where_clause";
$stmt = $pdo->prepare($count_query);
$stmt->execute($params);
$total_messages = $stmt->fetchColumn();

// Pagination
$page = $_GET['page'] ?? 1;
$per_page = 20;
$total_pages = ceil($total_messages / $per_page);
$offset = ($page - 1) * $per_page;

// Get messages
$query = "
    SELECT * FROM contact_messages
    $where_clause
    ORDER BY created_at DESC
    LIMIT $per_page OFFSET $offset
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$messages = $stmt->fetchAll();
?>

<div class="page-header">
    <h2>Contact Messages</h2>
    <div class="breadcrumb">
        <a href="<?php echo ADMIN_URL; ?>">Dashboard</a> / <span>Messages</span>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Messages (<?php echo $total_messages; ?>)</h3>
    </div>

    <!-- Filters -->
    <div style="padding: 0 0 20px 0;">
        <form method="GET" action="" style="display: flex; gap: 10px;">
            <select name="status" class="form-control" style="width: 150px;">
                <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>All Status</option>
                <option value="new" <?php echo $status_filter == 'new' ? 'selected' : ''; ?>>New</option>
                <option value="read" <?php echo $status_filter == 'read' ? 'selected' : ''; ?>>Read</option>
                <option value="replied" <?php echo $status_filter == 'replied' ? 'selected' : ''; ?>>Replied</option>
                <option value="archived" <?php echo $status_filter == 'archived' ? 'selected' : ''; ?>>Archived</option>
            </select>

            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="fas fa-filter"></i> Filter
            </button>

            <?php if ($status_filter != 'all'): ?>
                <a href="index.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i> Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (count($messages) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($msg['name']); ?></strong></td>
                        <td>
                            <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>">
                                <?php echo htmlspecialchars($msg['email']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($msg['subject'] ?: 'No subject'); ?></td>
                        <td style="max-width: 300px;">
                            <?php echo truncate($msg['message'], 100); ?>
                        </td>
                        <td><?php echo formatDate($msg['created_at'], 'M j, Y g:i A'); ?></td>
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
                            <div class="action-buttons">
                                <a href="view.php?id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" class="btn btn-sm btn-primary"
                                    title="Reply">
                                    <i class="fas fa-reply"></i>
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
                    <a href="?page=<?php echo $page - 1; ?>&status=<?php echo $status_filter; ?>" class="btn btn-sm btn-secondary">«
                        Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="btn btn-sm btn-primary"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>"
                            class="btn btn-sm btn-secondary"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&status=<?php echo $status_filter; ?>"
                        class="btn btn-sm btn-secondary">Next »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-envelope"></i>
            <p>No messages found. <?php echo $status_filter != 'all' ? 'Try a different filter.' : ''; ?></p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>