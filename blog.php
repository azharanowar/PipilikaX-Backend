<?php
/**
 * Single Blog Post Page - PipilikaX
 * Dynamic version fetching from database
 */

// Get slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header("HTTP/1.0 404 Not Found");
    require_once __DIR__ . '/404.php';
    exit;
}

// Include required files
require_once __DIR__ . '/includes/templates/header.php';

// Fetch the post
$stmt = $pdo->prepare("
    SELECT p.*, u.full_name as author_name, u.bio as author_bio,
           c.name as category_name, c.slug as category_slug
    FROM blog_posts p
    LEFT JOIN users u ON p.author_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.slug = ? AND p.status = 'published'
");
$stmt->execute([$slug]);
$post = $stmt->fetch();

// If post not found, show 404
if (!$post) {
    header("HTTP/1.0 404 Not Found");
    require_once __DIR__ . '/404.php';
    exit;
}

// Update view count
$update_views = $pdo->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?");
$update_views->execute([$post['id']]);

// Set page title
$page_title = $post['title'];

// Include navigation
require_once __DIR__ . '/includes/templates/navigation.php';

// Check if image exists in uploads or assets
$has_image = false;
$post_image = '';

if ($post['featured_image']) {
    // First check uploads folder
    if (file_exists(UPLOAD_PATH . 'blog/' . $post['featured_image'])) {
        $has_image = true;
        $post_image = UPLOAD_URL . '/blog/' . htmlspecialchars($post['featured_image']);
    }
    // Then check assets folder
    elseif (file_exists(ROOT_PATH . '/assets/images/' . $post['featured_image'])) {
        $has_image = true;
        $post_image = ASSETS_URL . '/images/' . htmlspecialchars($post['featured_image']);
    }
}

// Get related posts (same category, excluding current)
$related_posts = [];
if ($post['category_id']) {
    $related_stmt = $pdo->prepare("
        SELECT p.*, u.full_name as author_name
        FROM blog_posts p
        LEFT JOIN users u ON p.author_id = u.id
        WHERE p.category_id = ? AND p.id != ? AND p.status = 'published'
        ORDER BY p.published_at DESC
        LIMIT 3
    ");
    $related_stmt->execute([$post['category_id'], $post['id']]);
    $related_posts = $related_stmt->fetchAll();
}
?>

<main>
    <section id="blogSingleSection">
        <div class="container">
            <!-- Breadcrumb -->
            <div class="breadcrumb-nav">
                <a href="<?php echo SITE_URL; ?>">Home</a>
                <span>/</span>
                <a href="<?php echo SITE_URL; ?>/blogs.php">Blogs</a>
                <span>/</span>
                <span><?php echo htmlspecialchars($post['title']); ?></span>
            </div>

            <!-- Post Header -->
            <article class="blog-single">
                <div class="blog-hero">
                    <?php if ($post['category_name']): ?>
                        <span class="blog-category"
                            style="display: inline-block; background: #E90330; color: white; padding: 5px 15px; border-radius: 20px; margin-bottom: 15px; font-size: 14px;">
                            <?php echo htmlspecialchars($post['category_name']); ?>
                        </span>
                    <?php endif; ?>

                    <h1 class="blog-title" style="font-size: 2.5em; margin: 10px 0;">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </h1>

                    <div class="blog-meta"
                        style="display: flex; gap: 20px; margin: 15px 0; color: #666; font-size: 14px;">
                        <span>
                            <strong>By:</strong> <?php echo htmlspecialchars($post['author_name'] ?? 'Admin'); ?>
                        </span>
                        <span>
                            <strong>Date:</strong>
                            <?php echo formatDate($post['published_at'] ?? $post['created_at'], 'F j, Y'); ?>
                        </span>
                        <span>
                            <strong>Views:</strong> <?php echo number_format($post['views']); ?>
                        </span>
                    </div>
                </div>

                <!-- Featured Image -->
                <?php if ($post_image): ?>
                    <div class="blog-single-image">
                        <img src="<?php echo $post_image; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                    </div>
                <?php endif; ?>

                <!-- Post Content -->
                <div class="blog-single-content">
                    <?php if ($post['excerpt']): ?>
                        <div class="blog-excerpt-large">
                            <?php echo nl2br(htmlspecialchars($post['excerpt'])); ?>
                        </div>
                    <?php endif; ?>

                    <?php echo $post['content']; // Content from TinyMCE is already HTML ?>
                </div>

                <!-- Author Box -->
                <?php if ($post['author_name']): ?>
                    <div
                        style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin: 40px 0; display: flex; align-items: center; gap: 20px;">
                        <div
                            style="width: 60px; height: 60px; border-radius: 50%; background: #E90330; color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold;">
                            <?php echo strtoupper(substr($post['author_name'], 0, 1)); ?>
                        </div>
                        <div>
                            <h4 style="margin: 0 0 5px 0;">Written by <?php echo htmlspecialchars($post['author_name']); ?>
                            </h4>
                            <?php if ($post['author_bio']): ?>
                                <p style="margin: 0; color: #666;"><?php echo htmlspecialchars($post['author_bio']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Share Buttons -->
                <div class="share-section" style="margin: 40px 0; text-align: center;">
                    <h4 style="margin-bottom: 20px; color: #333;">Share this post:</h4>
                    <style>
                        .share-icons {
                            display: flex;
                            gap: 16px;
                            justify-content: center;
                        }

                        .share-icons .share-icon {
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            width: 44px;
                            height: 44px;
                            border-radius: 12px;
                            background: rgba(0, 0, 0, 0.05);
                            border: 1px solid rgba(0, 0, 0, 0.1);
                            color: #666;
                            font-size: 20px;
                            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                            text-decoration: none;
                        }

                        .share-icons .share-icon i {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .share-icons .share-icon:hover {
                            transform: translateY(-5px) scale(1.05);
                            color: #fff;
                            border-color: transparent;
                        }

                        .share-icons .share-icon.facebook:hover {
                            background: linear-gradient(135deg, #1877f2 0%, #0d5ed8 100%);
                            box-shadow: 0 8px 25px rgba(24, 119, 242, 0.4);
                        }

                        .share-icons .share-icon.twitter:hover {
                            background: linear-gradient(135deg, #1da1f2 0%, #0c85d0 100%);
                            box-shadow: 0 8px 25px rgba(29, 161, 242, 0.4);
                        }

                        .share-icons .share-icon.linkedin:hover {
                            background: linear-gradient(135deg, #0077b5 0%, #005a8a 100%);
                            box-shadow: 0 8px 25px rgba(0, 119, 181, 0.4);
                        }
                    </style>
                    <div class="share-icons">
                        <a href="https://facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/blog/' . $post['slug']); ?>"
                            target="_blank" title="Share on Facebook" class="share-icon facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(SITE_URL . '/blog/' . $post['slug']); ?>&text=<?php echo urlencode($post['title']); ?>"
                            target="_blank" title="Share on Twitter" class="share-icon twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(SITE_URL . '/blog/' . $post['slug']); ?>"
                            target="_blank" title="Share on LinkedIn" class="share-icon linkedin">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Related Posts -->
            <?php if (count($related_posts) > 0): ?>
                <section class="related-posts">
                    <h2>Related Posts</h2>
                    <div class="blog-grid">
                        <?php
                        foreach ($related_posts as $related):
                            // Check if image exists in uploads or assets
                            $has_related_image = false;
                            $related_image = '';

                            if ($related['featured_image']) {
                                // First check uploads folder
                                if (file_exists(UPLOAD_PATH . 'blog/' . $related['featured_image'])) {
                                    $has_related_image = true;
                                    $related_image = UPLOAD_URL . '/blog/' . htmlspecialchars($related['featured_image']);
                                }
                                // Then check assets folder
                                elseif (file_exists(ROOT_PATH . '/assets/images/' . $related['featured_image'])) {
                                    $has_related_image = true;
                                    $related_image = ASSETS_URL . '/images/' . htmlspecialchars($related['featured_image']);
                                }
                            }
                            ?>
                            <div class="blog-card">
                                <?php if ($has_related_image): ?>
                                    <img class="blog-image" src="<?php echo $related_image; ?>"
                                        alt="<?php echo htmlspecialchars($related['title']); ?>">
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
                                    "><?php echo strtoupper(substr($related['title'], 0, 1)); ?></div>
                                <?php endif; ?>

                                <div class="blog-content">
                                    <h3><?php echo htmlspecialchars($related['title']); ?></h3>
                                    <p><?php echo formatDate($related['published_at'] ?? $related['created_at'], 'M j, Y'); ?>
                                    </p>
                                    <a href="<?php echo SITE_URL; ?>/blog/<?php echo htmlspecialchars($related['slug']); ?>">
                                        <button class="btn">
                                            Read More <img src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" alt="Arrow" />
                                        </button>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Back to Blogs -->
            <div style="text-align: center; margin: 50px 0;">
                <a href="<?php echo SITE_URL; ?>/blogs.php" class="btn"
                    style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-arrow-left"></i> Back to All Posts
                </a>
            </div>
        </div>
    </section>
</main>

<?php
// Add Font Awesome for social icons
$additional_css = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';

require_once __DIR__ . '/includes/templates/footer.php';
?>