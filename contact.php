<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>PipilikaX - Your Space for AutoNotes</title>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/images/pipilika-favicon.png">
    <meta name="theme-color" content="#E90330" />
    <link href="assets/css/styles.css" rel="stylesheet" />
    <style>

    </style>
</head>

<body>
    <header>
        <div id="headerSection" class="header-inner">
            <!-- Logo -->
            <div class="logo-container">
                <a href="index.html" class="logo">
                    <img id="logoImage" src="assets/images/pipilika-logo.png" alt="PipilikaX Logo" />
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="desktop-nav">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="blogs.html">Blogs</a></li>
                    <li><a href="about-us.html">About Us</a></li>
                    <li><a href="contact-us.html" class="active">Contact Us</a></li>
                </ul>
            </nav>

            <!-- Desktop CTA Button -->
            <div class="desktop-btn">
                <a href="#"><button class="btn">Join Now <img src="assets/images/arrow-white.svg" /></button></a>
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
                <li><a href="index.html">Home</a></li>
                <li><a href="blogs.html">Blogs</a></li>
                <li><a href="about-us.html">About Us</a></li>
                <li><a href="contact-us.html" class="active">Contact Us</a></li>
                <li><a href="#"><button class="btn">Join Now <img src="assets/images/arrow-white.svg" /></button></a>
                </li>
            </ul>
        </div>
    </header>



    <main class="contact-section">
    <h1>Contact Us</h1>
    <p class="subtitle">We'd love to hear from you. Let's get in touch!</p>

    <div class="contact-wrapper">
      <div class="contact-info">
        <h2>Our Location</h2>
        <p><strong>Address:</strong> Dong-eui University, Intelligent Computing Department(International Students), 3rd Semester</p>
        <p><strong>Email:</strong> contact@pipilikaX.com</p>
        <p><strong>Phone:</strong> 010-5149-3665</p>
      </div>

      <form class="contact-form">
        <h2>Send a Message</h2>
        <input type="text" placeholder="Your Name" required />
        <input type="email" placeholder="Your Email" required />
        <textarea placeholder="Your Message" rows="5" required></textarea>
        <a href="#"><button class="btn">Join Now <img src="assets/images/arrow-white.svg" /></button></a>
      </form>
    </div>

    <div class="map-container">
      <h2>Find Us Here</h2>
      <iframe
        src="https://maps.google.com/maps?q=busan&t=k&z=13&ie=UTF8&iwloc=&output=embed"
        frameborder="0"
        allowfullscreen
        loading="lazy"
      ></iframe>
    </div>
  </main>
    <footer>
        <a href="https://github.com/azharanowar/pipilikaX" target="_blank"><strong>PipilikaX</strong></a> || Copyright ©
        2025 – All rights reserved.
    </footer>

    <div class="cursor-dot"></div>
<div class="cursor-ring"></div>
<button id="scrollToTopBtn" title="Go to top"><img src="assets/images/scroll-to-top.gif"/></button>
<script src="assets/js/scripts.js"></script>
</body>
</html>