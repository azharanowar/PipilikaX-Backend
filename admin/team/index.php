<?php
/**
 * Team Members Management
 * PipilikaX Admin Panel
 */

$page_title = 'Team Members';
$page_heading = 'Team Members';
require_once __DIR__ . '/../includes/header.php';

// Check permission - Admin only
if ($current_user['role'] != 'admin') {
    setFlash('Access denied. Admin only.', 'error');
    redirect(ADMIN_URL . '/index.php');
}

$errors = [];
$success = '';
$edit_member = null;

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];

    // Get member photo before deleting
    $stmt = $pdo->prepare("SELECT photo FROM team_members WHERE id = ?");
    $stmt->execute([$delete_id]);
    $member = $stmt->fetch();

    // Delete member
    $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = ?");
    $stmt->execute([$delete_id]);

    // Delete photo if exists
    if ($member && $member['photo'] && file_exists(UPLOAD_PATH . 'team/' . $member['photo'])) {
        unlink(UPLOAD_PATH . 'team/' . $member['photo']);
    }

    logActivity('delete', 'team_member', $delete_id, "Deleted team member");
    setFlash('Team member deleted successfully!', 'success');
    redirect(ADMIN_URL . '/team/index.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_member'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token.';
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $position = sanitize($_POST['position'] ?? '');
        $bio = sanitize($_POST['bio'] ?? '');
        $facebook_url = sanitize($_POST['facebook_url'] ?? '');
        $linkedin_url = sanitize($_POST['linkedin_url'] ?? '');
        $twitter_url = sanitize($_POST['twitter_url'] ?? '');
        $display_order = (int) ($_POST['display_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $member_id = $_POST['member_id'] ?? null;

        // Validation
        if (empty($name)) {
            $errors[] = 'Name is required.';
        }
        if (empty($position)) {
            $errors[] = 'Position is required.';
        }

        // Handle photo upload
        $photo_filename = $_POST['existing_photo'] ?? '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $upload_result = uploadImage($_FILES['photo'], 'team');
            if ($upload_result['success']) {
                // Delete old photo if updating
                if ($photo_filename && file_exists(UPLOAD_PATH . 'team/' . $photo_filename)) {
                    unlink(UPLOAD_PATH . 'team/' . $photo_filename);
                }
                $photo_filename = $upload_result['filename'];
            } else {
                $errors[] = $upload_result['message'];
            }
        }

        if (empty($errors)) {
            if ($member_id) {
                // Update
                $stmt = $pdo->prepare("
                    UPDATE team_members 
                    SET name = ?, position = ?, bio = ?, photo = ?, 
                        facebook_url = ?, linkedin_url = ?, twitter_url = ?,
                        display_order = ?, is_active = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $name,
                    $position,
                    $bio,
                    $photo_filename,
                    $facebook_url,
                    $linkedin_url,
                    $twitter_url,
                    $display_order,
                    $is_active,
                    $member_id
                ]);
                logActivity('update', 'team_member', $member_id, "Updated team member: $name");
                setFlash('Team member updated successfully!', 'success');
            } else {
                // Create
                $stmt = $pdo->prepare("
                    INSERT INTO team_members (name, position, bio, photo, facebook_url, linkedin_url, twitter_url, display_order, is_active)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $name,
                    $position,
                    $bio,
                    $photo_filename,
                    $facebook_url,
                    $linkedin_url,
                    $twitter_url,
                    $display_order,
                    $is_active
                ]);
                $member_id = $pdo->lastInsertId();
                logActivity('create', 'team_member', $member_id, "Created team member: $name");
                setFlash('Team member created successfully!', 'success');
            }
            redirect(ADMIN_URL . '/team/index.php');
        }
    }
}

// Get member to edit
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM team_members WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_member = $stmt->fetch();
}

// Get all team members
$members = $pdo->query("SELECT * FROM team_members ORDER BY display_order ASC, name ASC")->fetchAll();

// Define fallback images for preview
$team_fallback_images = ['team-1.jpg', 'team-2.jpg', 'team-3.jpg', 'team-4.jpg'];
?>

<div class="page-header">
    <h2>Team Members</h2>
    <div class="breadcrumb">
        <a href="<?php echo ADMIN_URL; ?>">Dashboard</a> / <span>Team Members</span>
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

<!-- Create/Edit Form -->
<div class="card">
    <div class="card-header">
        <h3><?php echo $edit_member ? 'Edit Team Member' : 'Add New Team Member'; ?></h3>
        <?php if ($edit_member): ?>
            <a href="index.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Cancel Edit
            </a>
        <?php endif; ?>
    </div>

    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <?php if ($edit_member): ?>
            <input type="hidden" name="member_id" value="<?php echo $edit_member['id']; ?>">
            <input type="hidden" name="existing_photo" value="<?php echo htmlspecialchars($edit_member['photo'] ?? ''); ?>">
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="<?php echo htmlspecialchars($edit_member['name'] ?? $_POST['name'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="position">Position *</label>
                <input type="text" id="position" name="position" class="form-control"
                    value="<?php echo htmlspecialchars($edit_member['position'] ?? $_POST['position'] ?? ''); ?>"
                    placeholder="e.g. CEO, Designer, Developer" required>
            </div>
        </div>

        <div class="form-group">
            <label for="bio">Bio/Description</label>
            <textarea id="bio" name="bio" class="form-control" rows="3"
                placeholder="Brief description about the team member"><?php echo htmlspecialchars($edit_member['bio'] ?? $_POST['bio'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
            <small style="color: #666;">Recommended size: 400x400px. Max file size: 5MB</small>
            <?php if ($edit_member && $edit_member['photo']): ?>
                <div style="margin-top: 10px;">
                    <img src="<?php
                    if (file_exists(UPLOAD_PATH . 'team/' . $edit_member['photo'])) {
                        echo UPLOAD_URL . '/team/' . htmlspecialchars($edit_member['photo']);
                    } else {
                        echo ASSETS_URL . '/images/' . $team_fallback_images[0];
                    }
                    ?>" alt="Current photo" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                    <span style="margin-left: 10px; color: #666;">Current photo</span>
                </div>
            <?php endif; ?>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="facebook_url">Facebook URL</label>
                <input type="url" id="facebook_url" name="facebook_url" class="form-control"
                    value="<?php echo htmlspecialchars($edit_member['facebook_url'] ?? $_POST['facebook_url'] ?? ''); ?>"
                    placeholder="https://facebook.com/username">
            </div>

            <div class="form-group">
                <label for="linkedin_url">LinkedIn URL</label>
                <input type="url" id="linkedin_url" name="linkedin_url" class="form-control"
                    value="<?php echo htmlspecialchars($edit_member['linkedin_url'] ?? $_POST['linkedin_url'] ?? ''); ?>"
                    placeholder="https://linkedin.com/in/username">
            </div>

            <div class="form-group">
                <label for="twitter_url">Twitter URL</label>
                <input type="url" id="twitter_url" name="twitter_url" class="form-control"
                    value="<?php echo htmlspecialchars($edit_member['twitter_url'] ?? $_POST['twitter_url'] ?? ''); ?>"
                    placeholder="https://twitter.com/username">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="display_order">Display Order</label>
                <input type="number" id="display_order" name="display_order" class="form-control"
                    value="<?php echo htmlspecialchars($edit_member['display_order'] ?? $_POST['display_order'] ?? '0'); ?>"
                    min="0">
                <small style="color: #666;">Lower numbers appear first</small>
            </div>

            <div class="form-group">
                <label>&nbsp;</label>
                <div style="padding-top: 10px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" <?php echo (($edit_member['is_active'] ?? true) || isset($_POST['is_active'])) ? 'checked' : ''; ?>>
                        <span>Active (show on website)</span>
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" name="save_member" class="btn btn-primary">
            <i class="fas fa-save"></i> <?php echo $edit_member ? 'Update' : 'Add'; ?> Team Member
        </button>
    </form>
</div>

<!-- Team Members List -->
<div class="card">
    <div class="card-header">
        <h3>All Team Members (<?php echo count($members); ?>)</h3>
    </div>

    <?php if (count($members) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">Photo</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Social Links</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $img_index = 0;
                foreach ($members as $member):
                    // Determine image source
                    if ($member['photo'] && file_exists(UPLOAD_PATH . 'team/' . $member['photo'])) {
                        $member_image = UPLOAD_URL . '/team/' . htmlspecialchars($member['photo']);
                    } else {
                        $member_image = ASSETS_URL . '/images/' . $team_fallback_images[$img_index % count($team_fallback_images)];
                        $img_index++;
                    }
                    ?>
                    <tr>
                        <td>
                            <img src="<?php echo $member_image; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>"
                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        </td>
                        <td><strong><?php echo htmlspecialchars($member['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($member['position']); ?></td>
                        <td>
                            <?php if ($member['facebook_url']): ?>
                                <a href="<?php echo htmlspecialchars($member['facebook_url']); ?>" target="_blank" title="Facebook"
                                    style="margin-right: 8px;">
                                    <i class="fab fa-facebook"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($member['linkedin_url']): ?>
                                <a href="<?php echo htmlspecialchars($member['linkedin_url']); ?>" target="_blank" title="LinkedIn"
                                    style="margin-right: 8px;">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($member['twitter_url']): ?>
                                <a href="<?php echo htmlspecialchars($member['twitter_url']); ?>" target="_blank" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (!$member['facebook_url'] && !$member['linkedin_url'] && !$member['twitter_url']): ?>
                                <span style="color: #999;">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $member['display_order']; ?></td>
                        <td>
                            <?php if ($member['is_active']): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $member['id']; ?>" class="btn btn-sm btn-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?php echo $member['id']; ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this team member?');"
                                    title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <p>No team members yet. Add your first team member above!</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>