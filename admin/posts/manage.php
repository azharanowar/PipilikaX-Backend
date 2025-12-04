<?php
/**
 * Create/Edit Blog Post
 * PipilikaX Admin Panel
 */

$page_title = isset($_GET['id']) ? 'Edit Post' : 'Create Post';
$page_heading = $page_title;
require_once __DIR__ . '/../includes/header.php';

// Check permission
if (!hasPermission('author')) {
    redirect(ADMIN_URL . '/index.php');
}

$post_id = $_GET['id'] ?? null;
$post = null;
$errors = [];

// If editing, fetch the post
if ($post_id) {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();

    if (!$post) {
        setFlash('Post not found.', 'danger');
        redirect(ADMIN_URL . '/posts/index.php');
    }

    // Check if user can edit this post
    if (!canEditPost($post['author_id'])) {
        setFlash('You do not have permission to edit this post.', 'danger');
        redirect(ADMIN_URL . '/posts/index.php');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setFlash('Invalid security token. Please try again.', 'danger');
        redirect($_SERVER['PHP_SELF'] . ($post_id ? '?id=' . $post_id : ''));
    }

    $title = sanitize($_POST['title'] ?? '');
    $slug = sanitize($_POST['slug'] ?? '');
    $excerpt = sanitize($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? ''; // Don't sanitize - TinyMCE handles this
    $category_id = $_POST['category_id'] ?? null;
    $status = $_POST['status'] ?? 'draft';

    // Validation
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }

    if (empty($slug)) {
        $slug = createSlug($title);
    }

    if (empty($content)) {
        $errors[] = 'Content is required.';
    }

    // Check if slug is unique
    $slug_check = $pdo->prepare("SELECT id FROM blog_posts WHERE slug = ? AND id != ?");
    $slug_check->execute([$slug, $post_id ?? 0]);
    if ($slug_check->fetch()) {
        $errors[] = 'Slug already exists. Please use a different one.';
    }

    // Handle image upload
    $featured_image = $post['featured_image'] ?? null;
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $upload_result = uploadImage($_FILES['featured_image'], 'blog');
        if ($upload_result['success']) {
            // Delete old image if exists
            if ($post && $post['featured_image']) {
                $old_image = UPLOAD_PATH . 'blog/' . $post['featured_image'];
                if (file_exists($old_image)) {
                    unlink($old_image);
                }
            }
            $featured_image = $upload_result['filename'];
        } else {
            $errors[] = $upload_result['message'];
        }
    }

    // If no errors, save
    if (empty($errors)) {
        if ($post_id) {
            // Update existing post
            $stmt = $pdo->prepare("
                UPDATE blog_posts 
                SET title = ?, slug = ?, excerpt = ?, content = ?, 
                    featured_image = ?, category_id = ?, status = ?,
                    updated_at = NOW(), published_at = IF(status = 'published' AND published_at IS NULL, NOW(), published_at)
                WHERE id = ?
            ");
            $stmt->execute([$title, $slug, $excerpt, $content, $featured_image, $category_id, $status, $post_id]);

            logActivity('update', 'blog_post', $post_id, "Updated post: $title");
            setFlash('Post updated successfully!', 'success');
        } else {
            // Create new post
            $stmt = $pdo->prepare("
                INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, 
                                       category_id, author_id, status, published_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, IF(? = 'published', NOW(), NULL))
            ");
            $stmt->execute([
                $title,
                $slug,
                $excerpt,
                $content,
                $featured_image,
                $category_id,
                $current_user['id'],
                $status,
                $status
            ]);

            $post_id = $pdo->lastInsertId();
            logActivity('create', 'blog_post', $post_id, "Created post: $title");
            setFlash('Post created successfully!', 'success');
        }

        redirect(ADMIN_URL . '/posts/index.php');
    }
}

// Get categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="page-header">
    <h2><?php echo $page_heading; ?></h2>
    <div class="breadcrumb">
        <a href="<?php echo ADMIN_URL; ?>">Dashboard</a> /
        <a href="<?php echo ADMIN_URL; ?>/posts/index.php">Posts</a> /
        <span><?php echo $post_id ? 'Edit' : 'Create'; ?></span>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul style="margin: 10px 0 0 0; padding-left: 20px;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

    <div class="card">
        <div class="card-header">
            <h3>Post Details</h3>
            <div style="display: flex; gap: 10px;">
                <button type="submit" name="status" value="draft" class="btn btn-secondary btn-sm">
                    <i class="fas fa-save"></i> Save as Draft
                </button>
                <button type="submit" name="status" value="published" class="btn btn-primary btn-sm">
                    <i class="fas fa-check"></i>
                    <?php echo ($post && $post['status'] == 'published') ? 'Update' : 'Publish'; ?>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="title">Title *</label>
            <input type="text" id="title" name="title" class="form-control"
                value="<?php echo htmlspecialchars($post['title'] ?? $_POST['title'] ?? ''); ?>" required autofocus>
        </div>

        <div class="form-group">
            <label for="slug">Slug (URL-friendly) *</label>
            <input type="text" id="slug" name="slug" class="form-control"
                value="<?php echo htmlspecialchars($post['slug'] ?? $_POST['slug'] ?? ''); ?>"
                placeholder="leave-blank-to-auto-generate">
            <small style="color: var(--admin-text-light);">Leave blank to auto-generate from title</small>
        </div>

        <div class="form-group">
            <label for="excerpt">Excerpt</label>
            <textarea id="excerpt" name="excerpt" class="form-control" rows="3"
                placeholder="Short description (optional)"><?php echo htmlspecialchars($post['excerpt'] ?? $_POST['excerpt'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="content">Content *</label>
            <textarea id="content" name="content"
                class="form-control"><?php echo htmlspecialchars($post['content'] ?? $_POST['content'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" class="form-control">
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo (isset($post['category_id']) && $post['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="featured_image">Featured Image</label>
            <?php if ($post && $post['featured_image']): ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?php echo UPLOAD_URL; ?>/blog/<?php echo htmlspecialchars($post['featured_image']); ?>"
                        alt="Current featured image"
                        style="max-width: 300px; border-radius: 8px; border: 1px solid var(--admin-border);">
                </div>
            <?php endif; ?>
            <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*">
            <small style="color: var(--admin-text-light);">Accepted: JPG, PNG, GIF, WEBP. Max size: 5MB</small>
        </div>
    </div>

    <div style="display: flex; gap: 10px; margin-top: 20px;">
        <button type="submit" name="status" value="<?php echo $post['status'] ?? 'draft'; ?>" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Post
        </button>
        <a href="<?php echo ADMIN_URL; ?>/posts/index.php" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
        </a>
    </div>
</form>

<?php
// TinyMCE initialization
$additional_js = "
<script>
    tinymce.init({
        selector: '#content',
        height: 500,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | code | help',
        content_style: 'body { font-family: Poppins, sans-serif; font-size: 14px; }'
    });
    
    // Auto-generate slug from title
    document.getElementById('title').addEventListener('blur', function() {
        const slugInput = document.getElementById('slug');
        if (!slugInput.value) {
            slugInput.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
</script>
";

require_once __DIR__ . '/../includes/footer.php';
?>