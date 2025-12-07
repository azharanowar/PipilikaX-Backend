<?php
/**
 * Homepage - PipilikaX
 * Dynamic version using template system
 */

// Set page title
$page_title = 'Home';

// Include header and navigation
require_once __DIR__ . '/includes/templates/header.php';
require_once __DIR__ . '/includes/templates/navigation.php';

// Get dynamic content from settings
$hero_welcome = getSetting('hero_welcome_title', 'Welcome to PipilikaX');
$hero_intro = getSetting('hero_intro_text', 'Adventures beyond the stars, adrenaline that defies gravity – welcome to:');
$hero_cta = getSetting('hero_cta_text', 'Join the Journey');
$hero_cta_url = getSetting('hero_cta_url', '#');

// Get hero phrases for typing animation
$hero_phrases = [];
for ($i = 1; $i <= 5; $i++) {
    $phrase = getSetting("hero_phrase_$i");
    if ($phrase) {
        $hero_phrases[] = $phrase;
    }
}

// Get about section content
$about_title = getSetting('about_title', 'Get to know PipilikaX');
$about_text = getSetting('about_text');
$about_cta = getSetting('about_cta_text', 'Join the Journey Now');
?>

<main>
    <!-- Hero Section -->
    <section id="heroSection">
        <canvas id="starfield"></canvas>

        <div id="heroContainer" class="container">
            <div class="hero-wrapper">
                <div class="hero-content">
                    <h2><?php echo htmlspecialchars($hero_welcome); ?></h2>
                    <p>
                        <?php echo nl2br(htmlspecialchars($hero_intro)); ?>
                        <strong><span id="typed-text"></span></strong>
                    </p>
                    <a href="<?php echo htmlspecialchars($hero_cta_url); ?>">
                        <button class="btn">
                            <?php echo htmlspecialchars($hero_cta); ?>
                            <img src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" />
                        </button>
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="<?php echo ASSETS_URL; ?>/images/astronaut-image.png" alt="Space man image floating" />
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="aboutSection">
        <div class="about-container">
            <div class="about-image">
                <img class="floating-image" src="<?php echo ASSETS_URL; ?>/images/space-man-img.png"
                    alt="About PipilikaX" width="400">
            </div>
            <div class="about-content">
                <h2><?php echo htmlspecialchars($about_title); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($about_text)); ?></p>
                <a href="#">
                    <button class="btn">
                        <?php echo htmlspecialchars($about_cta); ?>
                        <img src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" />
                    </button>
                </a>
            </div>
        </div>
    </section>

    <!-- Planet Names Section (Static) -->
    <section id="planetNamesSection">
        <div>
            <h3>Know YOUR solar system.</h3>
            <p>hope your'e on earth tho..</p>
            <img src="<?php echo ASSETS_URL; ?>/images/planet-names.svg" alt="Planet names svg image" />
            <p>Yeah, and the <strong>`Sun`</strong> too</p>
        </div>
    </section>

    <!-- Image of the Day Section (Static for now) -->
    <section id="imageOfTheDaySection">
        <div class="about-container">
            <div class="about-content image-of-the-day-content">
                <p class="spacing-letter-text">TODAY</p>
                <h2>Image Of The Day</h2>
                <p>
                    <strong>Webb Sees Sombrero Galaxy in Near-Infrared</strong>
                    NASA's James Webb Space Telescope recently imaged the Sombrero Galaxy with its NIRCam
                    (Near-Infrared Camera), which
                    shows dust from the galaxy's outer ring blocking stellar light from stars within the galaxy. In
                    the central region
                    of the galaxy, the roughly 2,000 globular clusters, or collections of hundreds of thousands of
                    old stars held
                    together by gravity, glow in the near-infrared.
                    <br><br>The Sombrero Galaxy is around 30 million light-years from Earth in
                    the constellation Virgo. From Earth, we see this galaxy nearly "edge-on," or from the side.
                </p>
                <a href="https://www.nasa.gov/image-of-the-day/" target="_blank">
                    <button class="btn">Browse Image Archive <img
                            src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" /></button>
                </a>
            </div>
            <div class="about-image image-of-the-day">
                <img src="<?php echo ASSETS_URL; ?>/images/image-of-the-day.jpg" alt="NASA image of the day"
                    width="400">
            </div>
        </div>
    </section>

    <!-- Engine Section (Static) -->
    <section id="engineSection">
        <div class="engine-content">
            <h3 class="engine-title">Pipilika X Engine</h3>
            <div class="engines-slide-content">
                <h4 class="spacing-letter-text">Features</h4>
                <div class="engines-slide">
                    <div class="engines-slide-text">Live Space Feeds</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Real-Time Earth Data</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Satellite Mapping</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Climate Analytics</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Planetary Info</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">AI Predictions</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Data Visualization</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Interactive UI</div>
                    <div class="dot"></div>

                    <!-- Duplicate for infinite loop -->
                    <div class="engines-slide-text">Live Space Feeds</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Real-Time Earth Data</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Satellite Mapping</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Climate Analytics</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Planetary Info</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">AI Predictions</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Data Visualization</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Interactive UI</div>
                    <div class="dot"></div>
                </div>
            </div>
            <div class="engines-slide-content reverse">
                <h4 class="spacing-letter-text">Technology Used</h4>
                <div class="engines-slide reverse-track">
                    <div class="engines-slide-text">HTML5</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">CSS3</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">JavaScript ES6</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">DOM Manipulation</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Animation</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Graphics Design</div>
                    <div class="dot"></div>

                    <!-- Duplicate for infinite loop -->
                    <div class="engines-slide-text">HTML5</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">CSS3</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">JavaScript ES6</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">DOM Manipulation</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Animation</div>
                    <div class="dot"></div>
                    <div class="engines-slide-text">Graphics Design</div>
                    <div class="dot"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ISS Tracker Section (Static) -->
    <section id="satelliteTracker">
        <div class="tracker-container">
            <div class="tracker-content">
                <h2 class="tracker-title">🌌 Live ISS Tracker</h2>
                <p class="tracker-description">
                    Watch the real-time position of the International Space Station (ISS) as it orbits our planet,
                    providing a breathtaking view of its global journey. <br><br>This live tracker is powered by
                    Pipilika
                    X's advanced space-data integration system, designed to bring you closer to space like never
                    before.
                </p>
                <a href="#"><button class="btn">Learn More <img
                            src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" /></button></a>
            </div>
            <div class="tracker-frame-wrapper">
                <iframe
                    src="https://isstracker.pl/en/widget/map?disableInfoBox=1&lang=en&z=4&mapType=satellite&units=metric&preloader=1&showSatTooltip=1&dayNightLayer=1&showFutureOrbit=1"
                    width="100%" height="450" frameborder="0" allowfullscreen loading="lazy" class="tracker-iframe">
                </iframe>
            </div>
        </div>
    </section>

    <!-- Adventure Fixed Section (Static) -->
    <section id="advenntureFixedSection">
        <div class="adventure-container">
            <div class="adventure-content">
                <h3>Adventures Beyond the Stars, Adrenaline That Defies Gravity</h3>
                <p>Our journey isn't just about reaching the stars—it's about understanding them, and bringing that
                    understanding back to Earth in the simplest, most engaging way possible.</p>
            </div>
            <a class="adventure-call-to-action" href="#">
                <button class="btn">Learn More <img src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" /></button>
            </a>
        </div>
    </section>

    <!-- Learn More Section (Static) -->
    <section id="learnMoreSection">
        <div class="learn-more-container">
            <div class="learn-more-content">
                <h2>Get More Info on Alien Species</h2>
                <p>
                    With an array of immersive content, Sora invites you to discover the beauty and vastness of our
                    universe, igniting
                    your curiosity and expanding your knowledge. Whether you're an avid astronomer or simply
                    fascinated by the cosmos. <strong>Remember sky is not the limit!!!</strong>
                </p>
                <div class="learn-more-images">
                    <img src="<?php echo ASSETS_URL; ?>/images/space-robot-man.jpg" alt="Space robot man image"
                        width="400">
                    <img src="<?php echo ASSETS_URL; ?>/images/planets-image.jpg" alt="Planets image" width="400">
                    <img src="<?php echo ASSETS_URL; ?>/images/rocket.jpg" alt="Rocket lunching image" width="400">
                </div>
                <a href="#"><button class="btn">Learn More <img
                            src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" /></button></a>
            </div>
        </div>
    </section>
</main>

<?php
// Custom JavaScript for this page
$additional_js = '
<script>
    // Starfield animation
    const canvas = document.getElementById("starfield");
    const ctx = canvas.getContext("2d");

    let W, H;

    function resize() {
        W = canvas.width = canvas.clientWidth;
        H = canvas.height = canvas.clientHeight;
    }
    window.addEventListener("resize", resize);
    resize();

    class Star {
        constructor(initial = false) {
            this.reset(initial);
        }

        reset(initial = false) {
            this.angle = Math.random() * 2 * Math.PI;
            this.radius = initial ? Math.random() * Math.max(W, H) : 0;
            this.speed = 0.5 + Math.random() * 2.5;
            this.length = 2 + Math.random() * 3;
            this.size = 0.7 + Math.random() * 1.3;
            this.baseOpacity = 0.2 + Math.random() * 0.5;
        }

        update() {
            this.radius += this.speed;
            if (this.x < 0 || this.x > W || this.y < 0 || this.y > H) {
                this.reset();
            }
        }

        get x() {
            const xOffset = W * 0.75;
            return xOffset + this.radius * Math.cos(this.angle);
        }

        get y() {
            return H / 2 + this.radius * Math.sin(this.angle);
        }

        get distance() {
            return this.radius;
        }

        draw() {
            const fadeRadius = 250;
            let fadeFactor = this.distance / fadeRadius;
            if (fadeFactor > 1) fadeFactor = 1;

            const opacity = this.baseOpacity * fadeFactor * fadeFactor;
            const size = this.size * fadeFactor;

            const tailX = this.x - Math.cos(this.angle) * this.length * this.speed;
            const tailY = this.y - Math.sin(this.angle) * this.length * this.speed;

            ctx.beginPath();
            ctx.strokeStyle = `rgba(255, 255, 255, ${opacity})`;
            ctx.lineWidth = size;
            ctx.moveTo(tailX, tailY);
            ctx.lineTo(this.x, this.y);
            ctx.stroke();
        }
    }

    const stars = [];
    const maxStars = 600;

    for (let i = 0; i < 100; i++) {
        stars.push(new Star(true));
    }

    function animate() {
        ctx.fillStyle = "rgba(0, 0, 0, 0.2)";
        ctx.fillRect(0, 0, W, H);

        stars.forEach(star => {
            star.update();
            star.draw();
        });

        if (stars.length < maxStars) {
            stars.push(new Star());
        }

        requestAnimationFrame(animate);
    }

    animate();

    // Hero text typing animation
    const phrases = ' . json_encode($hero_phrases) . ';

    const typedTextSpan = document.getElementById("typed-text");
    const typingDelay = 100;
    const erasingDelay = 50;
    const newTextDelay = 1500;
    let phraseIndex = 0;
    let charIndex = 0;

    function type() {
        if (charIndex < phrases[phraseIndex].length) {
            typedTextSpan.textContent += phrases[phraseIndex].charAt(charIndex);
            charIndex++;
            setTimeout(type, typingDelay);
        } else {
            setTimeout(erase, newTextDelay);
        }
    }

    function erase() {
        if (charIndex > 0) {
            typedTextSpan.textContent = phrases[phraseIndex].substring(0, charIndex - 1);
            charIndex--;
            setTimeout(erase, erasingDelay);
        } else {
            phraseIndex++;
            if (phraseIndex >= phrases.length) phraseIndex = 0;
            setTimeout(type, typingDelay);
        }
    }

    // Start the typing effect on page load
    document.addEventListener("DOMContentLoaded", function () {
        if (phrases.length) setTimeout(type, newTextDelay + 250);
    });
</script>
';

// Include footer
require_once __DIR__ . '/includes/templates/footer.php';
?>