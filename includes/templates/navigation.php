<?php
/**
 * Navigation Template
 * PipilikaX Frontend
 */

// Get navigation items from database
$nav_items_query = $pdo->query("SELECT * FROM active_navigation ORDER BY display_order ASC");
$nav_items = $nav_items_query->fetchAll();

// Get logo
$site_logo = getSetting('site_logo', 'pipilika-logo.png');
$site_logo_white = getSetting('site_logo_white', 'pipilika-logo-main-white.png');

// Determine current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header>
    <div id="headerSection" class="header-inner">
        <!-- Logo -->
        <div class="logo-container">
            <a href="<?php echo SITE_URL; ?>" class="logo">
                <img id="logoImage" src="<?php echo ASSETS_URL; ?>/images/<?php echo htmlspecialchars($site_logo); ?>" alt="<?php echo htmlspecialchars(getSetting('site_name', 'PipilikaX')); ?> Logo" />
            </a>
        </div>

        <!-- Desktop Navigation -->
        <nav class="desktop-nav">
            <ul>
                <?php foreach ($nav_items as $item): ?>
                    <li>
                        <a href="<?php echo SITE_URL . '/' . htmlspecialchars($item['url']); ?>" 
                           <?php if ($current_page == $item['url']) echo 'class="active"'; ?>
                           <?php if ($item['target'] != '_self') echo 'target="' . htmlspecialchars($item['target']) . '"'; ?>>
                            <?php echo htmlspecialchars($item['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <!-- Desktop CTA Button -->
        <div class="desktop-btn">
            <a href="#"><button class="btn">Join Now <img src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" /></button></a>
        </div>

        <!-- Hamburger Icon -->
        <div id="menuToggle" class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <!-- Fullscreen Popup Menu -->
    <div id="fullScreenMenu" class="full-screen-menu">
        <div class="close-btn" id="closeMenu">&times;</div>
        <ul>
            <?php foreach ($nav_items as $item): ?>
                <li>
                    <a href="<?php echo SITE_URL . '/' . htmlspecialchars($item['url']); ?>" 
                       <?php if ($current_page == $item['url']) echo 'class="active"'; ?>
                       <?php if ($item['target'] != '_self') echo 'target="' . htmlspecialchars($item['target']) . '"'; ?>>
                        <?php echo htmlspecialchars($item['title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li>
                <a href="#"><button class="btn">Join Now <img src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" /></button></a>
            </li>
        </ul>
    </div>
</header>

<script>
    // Update logo source for white version
    window.addEventListener('DOMContentLoaded', function() {
        const logoWhite = '<?php echo ASSETS_URL; ?>/images/<?php echo htmlspecialchars($site_logo_white); ?>';
        const logoDefault = '<?php echo ASSETS_URL; ?>/images/<?php echo htmlspecialchars($site_logo); ?>';
        
        // Update the script.js to use these variables
        if (typeof updateLogoSources === 'function') {
            updateLogoSources(logoDefault, logoWhite);
        }
    });
</script>
