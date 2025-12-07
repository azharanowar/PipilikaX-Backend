<?php
/**
 * 404 Error Page - PipilikaX
 * Handles page not found errors gracefully
 */

// Only include config if constants haven't been defined yet
if (!defined('SITE_URL')) {
    require_once __DIR__ . '/config/constants.php';
}

// Set HTTP status code
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Page Not Found - PipilikaX</title>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="<?php echo ASSETS_URL; ?>/images/pipilika-favicon.png">
    <meta name="theme-color" content="#E90330" />
    <link href="<?php echo ASSETS_URL; ?>/css/styles.css" rel="stylesheet" />
</head>

<body>
    <header>
        <div id="headerSection" class="header-inner">
            <!-- Logo -->
            <div class="logo-container">
                <a href="<?php echo SITE_URL; ?>" class="logo">
                    <img id="logoImage" src="<?php echo ASSETS_URL; ?>/images/pipilika-logo.png" alt="PipilikaX Logo" />
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="desktop-nav">
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/blogs">Blogs</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/about">About Us</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/contact">Contact Us</a></li>
                </ul>
            </nav>

            <!-- Desktop CTA Button -->
            <div class="desktop-btn">
                <a href="#"><button class="btn">Join Now <img
                            src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" /></button></a>
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
                <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                <li><a href="<?php echo SITE_URL; ?>/blogs">Blogs</a></li>
                <li><a href="<?php echo SITE_URL; ?>/about">About Us</a></li>
                <li><a href="<?php echo SITE_URL; ?>/contact">Contact Us</a></li>
                <li><a href="#"><button class="btn">Join Now <img
                                src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" /></button></a>
                </li>
            </ul>
        </div>
    </header>



    <main class="error-wrapper">
        <section class="error-page">
            <div class="bg-scroll"></div>
            <div class="error-content">
                <div>
                    <h1>Error: 404</h1>
                    <p>Oops! You seem lost in space.</p>
                    <a href="<?php echo SITE_URL; ?>"><button class="btn">Back to Home <img
                                src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" /></button></a>
                </div>
                <img src="<?php echo ASSETS_URL; ?>/images/era.png" class="floating-image" alt="Lost in Space" />
            </div>
        </section>
    </main>
    <footer>
        <a href="https://github.com/azharanowar/pipilikaX" target="_blank"><strong>PipilikaX</strong></a> || Copyright ©
        2025 – All rights reserved.
    </footer>

    <div class="cursor-dot"></div>
    <div class="cursor-ring"></div>
    <button id="scrollToTopBtn" title="Go to top"><img
            src="<?php echo ASSETS_URL; ?>/images/scroll-to-top.gif" /></button>
    <script src="<?php echo ASSETS_URL; ?>/js/scripts.js"></script>
</body>

</html>