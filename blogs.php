<?php
/**
 * Blogs Listing Page - PipilikaX
 * Dynamic version fetching from database
 * Supports: search, category filter, author filter, pagination
 */

$page_title = 'Blogs';

// Include header and navigation
require_once __DIR__ . '/includes/templates/header.php';
require_once __DIR__ . '/includes/templates/navigation.php';

// Pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = POSTS_PER_PAGE;
$offset = ($page - 1) * $per_page;

// Filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_slug = isset($_GET['category']) ? trim($_GET['category']) : '';
$author_id = isset($_GET['author']) ? (int) $_GET['author'] : 0;

// Build filter conditions and params
$conditions = ["p.status = 'published'"];
$params = [];

// Search filter
if ($search) {
    $conditions[] = "(p.title LIKE ? OR p.excerpt LIKE ? OR p.content LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

// Category filter
$filter_category = null;
if ($category_slug) {
    $cat_stmt = $pdo->prepare("SELECT id, name FROM categories WHERE slug = ?");
    $cat_stmt->execute([$category_slug]);
    $filter_category = $cat_stmt->fetch();
    if ($filter_category) {
        $conditions[] = "p.category_id = ?";
        $params[] = $filter_category['id'];
    }
}

// Author filter
$filter_author = null;
if ($author_id) {
    $author_stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE id = ?");
    $author_stmt->execute([$author_id]);
    $filter_author = $author_stmt->fetch();
    if ($filter_author) {
        $conditions[] = "p.author_id = ?";
        $params[] = $filter_author['id'];
    }
}

// Combine conditions
$where_clause = implode(' AND ', $conditions);

// Get total published posts (with filters)
$count_sql = "SELECT COUNT(*) FROM blog_posts p WHERE " . $where_clause;
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_posts = $count_stmt->fetchColumn();
$total_pages = ceil($total_posts / $per_page);

// Get published posts (with filters)
$posts_sql = "
    SELECT p.*, u.full_name as author_name, c.name as category_name
    FROM blog_posts p
    LEFT JOIN users u ON p.author_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE " . $where_clause . "
    ORDER BY p.published_at DESC
    LIMIT $per_page OFFSET $offset
";
$posts_stmt = $pdo->prepare($posts_sql);
$posts_stmt->execute($params);
$posts = $posts_stmt->fetchAll();
?>

<main>
    <section id="blogsSection">
        <div class="container">
            <div class="blogs-header">
                <h1>Our Space Chronicles</h1>
                <p>Explore the latest discoveries, missions, and wonders from across the cosmos</p>

                <!-- Search Form -->
                <form method="GET" action="" class="blog-search-form"
                    style="margin-top: 25px; margin-bottom: 30px; display: flex; justify-content: center; gap: 10px; max-width: 500px; margin-left: auto; margin-right: auto;">
                    <div style="position: relative; flex: 1;">
                        <input type="text" name="search" placeholder="Search posts..."
                            value="<?php echo htmlspecialchars($search); ?>"
                            style="width: 100%; padding: 12px 45px 12px 15px; border: 2px solid rgba(233,3,48,0.2); border-radius: 25px; font-size: 16px; outline: none; transition: border-color 0.3s ease;"
                            onfocus="this.style.borderColor='var(--main-color)'"
                            onblur="this.style.borderColor='rgba(233,3,48,0.2)'">
                        <button type="submit"
                            style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); background: var(--main-color); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <?php if ($search): ?>
                        <a href="<?php echo SITE_URL; ?>/blogs.php"
                            style="display: flex; align-items: center; gap: 5px; padding: 12px 20px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 25px; font-size: 14px;">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    <?php endif; ?>
                </form>

                <?php if ($search): ?>
                    <p style="margin-top: 15px; color: #666;">
                        Found <strong><?php echo $total_posts; ?></strong> result(s) for
                        "<strong><?php echo htmlspecialchars($search); ?></strong>"
                    </p>
                <?php endif; ?>

                <?php if ($filter_category || $filter_author): ?>
                    <div
                        style="margin-top: 20px; margin-bottom: 25px; padding: 15px 25px; background: linear-gradient(135deg, rgba(233,3,48,0.08) 0%, rgba(255,107,107,0.08) 100%); border-radius: 12px; display: inline-flex; align-items: center; gap: 15px;">
                        <?php if ($filter_category): ?>
                            <span style="font-size: 16px;">
                                <i class="fas fa-folder" style="color: var(--main-color); margin-right: 8px;"></i>
                                Posts in <strong><?php echo htmlspecialchars($filter_category['name']); ?></strong>
                            </span>
                        <?php endif; ?>
                        <?php if ($filter_author): ?>
                            <span style="font-size: 16px;">
                                <i class="fas fa-user" style="color: var(--main-color); margin-right: 8px;"></i>
                                Posts by <strong><?php echo htmlspecialchars($filter_author['full_name']); ?></strong>
                            </span>
                        <?php endif; ?>
                        <a href="<?php echo SITE_URL; ?>/blogs.php"
                            style="display: inline-flex; align-items: center; gap: 5px; padding: 8px 16px; background: var(--main-color); color: white; text-decoration: none; border-radius: 20px; font-size: 13px;">
                            <i class="fas fa-times"></i> Clear Filter
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (count($posts) > 0): ?>
                <div class="blog-grid">
                    <?php
                    // Fallback images from assets when uploads don't exist
                    $fallback_images = ['image-4.jpg', 'rocket.jpg', 'space-robot-man.jpg', 'image-of-the-day.jpg', 'planets-image.jpg', 'space.jpg', 'sapceX-image.jpg', 'spaceX-vertical.jpg'];
                    $img_index = 0;

                    foreach ($posts as $post):
                        // Check if image exists in uploads or assets
                        $has_image = false;
                        $image_src = '';

                        if ($post['featured_image']) {
                            // First check uploads folder
                            if (file_exists(UPLOAD_PATH . 'blog/' . $post['featured_image'])) {
                                $has_image = true;
                                $image_src = UPLOAD_URL . '/blog/' . htmlspecialchars($post['featured_image']);
                            }
                            // Then check assets folder
                            elseif (file_exists(ROOT_PATH . '/assets/images/' . $post['featured_image'])) {
                                $has_image = true;
                                $image_src = ASSETS_URL . '/images/' . htmlspecialchars($post['featured_image']);
                            }
                        }
                        ?>
                        <div class="blog-card">
                            <?php if ($has_image): ?>
                                <img class="blog-image" src="<?php echo $image_src; ?>"
                                    alt="<?php echo htmlspecialchars($post['title']); ?>">
                            <?php else: ?>
                                <div class="blog-image-placeholder" style="
                                    height: 200px;
                                    background: linear-gradient(135deg, var(--main-color) 0%, #ff6b6b 100%);
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 48px;
                                    font-weight: 700;
                                    color: rgba(255,255,255,0.9);
                                    text-transform: uppercase;
                                "><?php echo strtoupper(substr($post['title'], 0, 1)); ?></div>
                            <?php endif; ?>

                            <div class="blog-content">
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p><?php echo htmlspecialchars($post['excerpt'] ?: truncate($post['content'], 150)); ?></p>
                                <a href="<?php echo SITE_URL; ?>/blog/<?php echo htmlspecialchars($post['slug']); ?>">
                                    <button class="btn">
                                        Learn More <img src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" alt="Arrow" />
                                    </button>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1):
                    // Build filter params for pagination links
                    $filter_params = '';
                    if ($search)
                        $filter_params .= '&search=' . urlencode($search);
                    if ($category_slug)
                        $filter_params .= '&category=' . urlencode($category_slug);
                    if ($author_id)
                        $filter_params .= '&author=' . $author_id;
                    ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?><?php echo $filter_params; ?>" class="pagination-btn">
                                <img src="<?php echo ASSETS_URL; ?>/images/arrow-left.svg" alt="Previous"> Previous
                            </a>
                        <?php endif; ?>

                        <div class="pagination-numbers">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="pagination-number active"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?><?php echo $filter_params; ?>"
                                        class="pagination-number"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?><?php echo $filter_params; ?>" class="pagination-btn">
                                Next <img src="<?php echo ASSETS_URL; ?>/images/arrow-right.svg" alt="Next">
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="empty-blogs">
                    <img src="<?php echo ASSETS_URL; ?>/images/space.jpg" alt="No posts"
                        style="max-width: 400px; opacity: 0.5; border-radius: 12px;">
                    <h2>No Posts Yet</h2>
                    <p>Our space chronicles are being prepared. Check back soon for exciting updates!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/templates/footer.php'; ?>