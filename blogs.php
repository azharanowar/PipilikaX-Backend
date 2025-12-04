<?php
/**
 * Blogs Listing Page - PipilikaX
 * Dynamic version fetching from database
 */

$page_title = 'Blogs';

// Include header and navigation
require_once __DIR__ . '/includes/templates/header.php';
require_once __DIR__ . '/includes/templates/navigation.php';

// Pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = POSTS_PER_PAGE;
$offset = ($page - 1) * $per_page;

// Get total published posts
$total_stmt = $pdo->query("SELECT COUNT(*) FROM blog_posts WHERE status = 'published'");
$total_posts = $total_stmt->fetchColumn();
$total_pages = ceil($total_posts / $per_page);

// Get published posts
$posts_query = "
    SELECT p.*, u.full_name as author_name, c.name as category_name
    FROM blog_posts p
    LEFT JOIN users u ON p.author_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'published'
    ORDER BY p.published_at DESC
    LIMIT $per_page OFFSET $offset
";
$posts = $pdo->query($posts_query)->fetchAll();
?>

<main>
    <section id="blogsSection">
        <div class="container">
            <div class="blogs-header">
                <h1>Our Space Chronicles</h1>
                <p>Explore the latest discoveries, missions, and wonders from across the cosmos</p>
            </div>

            <?php if (count($posts) > 0): ?>
                <div class="blog-grid">
                    <?php
                    // Fallback images from assets when uploads don't exist
                    $fallback_images = ['image-4.jpg', 'rocket.jpg', 'space-robot-man.jpg', 'image-of-the-day.jpg', 'planets-image.jpg', 'space.jpg', 'sapceX-image.jpg', 'spaceX-vertical.jpg'];
                    $img_index = 0;

                    foreach ($posts as $post):
                        // Use uploaded image if exists, otherwise use fallback
                        if ($post['featured_image'] && file_exists(UPLOAD_PATH . 'blog/' . $post['featured_image'])) {
                            $image_src = UPLOAD_URL . '/blog/' . htmlspecialchars($post['featured_image']);
                        } else {
                            $image_src = ASSETS_URL . '/images/' . $fallback_images[$img_index % count($fallback_images)];
                            $img_index++;
                        }
                    ?>
                        <div class="blog-card">
                            <img class="blog-image"
                                 src="<?php echo $image_src; ?>"
                                 alt="<?php echo htmlspecialchars($post['title']); ?>">

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

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>" class="pagination-btn">
                                <img src="<?php echo ASSETS_URL; ?>/images/arrow-left.svg" alt="Previous"> Previous
                            </a>
                        <?php endif; ?>

                        <div class="pagination-numbers">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="pagination-number active"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?>" class="pagination-number"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?>" class="pagination-btn">
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