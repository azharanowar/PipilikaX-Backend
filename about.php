<?php
/**
 * About Us Page - PipilikaX
 * Dynamic version with team members from database
 */

$page_title = 'About Us';

// Include header and navigation
require_once __DIR__ . '/includes/templates/header.php';
require_once __DIR__ . '/includes/templates/navigation.php';

// Get about page content from settings
$about_intro = getSetting('about_page_subtitle', 'We are a passionate team dedicated to creating cutting-edge AI solutions that impact the future.');
$about_title = getSetting('about_title', 'Get to know PipilikaX');
$about_content = getSetting('about_text');

// Get active team members
$team = $pdo->query("SELECT * FROM active_team")->fetchAll();
?>

<main class="about-section">
    <!-- Intro Section -->
    <section class="intro">
        <h1>About Us</h1>
        <p class="subtitle"><?php echo htmlspecialchars($about_intro); ?></p>
    </section>

    <!-- About Content Section -->
    <section id="aboutSection" style="background-color: white; padding: 70px 10vw;">
        <div class="about-container">
            <div class="about-image">
                <img class="floating-image" src="<?php echo ASSETS_URL; ?>/images/space-man-img.png"
                    alt="About PipilikaX" width="400">
            </div>
            <div class="about-content">
                <h2><?php echo htmlspecialchars($about_title); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($about_content)); ?></p>
                <a href="#">
                    <button class="btn">
                        Join the Journey Now
                        <img src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" alt="Arrow" />
                    </button>
                </a>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <?php if (count($team) > 0): ?>
        <section class="team-section">
            <h2>Meet Our Team</h2>
            <div class="team-grid">
                <?php
                foreach ($team as $member):
                    // Determine image source - check uploads first, then assets folder (like posts)
                    $team_image = null;
                    if ($member['photo']) {
                        if (file_exists(UPLOAD_PATH . 'team/' . $member['photo'])) {
                            $team_image = UPLOAD_URL . '/team/' . htmlspecialchars($member['photo']);
                        } elseif (file_exists(ROOT_PATH . '/assets/images/' . $member['photo'])) {
                            $team_image = ASSETS_URL . '/images/' . htmlspecialchars($member['photo']);
                        }
                    }
                    ?>
                    <div class="team-member">
                        <?php if ($team_image): ?>
                            <img class="team-img" src="<?php echo $team_image; ?>"
                                alt="<?php echo htmlspecialchars($member['name']); ?> Photo">
                        <?php else: ?>
                            <div class="team-img team-img-placeholder"
                                style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%); color: #999; font-size: 48px;">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>

                        <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                        <p><?php echo htmlspecialchars($member['position']); ?></p>

                        <div class="team-social">
                            <?php if ($member['facebook_url'] && $member['facebook_url'] != '#'): ?>
                                <a href="<?php echo htmlspecialchars($member['facebook_url']); ?>" target="_blank"
                                    class="social-icon facebook" aria-label="Facebook">
                                    <img src="<?php echo ASSETS_URL; ?>/images/facebook.svg" alt="Facebook" />
                                </a>
                            <?php endif; ?>

                            <?php if ($member['linkedin_url'] && $member['linkedin_url'] != '#'): ?>
                                <a href="<?php echo htmlspecialchars($member['linkedin_url']); ?>" target="_blank"
                                    class="social-icon linkedin" aria-label="LinkedIn">
                                    <img src="<?php echo ASSETS_URL; ?>/images/linkedin.svg" alt="LinkedIn" />
                                </a>
                            <?php endif; ?>

                            <?php if ($member['twitter_url'] && $member['twitter_url'] != '#'): ?>
                                <a href="<?php echo htmlspecialchars($member['twitter_url']); ?>" target="_blank"
                                    class="social-icon twitter" aria-label="Twitter">
                                    <img src="<?php echo ASSETS_URL; ?>/images/twitter.svg" alt="Twitter" />
                                </a>
                            <?php endif; ?>

                            <?php if ($member['github_url'] && $member['github_url'] != '#'): ?>
                                <a href="<?php echo htmlspecialchars($member['github_url']); ?>" target="_blank"
                                    class="social-icon github" aria-label="GitHub">
                                    <img src="<?php echo ASSETS_URL; ?>/images/github.svg" alt="GitHub" />
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/templates/footer.php'; ?>