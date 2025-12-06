<?php
/**
 * Contact Messages Management
 * PipilikaX Admin Panel
 */

$page_title = 'Contact Messages';
$page_heading = 'Contact Messages';
require_once __DIR__ . '/../includes/header.php';

// Check permission
if (!hasPermission('editor')) {
    redirect(ADMIN_URL . '/index.php');
}

$errors = [];
$success = '';

// Handle single delete
if (isset($_GET['delete']) && isset($_GET['token'])) {
    if (!verifyCSRFToken($_GET['token'])) {
        $errors[] = 'Invalid security token.';
    } else {
        $delete_id = (int) $_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$delete_id]);
        logActivity('delete', 'contact_message', $delete_id, "Deleted contact message");
        setFlash('Message deleted successfully!', 'success');
        redirect(ADMIN_URL . '/messages/index.php');
    }
}

// Handle bulk actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token.';
    } else {
        $action = $_POST['bulk_action'];
        $selected_ids = $_POST['selected_messages'] ?? [];

        if (empty($selected_ids)) {
            $errors[] = 'Please select at least one message.';
        } else {
            $ids = array_map('intval', $selected_ids);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            switch ($action) {
                case 'delete':
                    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id IN ($placeholders)");
                    $stmt->execute($ids);
                    logActivity('bulk_delete', 'contact_message', null, "Bulk deleted " . count($ids) . " messages");
                    setFlash(count($ids) . ' message(s) deleted successfully!', 'success');
                    break;

                case 'mark_read':
                    $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id IN ($placeholders)");
                    $stmt->execute($ids);
                    setFlash(count($ids) . ' message(s) marked as read.', 'success');
                    break;

                case 'mark_archived':
                    $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'archived' WHERE id IN ($placeholders)");
                    $stmt->execute($ids);
                    setFlash(count($ids) . ' message(s) archived.', 'success');
                    break;
            }
            redirect(ADMIN_URL . '/messages/index.php');
        }
    }
}

// Handle single status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token.';
    } else {
        $message_id = $_POST['message_id'] ?? null;
        $new_status = $_POST['status'] ?? 'new';

        if ($message_id) {
            $stmt = $pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
            $stmt->execute([$new_status, $message_id]);
            setFlash('Message status updated!', 'success');
            redirect($_SERVER['PHP_SELF'] . '?' . http_build_query($_GET));
        }
    }
}

// Get filter and search parameters
$status_filter = $_GET['status'] ?? 'all';
$search_query = trim($_GET['search'] ?? '');

// Build query
$where = [];
$params = [];

if ($status_filter != 'all') {
    $where[] = "status = ?";
    $params[] = $status_filter;
}

if (!empty($search_query)) {
    $where[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ?)";
    $search_param = '%' . $search_query . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get total count
$count_query = "SELECT COUNT(*) FROM contact_messages $where_clause";
$stmt = $pdo->prepare($count_query);
$stmt->execute($params);
$total_messages = $stmt->fetchColumn();

// Pagination
$page = max(1, (int) ($_GET['page'] ?? 1));
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

// Generate CSRF token for delete links
$csrf_token = generateCSRFToken();
?>

<div class="page-header">
    <h2>Contact Messages</h2>
    <div class="breadcrumb">
        <a href="<?php echo ADMIN_URL; ?>">Dashboard</a> / <span>Messages</span>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3>Messages (<?php echo $total_messages; ?>)</h3>
    </div>

    <!-- Filters & Search -->
    <div style="padding: 0 0 20px 0;">
        <form method="GET" action="" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <select name="status" class="form-control" style="width: 150px;">
                <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>All Status</option>
                <option value="new" <?php echo $status_filter == 'new' ? 'selected' : ''; ?>>New</option>
                <option value="read" <?php echo $status_filter == 'read' ? 'selected' : ''; ?>>Read</option>
                <option value="replied" <?php echo $status_filter == 'replied' ? 'selected' : ''; ?>>Replied</option>
                <option value="archived" <?php echo $status_filter == 'archived' ? 'selected' : ''; ?>>Archived</option>
            </select>

            <input type="text" name="search" class="form-control" style="width: 250px;"
                placeholder="Search by name, email, subject..." value="<?php echo htmlspecialchars($search_query); ?>">

            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="fas fa-search"></i> Search
            </button>

            <?php if ($status_filter != 'all' || !empty($search_query)): ?>
                <a href="index.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i> Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (count($messages) > 0): ?>
        <form method="POST" action="" id="bulk-form">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <!-- Bulk Actions -->
            <div style="display: flex; gap: 10px; margin-bottom: 15px; align-items: center;">
                <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                    <input type="checkbox" id="select-all">
                    <span>Select All</span>
                </label>

                <select name="bulk_action" class="form-control" style="width: 180px;">
                    <option value="">-- Bulk Actions --</option>
                    <option value="mark_read">Mark as Read</option>
                    <option value="mark_archived">Archive Selected</option>
                    <option value="delete">Delete Selected</option>
                </select>

                <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirmBulkAction();">
                    <i class="fas fa-check"></i> Apply
                </button>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px;"></th>
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
                        <tr class="<?php echo $msg['status'] == 'new' ? 'unread-row' : ''; ?>">
                            <td>
                                <input type="checkbox" name="selected_messages[]" value="<?php echo $msg['id']; ?>"
                                    class="message-checkbox">
                            </td>
                            <td><strong><?php echo htmlspecialchars($msg['name']); ?></strong></td>
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>">
                                    <?php echo htmlspecialchars($msg['email']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($msg['subject'] ?: 'No subject'); ?></td>
                            <td style="max-width: 250px;">
                                <?php echo truncate($msg['message'], 80); ?>
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
                                    <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>"
                                        class="btn btn-sm btn-primary" title="Reply">
                                        <i class="fas fa-reply"></i>
                                    </a>
                                    <a href="?delete=<?php echo $msg['id']; ?>&token=<?php echo $csrf_token; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this message?');"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div style="display: flex; justify-content: center; gap: 5px; margin-top: 20px;">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search_query); ?>"
                        class="btn btn-sm btn-secondary">« Previous</a>
                <?php endif; ?>

                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="btn btn-sm btn-primary"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search_query); ?>"
                            class="btn btn-sm btn-secondary"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search_query); ?>"
                        class="btn btn-sm btn-secondary">Next »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-envelope"></i>
            <p>No messages found.
                <?php echo ($status_filter != 'all' || !empty($search_query)) ? 'Try a different filter or search term.' : ''; ?>
            </p>
        </div>
    <?php endif; ?>
</div>

<style>
    .unread-row {
        background-color: rgba(40, 167, 69, 0.05);
        font-weight: 500;
    }

    .unread-row td:first-child {
        border-left: 3px solid #28a745;
    }
</style>

<script>
    // Select all functionality
    document.getElementById('select-all').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.message-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Confirm bulk action
    function confirmBulkAction() {
        const action = document.querySelector('select[name="bulk_action"]').value;
        const checked = document.querySelectorAll('.message-checkbox:checked').length;

        if (!action) {
            alert('Please select a bulk action.');
            return false;
        }

        if (checked === 0) {
            alert('Please select at least one message.');
            return false;
        }

        if (action === 'delete') {
            return confirm('Are you sure you want to delete ' + checked + ' message(s)? This cannot be undone.');
        }

        return true;
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>