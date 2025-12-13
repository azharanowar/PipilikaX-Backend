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

// Get logo settings
$site_logo = getSetting('site_logo', '');
$site_logo_white = getSetting('site_logo_white', '');
$site_name = getSetting('site_name', 'PipilikaX');

// Determine logo URLs - check uploads first, then assets
$logo_url = null;
$logo_white_url = null;

if ($site_logo) {
    if (file_exists(UPLOAD_PATH . 'settings/' . $site_logo)) {
        $logo_url = UPLOAD_URL . '/settings/' . $site_logo;
    } elseif (file_exists(ROOT_PATH . '/assets/images/' . $site_logo)) {
        $logo_url = ASSETS_URL . '/images/' . $site_logo;
    }
}

if ($site_logo_white) {
    if (file_exists(UPLOAD_PATH . 'settings/' . $site_logo_white)) {
        $logo_white_url = UPLOAD_URL . '/settings/' . $site_logo_white;
    } elseif (file_exists(ROOT_PATH . '/assets/images/' . $site_logo_white)) {
        $logo_white_url = ASSETS_URL . '/images/' . $site_logo_white;
    }
}

// Fallback: if no white logo, use main logo; if no logo at all, will show text
if (!$logo_white_url) {
    $logo_white_url = $logo_url;
}

// Determine current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header>
    <div id="headerSection" class="header-inner">
        <!-- Logo -->
        <div class="logo-container">
            <a href="<?php echo SITE_URL; ?>" class="logo">
                <?php if ($logo_url): ?>
                    <img id="logoImage" src="<?php echo htmlspecialchars($logo_url); ?>" alt="<?php echo htmlspecialchars($site_name); ?> Logo" />
                <?php else: ?>
                    <span style="font-size: 24px; font-weight: 700; color: var(--main-color);"><?php echo htmlspecialchars($site_name); ?></span>
                <?php endif; ?>
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
    <?php if ($logo_url): ?>
    window.pipilikaLogoDefault = '<?php echo htmlspecialchars($logo_url); ?>';
    window.pipilikaLogoWhite = '<?php echo htmlspecialchars($logo_white_url ?: $logo_url); ?>';
    <?php else: ?>
    window.pipilikaLogoDefault = null;
    window.pipilikaLogoWhite = null;
    <?php endif; ?>
    
    // Initialize logo sources when DOM is ready and scripts.js is loaded
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof updateLogoSources === 'function' && window.pipilikaLogoDefault) {
            updateLogoSources(window.pipilikaLogoDefault, window.pipilikaLogoWhite);
        }
    });
</script>
