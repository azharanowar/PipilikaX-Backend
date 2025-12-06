<?php
/**
 * View Single Message
 * PipilikaX Admin Panel
 */

$page_title = 'View Message';
$page_heading = 'View Message';
require_once __DIR__ . '/../includes/header.php';

// Check permission
if (!hasPermission('editor')) {
    redirect(ADMIN_URL . '/index.php');
}

$message_id = $_GET['id'] ?? null;

if (!$message_id) {
    setFlash('Invalid message ID.', 'danger');
    redirect(ADMIN_URL . '/messages/index.php');
}

// Get message
$stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
$stmt->execute([$message_id]);
$message = $stmt->fetch();

if (!$message) {
    setFlash('Message not found.', 'danger');
    redirect(ADMIN_URL . '/messages/index.php');
}

// Handle delete
if (isset($_GET['delete']) && isset($_GET['token'])) {
    if (!verifyCSRFToken($_GET['token'])) {
        setFlash('Invalid security token.', 'danger');
    } else {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$message_id]);
        logActivity('delete', 'contact_message', $message_id, "Deleted contact message from " . $message['name']);
        setFlash('Message deleted successfully!', 'success');
        redirect(ADMIN_URL . '/messages/index.php');
    }
}

// Mark as read if it was new
if ($message['status'] == 'new') {
    $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?")->execute([$message_id]);
    $message['status'] = 'read';
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlash('Invalid security token.', 'danger');
    } else {
        $new_status = $_POST['status'] ?? 'read';
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $message_id]);
        setFlash('Message status updated!', 'success');
        redirect($_SERVER['PHP_SELF'] . '?id=' . $message_id);
    }
}

// Generate CSRF token
$csrf_token = generateCSRFToken();
?>

<div class="page-header">
    <h2>View Message</h2>
    <div class="breadcrumb">
        <a href="<?php echo ADMIN_URL; ?>">Dashboard</a> /
        <a href="<?php echo ADMIN_URL; ?>/messages/index.php">Messages</a> /
        <span>View</span>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Message from <?php echo htmlspecialchars($message['name']); ?></h3>
        <div style="display: flex; gap: 10px;">
            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo urlencode($message['subject'] ?: 'Your Message'); ?>"
                class="btn btn-primary btn-sm">
                <i class="fas fa-reply"></i> Reply via Email
            </a>
            <a href="?id=<?php echo $message_id; ?>&delete=1&token=<?php echo $csrf_token; ?>"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Are you sure you want to delete this message? This cannot be undone.');">
                <i class="fas fa-trash"></i> Delete
            </a>
            <a href="<?php echo ADMIN_URL; ?>/messages/index.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Messages
            </a>
        </div>
    </div>

    <!-- Message Details -->
    <table class="table" style="margin-bottom: 0;">
        <tr>
            <th style="width: 150px;">Name:</th>
            <td><strong><?php echo htmlspecialchars($message['name']); ?></strong></td>
        </tr>
        <tr>
            <th>Email:</th>
            <td>
                <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
                    <?php echo htmlspecialchars($message['email']); ?>
                </a>
            </td>
        </tr>
        <tr>
            <th>Subject:</th>
            <td><?php echo htmlspecialchars($message['subject'] ?: 'No subject'); ?></td>
        </tr>
        <tr>
            <th>Date:</th>
            <td><?php echo formatDate($message['created_at'], 'F j, Y g:i A'); ?></td>
        </tr>
        <tr>
            <th>IP Address:</th>
            <td><code><?php echo htmlspecialchars($message['ip_address']); ?></code></td>
        </tr>
        <tr>
            <th>Status:</th>
            <td>
                <?php
                $badge_class = [
                    'new' => 'badge-success',
                    'read' => 'badge-info',
                    'replied' => 'badge-warning',
                    'archived' => 'badge-danger'
                ][$message['status']] ?? 'badge-info';
                ?>
                <span class="badge <?php echo $badge_class; ?>">
                    <?php echo ucfirst($message['status']); ?>
                </span>
            </td>
        </tr>
    </table>
</div>

<div class="card">
    <div class="card-header">
        <h3>Message Content</h3>
    </div>
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; line-height: 1.8; white-space: pre-wrap;">
        <?php echo htmlspecialchars($message['message']); ?>
    </div>
</div>

<!-- Update Status -->
<div class="card">
    <div class="card-header">
        <h3>Update Status</h3>
    </div>
    <form method="POST" action="" style="display: flex; gap: 10px; align-items: center;">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <select name="status" class="form-control" style="width: 200px;">
            <option value="new" <?php echo $message['status'] == 'new' ? 'selected' : ''; ?>>New</option>
            <option value="read" <?php echo $message['status'] == 'read' ? 'selected' : ''; ?>>Read</option>
            <option value="replied" <?php echo $message['status'] == 'replied' ? 'selected' : ''; ?>>Replied</option>
            <option value="archived" <?php echo $message['status'] == 'archived' ? 'selected' : ''; ?>>Archived
            </option>
        </select>
        <button type="submit" name="update_status" class="btn btn-primary btn-sm">
            <i class="fas fa-save"></i> Update Status
        </button>
    </form>
</div>

<!-- Navigation -->
<?php
// Get previous and next messages
$prev_stmt = $pdo->prepare("SELECT id FROM contact_messages WHERE created_at > ? ORDER BY created_at ASC LIMIT 1");
$prev_stmt->execute([$message['created_at']]);
$prev_message = $prev_stmt->fetch();

$next_stmt = $pdo->prepare("SELECT id FROM contact_messages WHERE created_at < ? ORDER BY created_at DESC LIMIT 1");
$next_stmt->execute([$message['created_at']]);
$next_message = $next_stmt->fetch();
?>

<?php if ($prev_message || $next_message): ?>
    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
        <?php if ($prev_message): ?>
            <a href="?id=<?php echo $prev_message['id']; ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-chevron-left"></i> Newer Message
            </a>
        <?php else: ?>
            <span></span>
        <?php endif; ?>

        <?php if ($next_message): ?>
            <a href="?id=<?php echo $next_message['id']; ?>" class="btn btn-secondary btn-sm">
                Older Message <i class="fas fa-chevron-right"></i>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>