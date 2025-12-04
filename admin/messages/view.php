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

// Mark as read if it was new
if ($message['status'] == 'new') {
    $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?")->execute([$message_id]);
    $message['status'] = 'read';
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'] ?? 'read';
    $stmt = $pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $message_id]);
    setFlash('Message status updated!', 'success');
    redirect($_SERVER['PHP_SELF'] . '?id=' . $message_id);
}
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
            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-reply"></i> Reply via Email
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
            <td><?php echo htmlspecialchars($message['ip_address']); ?></td>
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
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; line-height: 1.6;">
        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
    </div>
</div>

<!-- Update Status -->
<div class="card">
    <div class="card-header">
        <h3>Update Status</h3>
    </div>
    <form method="POST" action="" style="display: flex; gap: 10px; align-items: center;">
        <select name="status" class="form-control" style="width: 200px;">
            <option value="new" <?php echo $message['status'] == 'new' ? 'selected' : ''; ?>>New</option>
            <option value="read" <?php echo $message['status'] == 'read' ? 'selected' : ''; ?>>Read</option>
            <option value="replied" <?php echo $message['status'] == 'replied' ? 'selected' : ''; ?>>Replied</option>
            <option value="archived" <?php echo $message['status'] == 'archived' ? 'selected' : ''; ?>>Archived</option>
        </select>
        <button type="submit" name="update_status" class="btn btn-primary btn-sm">
            <i class="fas fa-save"></i> Update Status
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>