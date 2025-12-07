// Dynamic logo paths (can be updated from PHP)
let logoDefault = 'assets/images/pipilika-logo.png';
let logoWhite = 'assets/images/pipilika-logo-main-white.png';

// Function to update logo sources from PHP
function updateLogoSources(defaultPath, whitePath) {
  logoDefault = defaultPath;
  logoWhite = whitePath;
}

// Header scroll handling with anti-flicker mechanism
let isHeaderFixed = false;
let headerPlaceholder = null;

window.addEventListener('scroll', function () {
  const header = document.getElementById('headerSection');
  const logo = document.getElementById('logoImage');
  const spans = document.querySelectorAll('.menu-toggle span');

  // Use different thresholds for adding vs removing to prevent flickering
  const scrollThreshold = 50;
  const scrollBuffer = 10; // Buffer zone to prevent rapid toggling

  const shouldBeFixed = window.scrollY > scrollThreshold;
  const shouldBeStatic = window.scrollY < (scrollThreshold - scrollBuffer);

  if (shouldBeFixed && !isHeaderFixed) {
    // Create placeholder to prevent layout shift
    if (!headerPlaceholder) {
      headerPlaceholder = document.createElement('div');
      headerPlaceholder.id = 'header-placeholder';
      headerPlaceholder.style.height = header.offsetHeight + 'px';
      headerPlaceholder.style.width = '100%';
      header.parentNode.insertBefore(headerPlaceholder, header);
    }

    header.classList.add('header-scrolled');
    logo.src = logoWhite;
    spans.forEach(span => span.classList.add('hamburger-white'));
    isHeaderFixed = true;

  } else if (shouldBeStatic && isHeaderFixed) {
    // Remove placeholder
    if (headerPlaceholder) {
      headerPlaceholder.remove();
      headerPlaceholder = null;
    }

    header.classList.remove('header-scrolled');
    logo.src = logoDefault;
    spans.forEach(span => span.classList.remove('hamburger-white'));
    isHeaderFixed = false;
  }
});


// Menu open/close
const menuToggle = document.getElementById('menuToggle');
const fullScreenMenu = document.getElementById('fullScreenMenu');
const closeMenu = document.getElementById('closeMenu');

menuToggle.addEventListener('click', function () {
  fullScreenMenu.classList.add('active');
  document.body.style.overflow = 'hidden';
});

closeMenu.addEventListener('click', function () {
  fullScreenMenu.classList.remove('active');
  document.body.style.overflow = 'auto';
});



const scrollToTopBtn = document.getElementById("scrollToTopBtn");

window.addEventListener("scroll", () => {
  if (window.scrollY > 300) {
    scrollToTopBtn.classList.add("show");
  } else {
    scrollToTopBtn.classList.remove("show");
  }
});

scrollToTopBtn.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
});



const dot = document.querySelector('.cursor-dot');
const ring = document.querySelector('.cursor-ring');

let mouseX = 0, mouseY = 0;
let ringX = 0, ringY = 0;
const speed = 0.3; // Adjust for more/less lagging (0.1 = smooth, 1 = instant)

if (window.innerWidth > 768) {
  document.addEventListener('mousemove', (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    dot.style.top = `${mouseY}px`;
    dot.style.left = `${mouseX}px`;
  });

  function animateRing() {
    ringX += (mouseX - ringX) * speed;
    ringY += (mouseY - ringY) * speed;
    ring.style.top = `${ringY}px`;
    ring.style.left = `${ringX}px`;
    requestAnimationFrame(animateRing);
  }

  animateRing();
} else {
  dot.style.display = 'none';
  ring.style.display = 'none';
}