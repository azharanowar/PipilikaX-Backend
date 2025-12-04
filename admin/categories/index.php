<?php
/**
 * Categories Management
 * PipilikaX Admin Panel
 */

$page_title = 'Categories';
$page_heading = 'Categories';
require_once __DIR__ . '/../includes/header.php';

// Check permission
if (!hasPermission('editor')) {
    redirect(ADMIN_URL . '/index.php');
}

$errors = [];
$success = '';
$edit_category = null;

// Handle form submission (Create/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_category'])) {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token.';
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $slug = sanitize($_POST['slug'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $category_id = $_POST['category_id'] ?? null;

        // Validation
        if (empty($name)) {
            $errors[] = 'Category name is required.';
        }

        if (empty($slug)) {
            $slug = createSlug($name);
        }

        // Check if slug is unique
        $slug_check = $pdo->prepare("SELECT id FROM categories WHERE slug = ? AND id != ?");
        $slug_check->execute([$slug, $category_id ?? 0]);
        if ($slug_check->fetch()) {
            $errors[] = 'Slug already exists.';
        }

        if (empty($errors)) {
            if ($category_id) {
                // Update
                $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, description = ? WHERE id = ?");
                $stmt->execute([$name, $slug, $description, $category_id]);
                logActivity('update', 'category', $category_id, "Updated category: $name");
                $success = 'Category updated successfully!';
            } else {
                // Create
                $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
                $stmt->execute([$name, $slug, $description]);
                $category_id = $pdo->lastInsertId();
                logActivity('create', 'category', $category_id, "Created category: $name");
                $success = 'Category created successfully!';
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Check if category has posts
    $post_check = $pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE category_id = ?");
    $post_check->execute([$delete_id]);
    $post_count = $post_check->fetchColumn();

    if ($post_count > 0) {
        $errors[] = "Cannot delete category. It has $post_count post(s) assigned.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$delete_id]);
        logActivity('delete', 'category', $delete_id, "Deleted category");
        $success = 'Category deleted successfully!';
    }
}

// Get category to edit
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_category = $stmt->fetch();
}

// Get all categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="page-header">
    <h2>Categories</h2>
    <div class="breadcrumb">
        <a href="<?php echo ADMIN_URL; ?>">Dashboard</a> / <span>Categories</span>
    </div>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

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
        <h3><?php echo $edit_category ? 'Edit Category' : 'Add New Category'; ?></h3>
        <?php if ($edit_category): ?>
            <a href="index.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Cancel Edit
            </a>
        <?php endif; ?>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <?php if ($edit_category): ?>
            <input type="hidden" name="category_id" value="<?php echo $edit_category['id']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Category Name *</label>
            <input type="text" id="name" name="name" class="form-control"
                value="<?php echo htmlspecialchars($edit_category['name'] ?? $_POST['name'] ?? ''); ?>" required
                autofocus>
        </div>

        <div class="form-group">
            <label for="slug">Slug (URL-friendly)</label>
            <input type="text" id="slug" name="slug" class="form-control"
                value="<?php echo htmlspecialchars($edit_category['slug'] ?? $_POST['slug'] ?? ''); ?>"
                placeholder="leave-blank-to-auto-generate">
            <small style="color: #666;">Leave blank to auto-generate from name</small>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3"
                placeholder="Brief description (optional)"><?php echo htmlspecialchars($edit_category['description'] ?? $_POST['description'] ?? ''); ?></textarea>
        </div>

        <button type="submit" name="save_category" class="btn btn-primary">
            <i class="fas fa-save"></i> <?php echo $edit_category ? 'Update' : 'Create'; ?> Category
        </button>
    </form>
</div>

<!-- Categories List -->
<div class="card">
    <div class="card-header">
        <h3>All Categories (<?php echo count($categories); ?>)</h3>
    </div>

    <?php if (count($categories) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Posts</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($category['name']); ?></strong></td>
                        <td><code><?php echo htmlspecialchars($category['slug']); ?></code></td>
                        <td><?php echo htmlspecialchars($category['description'] ?: '-'); ?></td>
                        <td><?php echo number_format($category['post_count']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $category['id']; ?>" class="btn btn-sm btn-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($category['post_count'] == 0): ?>
                                    <a href="?delete=<?php echo $category['id']; ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this category?');" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-secondary" disabled title="Cannot delete: has posts">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-folder"></i>
            <p>No categories yet. Create your first category above!</p>
        </div>
    <?php endif; ?>
</div>

<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('blur', function () {
        const slugInput = document.getElementById('slug');
        if (!slugInput.value) {
            slugInput.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>