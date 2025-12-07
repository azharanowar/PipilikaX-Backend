<?php
/**
 * Users Management
 * PipilikaX Admin Panel
 */

$page_title = 'Users';
$page_heading = 'Users Management';
require_once __DIR__ . '/../includes/header.php';

// Check permission - Admin only
if ($current_user['role'] != 'admin') {
    setFlash('Access denied. Admin only.', 'error');
    redirect(ADMIN_URL . '/index.php');
}

$errors = [];
$success = '';
$edit_user = null;

// Handle delete
if (isset($_GET['delete']) && isset($_GET['token'])) {
    if (!verifyCSRFToken($_GET['token'])) {
        $errors[] = 'Invalid security token.';
    } else {
        $delete_id = (int) $_GET['delete'];

        // Can't delete yourself
        if ($delete_id == $current_user['id']) {
            $errors[] = 'You cannot delete your own account.';
        } else {
            // Get user info before deleting
            $stmt = $pdo->prepare("SELECT username, avatar FROM users WHERE id = ?");
            $stmt->execute([$delete_id]);
            $del_user = $stmt->fetch();

            if ($del_user) {
                // Delete user
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$delete_id]);

                // Delete avatar if exists
                if ($del_user['avatar'] && file_exists(UPLOAD_PATH . 'avatars/' . $del_user['avatar'])) {
                    unlink(UPLOAD_PATH . 'avatars/' . $del_user['avatar']);
                }

                logActivity('delete', 'user', $delete_id, "Deleted user: " . $del_user['username']);
                setFlash('User deleted successfully!', 'success');
                redirect(ADMIN_URL . '/users/index.php');
            }
        }
    }
}

// Handle toggle status
if (isset($_GET['toggle']) && isset($_GET['token'])) {
    if (!verifyCSRFToken($_GET['token'])) {
        $errors[] = 'Invalid security token.';
    } else {
        $toggle_id = (int) $_GET['toggle'];

        // Can't deactivate yourself
        if ($toggle_id == $current_user['id']) {
            $errors[] = 'You cannot deactivate your own account.';
        } else {
            $stmt = $pdo->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
            $stmt->execute([$toggle_id]);
            logActivity('update', 'user', $toggle_id, "Toggled user status");
            setFlash('User status updated!', 'success');
            redirect(ADMIN_URL . '/users/index.php');
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_user'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token.';
    } else {
        $username = sanitize($_POST['username'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $full_name = sanitize($_POST['full_name'] ?? '');
        $role = $_POST['role'] ?? 'subscriber';
        $bio = sanitize($_POST['bio'] ?? '');
        $password = $_POST['password'] ?? '';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $user_id = $_POST['user_id'] ?? null;

        // Validation
        if (empty($username)) {
            $errors[] = 'Username is required.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = 'Username can only contain letters, numbers, and underscores.';
        }

        if (empty($email)) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
        }

        // Check unique username
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $user_id ?? 0]);
        if ($stmt->fetch()) {
            $errors[] = 'Username already exists.';
        }

        // Check unique email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id ?? 0]);
        if ($stmt->fetch()) {
            $errors[] = 'Email already exists.';
        }

        // Password validation for new users
        if (!$user_id && empty($password)) {
            $errors[] = 'Password is required for new users.';
        } elseif (!empty($password) && strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        // Handle avatar upload
        $avatar_filename = $_POST['existing_avatar'] ?? '';
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            // Create avatars directory if not exists
            if (!is_dir(UPLOAD_PATH . 'avatars')) {
                mkdir(UPLOAD_PATH . 'avatars', 0755, true);
            }

            $upload_result = uploadImage($_FILES['avatar'], 'avatars');
            if ($upload_result['success']) {
                // Delete old avatar
                if ($avatar_filename && file_exists(UPLOAD_PATH . 'avatars/' . $avatar_filename)) {
                    unlink(UPLOAD_PATH . 'avatars/' . $avatar_filename);
                }
                $avatar_filename = $upload_result['filename'];
            } else {
                $errors[] = $upload_result['message'];
            }
        }

        if (empty($errors)) {
            if ($user_id) {
                // Update existing user
                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("
                        UPDATE users SET username = ?, email = ?, password = ?, full_name = ?, 
                        role = ?, bio = ?, avatar = ?, is_active = ? WHERE id = ?
                    ");
                    $stmt->execute([$username, $email, $hashed_password, $full_name, $role, $bio, $avatar_filename, $is_active, $user_id]);
                } else {
                    $stmt = $pdo->prepare("
                        UPDATE users SET username = ?, email = ?, full_name = ?, 
                        role = ?, bio = ?, avatar = ?, is_active = ? WHERE id = ?
                    ");
                    $stmt->execute([$username, $email, $full_name, $role, $bio, $avatar_filename, $is_active, $user_id]);
                }
                logActivity('update', 'user', $user_id, "Updated user: $username");
                setFlash('User updated successfully!', 'success');
            } else {
                // Create new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password, full_name, role, bio, avatar, is_active)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$username, $email, $hashed_password, $full_name, $role, $bio, $avatar_filename, $is_active]);
                $user_id = $pdo->lastInsertId();
                logActivity('create', 'user', $user_id, "Created user: $username");
                setFlash('User created successfully!', 'success');
            }
            redirect(ADMIN_URL . '/users/index.php');
        }
    }
}

// Get user to edit
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_user = $stmt->fetch();
}

// Get all users
$users = $pdo->query("SELECT * FROM users ORDER BY role ASC, username ASC")->fetchAll();

// Generate CSRF token
$csrf_token = generateCSRFToken();

// Role badge colors
$role_badges = [
    'admin' => 'badge-danger',
    'editor' => 'badge-warning',
    'author' => 'badge-info',
    'subscriber' => 'badge-success'
];
?>

<div class="page-header">
    <h2>Users Management</h2>
    <div class="breadcrumb">
        <a href="<?php echo ADMIN_URL; ?>">Dashboard</a> / <span>Users</span>
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
        <h3><?php echo $edit_user ? 'Edit User' : 'Add New User'; ?></h3>
        <?php if ($edit_user): ?>
            <a href="index.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Cancel Edit
            </a>
        <?php endif; ?>
    </div>

    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <?php if ($edit_user): ?>
            <input type="hidden" name="user_id" value="<?php echo $edit_user['id']; ?>">
            <input type="hidden" name="existing_avatar" value="<?php echo htmlspecialchars($edit_user['avatar'] ?? ''); ?>">
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" class="form-control"
                    value="<?php echo htmlspecialchars($edit_user['username'] ?? $_POST['username'] ?? ''); ?>"
                    required>
                <small style="color: #666;">Letters, numbers, and underscores only</small>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="<?php echo htmlspecialchars($edit_user['email'] ?? $_POST['email'] ?? ''); ?>" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="form-control"
                    value="<?php echo htmlspecialchars($edit_user['full_name'] ?? $_POST['full_name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="role">Role *</label>
                <select id="role" name="role" class="form-control">
                    <option value="subscriber" <?php echo (($edit_user['role'] ?? '') == 'subscriber') ? 'selected' : ''; ?>>
                        Subscriber</option>
                    <option value="author" <?php echo (($edit_user['role'] ?? '') == 'author') ? 'selected' : ''; ?>>
                        Author
                    </option>
                    <option value="editor" <?php echo (($edit_user['role'] ?? '') == 'editor') ? 'selected' : ''; ?>>
                        Editor
                    </option>
                    <option value="admin" <?php echo (($edit_user['role'] ?? '') == 'admin') ? 'selected' : ''; ?>>Admin
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password <?php echo $edit_user ? '(leave blank to keep current)' : '*'; ?></label>
            <input type="password" id="password" name="password" class="form-control" <?php echo $edit_user ? '' : 'required'; ?> minlength="6">
            <small style="color: #666;">Minimum 6 characters</small>
        </div>

        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" class="form-control" rows="3"
                placeholder="Brief bio (optional)"><?php echo htmlspecialchars($edit_user['bio'] ?? $_POST['bio'] ?? ''); ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="avatar">Avatar</label>
                <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
                <small style="color: #666;">Recommended: 200x200px square image</small>
                <?php if ($edit_user && $edit_user['avatar']): ?>
                    <div style="margin-top: 10px;">
                        <?php
                        $avatar_path = UPLOAD_PATH . 'avatars/' . $edit_user['avatar'];
                        if (file_exists($avatar_path)) {
                            $avatar_url = UPLOAD_URL . '/avatars/' . htmlspecialchars($edit_user['avatar']);
                        } else {
                            $avatar_url = null;
                        }
                        if ($avatar_url):
                            ?>
                            <img src="<?php echo $avatar_url; ?>" alt="Current avatar"
                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>&nbsp;</label>
                <div style="padding-top: 10px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" <?php echo (($edit_user['is_active'] ?? true) || isset($_POST['is_active'])) ? 'checked' : ''; ?>>
                        <span>Active (can log in)</span>
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" name="save_user" class="btn btn-primary">
            <i class="fas fa-save"></i> <?php echo $edit_user ? 'Update' : 'Create'; ?> User
        </button>
    </form>
</div>

<!-- Users List -->
<div class="card">
    <div class="card-header">
        <h3>All Users (<?php echo count($users); ?>)</h3>
    </div>

    <?php if (count($users) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">Avatar</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?php
                            $avatar_url = null;
                            if ($user['avatar'] && file_exists(UPLOAD_PATH . 'avatars/' . $user['avatar'])) {
                                $avatar_url = UPLOAD_URL . '/avatars/' . htmlspecialchars($user['avatar']);
                            }
                            ?>
                            <?php if ($avatar_url): ?>
                                <img src="<?php echo $avatar_url; ?>" alt=""
                                    style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                            <?php else: ?>
                                <div
                                    style="width: 40px; height: 40px; border-radius: 50%; background: var(--admin-primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                            <?php if ($user['id'] == $current_user['id']): ?>
                                <span class="badge badge-info">You</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['full_name'] ?: '-'); ?></td>
                        <td>
                            <span class="badge <?php echo $role_badges[$user['role']] ?? 'badge-info'; ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['is_active']): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo $user['last_login'] ? formatDate($user['last_login'], 'M j, Y g:i A') : 'Never'; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $user['id']; ?>" class="btn btn-sm btn-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($user['id'] != $current_user['id']): ?>
                                    <a href="?toggle=<?php echo $user['id']; ?>&token=<?php echo $csrf_token; ?>"
                                        class="btn btn-sm <?php echo $user['is_active'] ? 'btn-warning' : 'btn-success'; ?>"
                                        title="<?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                        <i class="fas fa-<?php echo $user['is_active'] ? 'ban' : 'check'; ?>"></i>
                                    </a>
                                    <a href="?delete=<?php echo $user['id']; ?>&token=<?php echo $csrf_token; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this user?');" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <p>No users found.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Role Permissions Info -->
<div class="card">
    <div class="card-header">
        <h3>Role Permissions</h3>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Role</th>
                <th>Permissions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="badge badge-danger">Admin</span></td>
                <td>Full access: Manage all content, users, settings, team members</td>
            </tr>
            <tr>
                <td><span class="badge badge-warning">Editor</span></td>
                <td>Manage all posts, categories, and messages</td>
            </tr>
            <tr>
                <td><span class="badge badge-info">Author</span></td>
                <td>Create and manage own posts only</td>
            </tr>
            <tr>
                <td><span class="badge badge-success">Subscriber</span></td>
                <td>View dashboard only (future: commenting, profile)</td>
            </tr>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>