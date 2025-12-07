<?php
/**
 * Footer Template
 * PipilikaX Frontend
 */

// Get footer settings
$footer_brand = getSetting('footer_brand_name', 'PipilikaX');
$footer_copyright = getSetting('footer_copyright', 'Copyright © ' . date('Y') . ' – All rights reserved.');
$footer_github = getSetting('footer_github_url', 'https://github.com/azharanowar/pipilikaX');

// Get social media URLs
$social_facebook = getSetting('facebook_url', '#');
$social_twitter = getSetting('twitter_url', '#');
$social_linkedin = getSetting('linkedin_url', '#');
$social_github = getSetting('github_url', 'https://github.com/azharanowar/pipilikaX');
?>

<footer>
    <div class="footer-content">
        <div class="footer-brand">
            <a href="<?php echo htmlspecialchars($footer_github); ?>" target="_blank">
                <strong><?php echo htmlspecialchars($footer_brand); ?></strong>
            </a> || <?php echo htmlspecialchars($footer_copyright); ?>
        </div>

        <div class="footer-social">
            <style>
                .footer-social {
                    display: flex;
                    gap: 16px;
                    align-items: center;
                    justify-content: center;
                }
                .footer-social .social-icon {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 44px;
                    height: 44px;
                    border-radius: 12px;
                    background: rgba(255, 255, 255, 0.08);
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                    border: 1px solid rgba(255, 255, 255, 0.15);
                    color: rgba(255, 255, 255, 0.9);
                    font-size: 20px;
                    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                    text-decoration: none;
                }
                .footer-social .social-icon i {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .footer-social .social-icon:hover {
                    transform: translateY(-5px) scale(1.05);
                    color: #fff;
                    border-color: transparent;
                }
                .footer-social .social-icon.github:hover {
                    background: linear-gradient(135deg, #333 0%, #24292e 100%);
                    box-shadow: 0 8px 25px rgba(36, 41, 46, 0.5);
                }
                .footer-social .social-icon.facebook:hover {
                    background: linear-gradient(135deg, #1877f2 0%, #0d5ed8 100%);
                    box-shadow: 0 8px 25px rgba(24, 119, 242, 0.5);
                }
                .footer-social .social-icon.twitter:hover {
                    background: linear-gradient(135deg, #1da1f2 0%, #0c85d0 100%);
                    box-shadow: 0 8px 25px rgba(29, 161, 242, 0.5);
                }
                .footer-social .social-icon.linkedin:hover {
                    background: linear-gradient(135deg, #0077b5 0%, #005a8a 100%);
                    box-shadow: 0 8px 25px rgba(0, 119, 181, 0.5);
                }
            </style>

            <?php if ($social_github && $social_github !== '#'): ?>
                <a href="<?php echo htmlspecialchars($social_github); ?>" target="_blank" title="GitHub"
                    class="social-icon github">
                    <i class="fab fa-github"></i>
                </a>
            <?php endif; ?>

            <?php if ($social_facebook && $social_facebook !== '#'): ?>
                <a href="<?php echo htmlspecialchars($social_facebook); ?>" target="_blank" title="Facebook"
                    class="social-icon facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
            <?php endif; ?>

            <?php if ($social_twitter && $social_twitter !== '#'): ?>
                <a href="<?php echo htmlspecialchars($social_twitter); ?>" target="_blank" title="Twitter"
                    class="social-icon twitter">
                    <i class="fab fa-twitter"></i>
                </a>
            <?php endif; ?>

            <?php if ($social_linkedin && $social_linkedin !== '#'): ?>
                <a href="<?php echo htmlspecialchars($social_linkedin); ?>" target="_blank" title="LinkedIn"
                    class="social-icon linkedin">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</footer>

<!-- Custom Cursor -->
<div class="cursor-dot"></div>
<div class="cursor-ring"></div>

<!-- Scroll to Top Button -->
<button id="scrollToTopBtn" title="Go to top">
    <img src="<?php echo ASSETS_URL; ?>/images/scroll-to-top.gif" alt="Scroll to top" />
</button>

<!-- JavaScript -->
<script src="<?php echo ASSETS_URL; ?>/js/scripts.js"></script>

<?php if (isset($additional_js)): ?>
    <?php echo $additional_js; ?>
<?php endif; ?>

</body>

</html>