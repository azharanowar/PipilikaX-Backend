<?php
/**
 * Site Settings Management
 * PipilikaX Admin Panel
 */

$page_title = 'Site Settings';
$page_heading = 'Site Settings';
require_once __DIR__ . '/../includes/header.php';

// Check permission - Admin only
if ($current_user['role'] != 'admin') {
    setFlash('Access denied. Admin only.', 'error');
    redirect(ADMIN_URL . '/index.php');
}

$errors = [];
$success = '';

// Define setting groups with their display names
$setting_groups = [
    'general' => 'General',
    'menu' => 'Menu',
    'homepage' => 'Homepage',
    'footer' => 'Footer',
    'contact' => 'Contact'
];

// Current active tab
$active_tab = $_GET['tab'] ?? 'general';
if (!array_key_exists($active_tab, $setting_groups)) {
    $active_tab = 'general';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token.';
    } else {
        $group = $_POST['setting_group'] ?? 'general';
        $settings_data = $_POST['settings'] ?? [];

        foreach ($settings_data as $key => $value) {
            // Get setting info
            $stmt = $pdo->prepare("SELECT setting_type FROM settings WHERE setting_key = ?");
            $stmt->execute([$key]);
            $setting = $stmt->fetch();

            if ($setting) {
                // Handle different types
                if ($setting['setting_type'] === 'boolean') {
                    $value = isset($value) ? '1' : '0';
                }

                // Update setting
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ?, updated_by = ?, updated_at = NOW() WHERE setting_key = ?");
                $stmt->execute([$value, $current_user['id'], $key]);
            }
        }

        // Handle image uploads
        if (!empty($_FILES['settings_images'])) {
            foreach ($_FILES['settings_images']['name'] as $key => $name) {
                if ($_FILES['settings_images']['error'][$key] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $_FILES['settings_images']['name'][$key],
                        'type' => $_FILES['settings_images']['type'][$key],
                        'tmp_name' => $_FILES['settings_images']['tmp_name'][$key],
                        'error' => $_FILES['settings_images']['error'][$key],
                        'size' => $_FILES['settings_images']['size'][$key]
                    ];

                    $upload_result = uploadImage($file, 'settings');
                    if ($upload_result['success']) {
                        // Update setting with new filename
                        $stmt = $pdo->prepare("UPDATE settings SET setting_value = ?, updated_by = ?, updated_at = NOW() WHERE setting_key = ?");
                        $stmt->execute([$upload_result['filename'], $current_user['id'], $key]);
                    } else {
                        $errors[] = "Error uploading $key: " . $upload_result['message'];
                    }
                }
            }
        }

        if (empty($errors)) {
            logActivity('update', 'settings', null, "Updated $group settings");
            setFlash('Settings saved successfully!', 'success');
            redirect(ADMIN_URL . '/settings/index.php?tab=' . $group);
        }
    }
}

// Get settings for current tab
$stmt = $pdo->prepare("SELECT * FROM settings WHERE setting_group = ? ORDER BY id ASC");
$stmt->execute([$active_tab]);
$settings = $stmt->fetchAll();

// Handle menu actions BEFORE any output (so redirect works)
if ($active_tab === 'menu' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_action'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token.';
    } else {
        $action = $_POST['menu_action'];
        
        switch ($action) {
            case 'add':
                $title = sanitize($_POST['menu_title'] ?? '');
                $url = sanitize($_POST['menu_url'] ?? '');
                $target = $_POST['menu_target'] ?? '_self';
                $is_cta = isset($_POST['menu_is_cta']) ? 1 : 0;
                
                if ($title && $url) {
                    $max_order = $pdo->query("SELECT MAX(display_order) FROM navigation_menu")->fetchColumn();
                    $stmt = $pdo->prepare("INSERT INTO navigation_menu (title, url, target, display_order, is_active, is_cta) VALUES (?, ?, ?, ?, 1, ?)");
                    $stmt->execute([$title, $url, $target, ($max_order + 1), $is_cta]);
                    logActivity('create', 'navigation', $pdo->lastInsertId(), "Added menu item: $title");
                    setFlash('Menu item added successfully!', 'success');
                }
                break;
                
            case 'update':
                $id = (int)($_POST['menu_id'] ?? 0);
                $title = sanitize($_POST['menu_title'] ?? '');
                $url = sanitize($_POST['menu_url'] ?? '');
                $target = $_POST['menu_target'] ?? '_self';
                $is_cta = isset($_POST['menu_is_cta']) ? 1 : 0;
                
                if ($id && $title && $url) {
                    $stmt = $pdo->prepare("UPDATE navigation_menu SET title = ?, url = ?, target = ?, is_cta = ? WHERE id = ?");
                    $stmt->execute([$title, $url, $target, $is_cta, $id]);
                    logActivity('update', 'navigation', $id, "Updated menu item: $title");
                    setFlash('Menu item updated successfully!', 'success');
                }
                break;
                
            case 'delete':
                $id = (int)($_POST['menu_id'] ?? 0);
                if ($id) {
                    $stmt = $pdo->prepare("DELETE FROM navigation_menu WHERE id = ?");
                    $stmt->execute([$id]);
                    logActivity('delete', 'navigation', $id, "Deleted menu item");
                    setFlash('Menu item deleted successfully!', 'success');
                }
                break;
                
            case 'toggle':
                $id = (int)($_POST['menu_id'] ?? 0);
                if ($id) {
                    $stmt = $pdo->prepare("UPDATE navigation_menu SET is_active = NOT is_active WHERE id = ?");
                    $stmt->execute([$id]);
                    logActivity('update', 'navigation', $id, "Toggled menu item visibility");
                    setFlash('Menu item visibility toggled!', 'success');
                }
                break;
                
            case 'move_up':
                $id = (int)($_POST['menu_id'] ?? 0);
                if ($id) {
                    $current = $pdo->prepare("SELECT display_order FROM navigation_menu WHERE id = ?");
                    $current->execute([$id]);
                    $order = $current->fetchColumn();
                    
                    $prev = $pdo->prepare("SELECT id, display_order FROM navigation_menu WHERE display_order < ? ORDER BY display_order DESC LIMIT 1");
                    $prev->execute([$order]);
                    $prev_item = $prev->fetch();
                    
                    if ($prev_item) {
                        $pdo->prepare("UPDATE navigation_menu SET display_order = ? WHERE id = ?")->execute([$prev_item['display_order'], $id]);
                        $pdo->prepare("UPDATE navigation_menu SET display_order = ? WHERE id = ?")->execute([$order, $prev_item['id']]);
                    }
                }
                break;
                
            case 'move_down':
                $id = (int)($_POST['menu_id'] ?? 0);
                if ($id) {
                    $current = $pdo->prepare("SELECT display_order FROM navigation_menu WHERE id = ?");
                    $current->execute([$id]);
                    $order = $current->fetchColumn();
                    
                    $next = $pdo->prepare("SELECT id, display_order FROM navigation_menu WHERE display_order > ? ORDER BY display_order ASC LIMIT 1");
                    $next->execute([$order]);
                    $next_item = $next->fetch();
                    
                    if ($next_item) {
                        $pdo->prepare("UPDATE navigation_menu SET display_order = ? WHERE id = ?")->execute([$next_item['display_order'], $id]);
                        $pdo->prepare("UPDATE navigation_menu SET display_order = ? WHERE id = ?")->execute([$order, $next_item['id']]);
                    }
                }
                break;
        }
        redirect(ADMIN_URL . '/settings/index.php?tab=menu');
    }
}

// Get menu items for display (if on menu tab)
$menu_items = [];
if ($active_tab === 'menu') {
    $menu_items = $pdo->query("SELECT * FROM navigation_menu ORDER BY display_order ASC")->fetchAll();
}

// Generate CSRF token
$csrf_token = generateCSRFToken();
?>

<div class="page-header">
    <h2>Site Settings</h2>
    <div class="breadcrumb">
        <a href="<?php echo ADMIN_URL; ?>">Dashboard</a> / <span>Settings</span>
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

<!-- Tabs Navigation -->
<div class="settings-tabs">
    <?php foreach ($setting_groups as $group_key => $group_name): ?>
        <a href="?tab=<?php echo $group_key; ?>" class="settings-tab <?php echo $active_tab === $group_key ? 'active' : ''; ?>">
            <?php
            $icons = [
                'general' => 'fa-globe',
                'homepage' => 'fa-home',
                'footer' => 'fa-shoe-prints',
                'menu' => 'fa-bars',
                'contact' => 'fa-address-card'
            ];
            ?>
            <i class="fas <?php echo $icons[$group_key] ?? 'fa-cog'; ?>"></i>
            <?php echo $group_name; ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Settings Form / Menu Management -->
<div class="card">
    <div class="card-header">
        <h3><?php echo $setting_groups[$active_tab]; ?> Settings</h3>
    </div>

    <?php if ($active_tab === 'menu'): ?>
        <!-- Menu Management Interface -->
        
        <!-- Add New Menu Item -->
        <form method="POST" action="" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="menu_action" value="add">
            <h4 style="margin-bottom: 15px; color: var(--admin-text);"><i class="fas fa-plus"></i> Add New Menu Item</h4>
            <div style="display: grid; grid-template-columns: 2fr 2fr 1fr 1fr auto; gap: 10px; align-items: end;">
                <div class="form-group" style="margin: 0;">
                    <label>Title</label>
                    <input type="text" name="menu_title" class="form-control" placeholder="Menu Title" required>
                </div>
                <div class="form-group" style="margin: 0;">
                    <label>URL</label>
                    <input type="text" name="menu_url" class="form-control" placeholder="page.php or https://..." required>
                </div>
                <div class="form-group" style="margin: 0;">
                    <label>Target</label>
                    <select name="menu_target" class="form-control">
                        <option value="_self">Same Tab</option>
                        <option value="_blank">New Tab</option>
                    </select>
                </div>
                <div class="form-group" style="margin: 0;">
                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer; padding-top: 8px;">
                        <input type="checkbox" name="menu_is_cta" value="1">
                        <span>CTA Button</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="height: 42px;">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </form>
        
        <!-- Menu Items Table -->
        <?php if (count($menu_items) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Order</th>
                        <th>Title</th>
                        <th>URL</th>
                        <th>Target</th>
                        <th style="width: 80px;">Status</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menu_items as $index => $item): ?>
                        <tr>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                        <input type="hidden" name="menu_action" value="move_up">
                                        <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="btn-icon" title="Move Up" <?php echo $index === 0 ? 'disabled' : ''; ?>>
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                    </form>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                        <input type="hidden" name="menu_action" value="move_down">
                                        <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="btn-icon" title="Move Down" <?php echo $index === count($menu_items) - 1 ? 'disabled' : ''; ?>>
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td><strong><?php echo htmlspecialchars($item['title']); ?></strong></td>
                            <td><code><?php echo htmlspecialchars($item['url']); ?></code></td>
                            <td><?php echo $item['target'] === '_blank' ? 'New Tab' : 'Same Tab'; ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    <input type="hidden" name="menu_action" value="toggle">
                                    <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="status-badge <?php echo $item['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $item['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <button type="button" class="btn btn-sm" onclick="editMenuItem(<?php echo htmlspecialchars(json_encode($item)); ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this menu item?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                        <input type="hidden" name="menu_action" value="delete">
                                        <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-bars"></i>
                <p>No menu items found. Add your first menu item above.</p>
            </div>
        <?php endif; ?>
        
        <!-- Edit Modal -->
        <div id="editModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;">
            <div style="background: white; padding: 25px; border-radius: 12px; max-width: 500px; width: 90%;">
                <h3 style="margin-bottom: 20px;"><i class="fas fa-edit"></i> Edit Menu Item</h3>
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="menu_action" value="update">
                    <input type="hidden" name="menu_id" id="edit_menu_id">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="menu_title" id="edit_menu_title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>URL</label>
                        <input type="text" name="menu_url" id="edit_menu_url" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Target</label>
                        <select name="menu_target" id="edit_menu_target" class="form-control">
                            <option value="_self">Same Tab</option>
                            <option value="_blank">New Tab</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="menu_is_cta" id="edit_menu_is_cta" value="1">
                            <span>CTA Button (styled as button instead of link)</span>
                        </label>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn" onclick="closeEditModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        
        <script>
            function editMenuItem(item) {
                document.getElementById('edit_menu_id').value = item.id;
                document.getElementById('edit_menu_title').value = item.title;
                document.getElementById('edit_menu_url').value = item.url;
                document.getElementById('edit_menu_target').value = item.target;
                document.getElementById('edit_menu_is_cta').checked = item.is_cta == 1;
                document.getElementById('editModal').style.display = 'flex';
            }
            
            function closeEditModal() {
                document.getElementById('editModal').style.display = 'none';
            }
            
            // Close modal on outside click
            document.getElementById('editModal').addEventListener('click', function(e) {
                if (e.target === this) closeEditModal();
            });
        </script>

    <?php else: ?>
        <!-- Regular Settings Form -->
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="setting_group" value="<?php echo $active_tab; ?>">

            <?php if (count($settings) > 0): ?>
                <?php foreach ($settings as $setting): ?>
                    <div class="form-group">
                        <label for="<?php echo $setting['setting_key']; ?>">
                            <?php echo htmlspecialchars($setting['description'] ?: ucwords(str_replace('_', ' ', $setting['setting_key']))); ?>
                        </label>

                        <?php switch ($setting['setting_type']):
                            case 'textarea': ?>
                                <textarea id="<?php echo $setting['setting_key']; ?>"
                                    name="settings[<?php echo $setting['setting_key']; ?>]" class="form-control"
                                    rows="4"><?php echo htmlspecialchars($setting['setting_value']); ?></textarea>
                                <?php break; ?>

                            <?php case 'image': ?>
                                <?php if ($setting['setting_value']): ?>
                                    <div style="margin-bottom: 10px;">
                                        <?php
                                        $settings_path = UPLOAD_PATH . 'settings/' . $setting['setting_value'];
                                        $assets_path = ROOT_PATH . '/assets/images/' . $setting['setting_value'];

                                        if (file_exists($settings_path)) {
                                            $img_url = UPLOAD_URL . '/settings/' . htmlspecialchars($setting['setting_value']);
                                        } elseif (file_exists($assets_path)) {
                                            $img_url = ASSETS_URL . '/images/' . htmlspecialchars($setting['setting_value']);
                                        } else {
                                            $img_url = null;
                                        }

                                        if ($img_url):
                                        ?>
                                            <img src="<?php echo $img_url; ?>" alt="Current image"
                                                style="max-height: 60px; border-radius: 4px; background: #f0f0f0; padding: 5px;">
                                            <span style="margin-left: 10px; color: #666; font-size: 12px;">
                                                <?php echo htmlspecialchars($setting['setting_value']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <input type="file" id="<?php echo $setting['setting_key']; ?>"
                                    name="settings_images[<?php echo $setting['setting_key']; ?>]" class="form-control"
                                    accept="image/*">
                                <small style="color: #666;">Leave empty to keep current image</small>
                                <?php break; ?>

                            <?php case 'boolean': ?>
                                <div style="padding-top: 5px;">
                                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                        <input type="checkbox" name="settings[<?php echo $setting['setting_key']; ?>]" value="1"
                                            <?php echo $setting['setting_value'] ? 'checked' : ''; ?>>
                                        <span>Enabled</span>
                                    </label>
                                </div>
                                <?php break; ?>

                            <?php case 'number': ?>
                                <input type="number" id="<?php echo $setting['setting_key']; ?>"
                                    name="settings[<?php echo $setting['setting_key']; ?>]" class="form-control"
                                    value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                <?php break; ?>

                            <?php case 'url': ?>
                                <input type="url" id="<?php echo $setting['setting_key']; ?>"
                                    name="settings[<?php echo $setting['setting_key']; ?>]" class="form-control"
                                    value="<?php echo htmlspecialchars($setting['setting_value']); ?>"
                                    placeholder="https://...">
                                <?php break; ?>

                            <?php default: ?>
                                <input type="text" id="<?php echo $setting['setting_key']; ?>"
                                    name="settings[<?php echo $setting['setting_key']; ?>]" class="form-control"
                                    value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                        <?php endswitch; ?>
                    </div>
                <?php endforeach; ?>

                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    <button type="submit" name="save_settings" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save <?php echo $setting_groups[$active_tab]; ?> Settings
                    </button>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-cog"></i>
                    <p>No settings found for this group.</p>
                </div>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>

<style>
    .settings-tabs {
        display: flex;
        gap: 5px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        background: white;
        padding: 10px;
        border-radius: 8px;
        box-shadow: var(--admin-shadow);
    }

    .settings-tab {
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        color: var(--admin-text-light);
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .settings-tab:hover {
        background: #f0f0f0;
        color: var(--admin-text);
    }

    .settings-tab.active {
        background: var(--admin-primary);
        color: white;
    }

    .settings-tab i {
        font-size: 14px;
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
