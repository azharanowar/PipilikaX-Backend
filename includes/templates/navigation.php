<?php
/**
 * Navigation Template
 * PipilikaX Frontend
 */

// Get navigation items from database (including is_cta)
$nav_items_query = $pdo->query("SELECT * FROM navigation_menu WHERE is_active = 1 ORDER BY display_order ASC");
$nav_items = $nav_items_query->fetchAll();

// Separate regular items and CTA items
$regular_items = array_filter($nav_items, fn($item) => !$item['is_cta']);
$cta_items = array_filter($nav_items, fn($item) => $item['is_cta']);

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
                <?php foreach ($regular_items as $item): ?>
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

        <!-- Desktop CTA Button(s) -->
        <?php if (!empty($cta_items)): ?>
            <div class="desktop-btn">
                <?php foreach ($cta_items as $cta): ?>
                    <a href="<?php echo htmlspecialchars($cta['url']); ?>" <?php if ($cta['target'] != '_self') echo 'target="' . htmlspecialchars($cta['target']) . '"'; ?>>
                        <button class="btn"><?php echo htmlspecialchars($cta['title']); ?> <img src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" /></button>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

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
            <?php foreach ($regular_items as $item): ?>
                <li>
                    <a href="<?php echo SITE_URL . '/' . htmlspecialchars($item['url']); ?>" 
                       <?php if ($current_page == $item['url']) echo 'class="active"'; ?>
                       <?php if ($item['target'] != '_self') echo 'target="' . htmlspecialchars($item['target']) . '"'; ?>>
                        <?php echo htmlspecialchars($item['title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <?php foreach ($cta_items as $cta): ?>
                <li>
                    <a href="<?php echo htmlspecialchars($cta['url']); ?>" <?php if ($cta['target'] != '_self') echo 'target="' . htmlspecialchars($cta['target']) . '"'; ?>>
                        <button class="btn"><?php echo htmlspecialchars($cta['title']); ?> <img src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" /></button>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</header>

<script>
    // Store logo URLs for JavaScript - will be used after scripts.js loads
    window.pipilikaLogoDefault = '<?php echo ASSETS_URL; ?>/images/<?php echo htmlspecialchars($site_logo); ?>';
    window.pipilikaLogoWhite = '<?php echo ASSETS_URL; ?>/images/<?php echo htmlspecialchars($site_logo_white); ?>';
    
    // Initialize logo sources when DOM is ready and scripts.js is loaded
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof updateLogoSources === 'function') {
            updateLogoSources(window.pipilikaLogoDefault, window.pipilikaLogoWhite);
        }
    });
</script>
