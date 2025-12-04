<?php
/**
 * Footer Template
 * PipilikaX Frontend
 */

// Get footer settings
$footer_brand = getSetting('footer_brand_name', 'PipilikaX');
$footer_copyright = getSetting('footer_copyright', 'Copyright © ' . date('Y') . ' – All rights reserved.');
$footer_github = getSetting('footer_github_url', 'https://github.com/azharanowar/pipilikaX');
?>

<footer>
    <a href="<?php echo htmlspecialchars($footer_github); ?>" target="_blank">
        <strong><?php echo htmlspecialchars($footer_brand); ?></strong>
    </a> || <?php echo htmlspecialchars($footer_copyright); ?>
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