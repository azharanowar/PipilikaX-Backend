-- PipilikaX Database - Complete Setup with Sample Data
-- Version: Final
-- Date: December 2025
-- This file contains the complete database with realistic sample data

CREATE DATABASE IF NOT EXISTS pipilikax_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pipilikax_db;

-- ============================================
-- 1. USERS TABLE
-- ============================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('admin', 'editor', 'author', 'subscriber') DEFAULT 'subscriber',
    is_active BOOLEAN DEFAULT TRUE,
    avatar VARCHAR(255),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_role (role),
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- Sample users (password for all: password123)
INSERT INTO users (username, email, password, full_name, role, bio) VALUES 
('admin', 'admin@pipilikax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin', 'System administrator'),
('azhar', 'azhar@pipilikax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Azhar Anowar', 'editor', 'Founder & CEO'),
('arafat', 'arafat@pipilikax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Md Arafat Hossain', 'author', 'Chief Technology Officer');

-- ============================================
-- 2. NAVIGATION MENU TABLE
-- ============================================
CREATE TABLE navigation_menu (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    target VARCHAR(20) DEFAULT '_self',
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    parent_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES navigation_menu(id) ON DELETE CASCADE,
    INDEX idx_display_order (display_order),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB;

-- Default navigation (matching current website)
INSERT INTO navigation_menu (title, url, display_order, is_active) VALUES 
('Home', 'index.php', 1, TRUE),
('Blogs', 'blogs.php', 2, TRUE),
('About Us', 'about.php', 3, TRUE),
('Contact Us', 'contact.php', 4, TRUE);

-- ============================================
-- 3. CATEGORIES TABLE
-- ============================================
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    post_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB;

-- Sample categories
INSERT INTO categories (name, slug, description) VALUES 
('Space Exploration', 'space-exploration', 'Articles about space missions and cosmic discoveries'),
('Astronomy', 'astronomy', 'Celestial phenomena and sky observations'),
('Technology', 'technology', 'Space technology and innovations'),
('Science', 'science', 'Scientific breakthroughs and research'),
('NASA Updates', 'nasa-updates', 'Latest news from NASA missions');

-- ============================================
-- 4. BLOG POSTS TABLE
-- ============================================
CREATE TABLE blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(255),
    author_id INT,
    category_id INT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    views INT DEFAULT 0,
    allow_comments BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_author (author_id),
    INDEX idx_category (category_id),
    INDEX idx_published_at (published_at)
) ENGINE=InnoDB;

-- Sample blog posts
INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, author_id, category_id, status, views, published_at) VALUES 
(
    'Webb Sees Sombrero Galaxy in Near-Infrared',
    'webb-sees-sombrero-galaxy-in-near-infrared',
    'NASA\'s James Webb Space Telescope recently imaged the Sombrero Galaxy with its NIRCam, revealing stunning details of this cosmic wonder.',
    '<p>NASA\'s James Webb Space Telescope recently imaged the Sombrero Galaxy with its NIRCam (Near-Infrared Camera), which shows dust from the galaxy\'s outer ring blocking stellar light from stars within the galaxy. In the central region of the galaxy, the roughly 2,000 globular clusters, or collections of hundreds of thousands of old stars held together by gravity, glow in the near-infrared.</p><p>The Sombrero Galaxy is around 30 million light-years from Earth in the constellation Virgo. From Earth, we see this galaxy nearly "edge-on," or from the side.</p><p>This new image reveals the smooth, clumpy nature of the dust that makes up the galaxy\'s outer ring, where new stars are forming. The JWST\'s infrared view is giving astronomers and space enthusiasts a whole new perspective on this iconic galaxy.</p>',
    'image-of-the-day.jpg',
    2,
    2,
    'published',
    156,
    '2024-12-01 10:30:00'
),
(
    'The Future of Mars Exploration',
    'the-future-of-mars-exploration',
    'As we stand on the brink of a new era in space exploration, Mars missions are becoming more ambitious and closer to reality.',
    '<p>The red planet has captivated humanity for centuries, and now we\'re closer than ever to establishing a human presence on Mars. Recent missions by NASA, SpaceX, and other space agencies have paved the way for future exploration.</p><h2>Current Missions</h2><p>The Perseverance rover continues to explore the Jezero Crater, searching for signs of ancient microbial life. Meanwhile, the Ingenuity helicopter has proven that powered flight is possible in Mars\' thin atmosphere.</p><h2>Future Plans</h2><p>SpaceX\'s Starship is being developed specifically for Mars missions, with Elon Musk aiming for the first crewed mission by the 2030s. NASA\'s Artemis program will also play a crucial role, testing technologies on the Moon before applying them to Mars.</p>',
    'mars-image.jpg',
    2,
    1,
    'published',
    243,
    '2024-11-28 14:20:00'
),
(
    'Understanding Black Holes: A Beginner\'s Guide',
    'understanding-black-holes-beginners-guide',
    'Black holes are among the most mysterious objects in the universe. Let\'s explore what they are and how they work.',
    '<p>Black holes are regions of spacetime where gravity is so strong that nothing, not even light, can escape from them. They form when massive stars collapse at the end of their life cycles.</p><h2>Types of Black Holes</h2><p>There are several types of black holes: stellar-mass black holes, intermediate-mass black holes, and supermassive black holes that sit at the centers of galaxies.</p><h2>Event Horizon</h2><p>The boundary of a black hole is called the event horizon. Once anything crosses this point, it can never return. Inside the event horizon lies the singularity, a point of infinite density.</p><p>Recent observations using the Event Horizon Telescope have even captured the first images of black holes, revolutionizing our understanding of these cosmic phenomena.</p>',
    'space.jpg',
    3,
    2,
    'published',
    189,
    '2024-11-25 09:15:00'
),
(
    'SpaceX Starship: The Rocket That Will Take Us to Mars',
    'spacex-starship-rocket-to-mars',
    'SpaceX\'s Starship represents the future of space travel, designed to carry both crew and cargo to the Moon, Mars, and beyond.',
    '<p>Starship is the world\'s most powerful launch vehicle ever developed, capable of carrying up to 100 people on long-duration interplanetary flights. Standing at 120 meters tall, it\'s a fully reusable transportation system designed to revolutionize space travel.</p><h2>Key Features</h2><p>The spacecraft consists of two stages: the Super Heavy booster and the Starship spacecraft. Both stages are designed to be fully and rapidly reusable, dramatically reducing the cost of space access.</p><h2>Test Flights</h2><p>SpaceX has conducted multiple test flights, each providing valuable data to improve the design. The company continues to iterate rapidly, bringing humanity closer to becoming a multi-planetary species.</p>',
    'spaceX-flight-rocket.jpg',
    2,
    3,
    'published',
    312,
    '2024-11-20 16:45:00'
),
(
    'The International Space Station: 25 Years of Science',
    'international-space-station-25-years',
    'For over two decades, the ISS has served as a unique laboratory for scientific research and international cooperation.',
    '<p>The International Space Station (ISS) represents one of humanity\'s greatest achievements in space exploration. Launched in 1998, it has been continuously inhabited for over 20 years, hosting astronauts from around the world.</p><h2>Scientific Research</h2><p>The ISS serves as a microgravity laboratory where researchers conduct experiments in biology, human biology, physics, astronomy, and meteorology. The unique environment has led to breakthroughs in medicine, materials science, and our understanding of how the human body adapts to space.</p><h2>International Cooperation</h2><p>Involving five space agencies (NASA, Roscosmos, JAXA, ESA, and CSA), the ISS stands as a symbol of international cooperation and peaceful use of space.</p>',
    'sapceX-image.jpg',
    3,
    1,
    'published',
    167,
    '2024-11-15 11:30:00'
),
(
    'Discovering Exoplanets: Worlds Beyond Our Solar System',
    'discovering-exoplanets-worlds-beyond',
    'The search for planets orbiting other stars has revealed thousands of worlds, some of which might harbor life.',
    '<p>Since the first confirmed detection in 1992, astronomers have discovered over 5,000 exoplanets. These discoveries have revolutionized our understanding of planetary systems and the potential for life beyond Earth.</p><h2>Detection Methods</h2><p>Scientists use several methods to detect exoplanets, including the transit method (observing dimming as a planet passes in front of its star) and the radial velocity method (detecting wobbles in a star caused by orbiting planets).</p><h2>Habitable Worlds</h2><p>Some exoplanets lie in their star\'s habitable zone, where conditions might allow liquid water to exist on the surface. These worlds are prime targets in the search for extraterrestrial life.</p>',
    'planets-image.jpg',
    2,
    2,
    'published',
    201,
    '2024-11-10 08:00:00'
);

-- ============================================
-- 5. TEAM MEMBERS TABLE
-- ============================================
CREATE TABLE team_members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    photo VARCHAR(255),
    bio TEXT,
    email VARCHAR(100),
    phone VARCHAR(20),
    facebook_url VARCHAR(255),
    linkedin_url VARCHAR(255),
    twitter_url VARCHAR(255),
    github_url VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_display_order (display_order),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB;

-- Team members from current website
INSERT INTO team_members (name, position, photo, bio, email, facebook_url, linkedin_url, twitter_url, github_url, display_order) VALUES 
(
    'Azhar Anowar',
    'Founder & CEO',
    'team-1.jpg',
    'Visionary leader driving PipilikaX towards making space knowledge accessible to everyone.',
    'azhar@pipilikax.com',
    'https://facebook.com/azharanowar',
    'https://www.linkedin.com/in/azharanowar/',
    'https://x.com/AzharAnowar',
    'https://github.com/azharanowar',
    1
),
(
    'Md Arafat Hossain',
    'Chief Technology Officer',
    'team-2.jpg',
    'Technology enthusiast building innovative solutions for space education.',
    'arafat@pipilikax.com',
    'https://www.facebook.com/sijan.khan4646',
    '#',
    '#',
    '#',
    2
),
(
    'Al-Rafi Azad',
    'Product Designer',
    'team-3.jpg',
    'Creating beautiful and intuitive designs that bring space closer to people.',
    'rafi@pipilikax.com',
    'https://www.facebook.com/alrafi.azad.9',
    '#',
    '#',
    '#',
    3
),
(
    'Taohidul Islam',
    'Marketing Lead',
    'team-4.jpg',
    'Spreading the word about space exploration and making PipilikaX accessible to all.',
    'taohid@pipilikax.com',
    'https://www.facebook.com/mdtaohid.binbhuiyan',
    '#',
    '#',
    '#',
    4
);

-- ============================================
-- 6. CONTACT MESSAGES TABLE
-- ============================================
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    replied_at TIMESTAMP NULL,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- Sample contact messages
INSERT INTO contact_messages (name, email, subject, message, status, ip_address, created_at) VALUES 
('John Doe', 'john.doe@example.com', 'Question about Mars mission', 'I loved your article about Mars exploration! When do you think humans will actually land on Mars?', 'new', '192.168.1.1', '2024-12-03 10:30:00'),
('Sarah Johnson', 'sarah.j@example.com', 'Collaboration inquiry', 'I''m interested in collaborating on space education content. Could we schedule a call?', 'read', '192.168.1.2', '2024-12-02 14:20:00'),
('Mike Chen', 'mike.chen@example.com', 'Technical question', 'Great content! I have a question about black hole formation. Can you provide more details?', 'new', '192.168.1.3', '2024-12-01 09:15:00');

-- ============================================
-- 7. SETTINGS TABLE
-- ============================================
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'textarea', 'image', 'boolean', 'number', 'url') DEFAULT 'text',
    setting_group VARCHAR(50) DEFAULT 'general',
    description VARCHAR(255),
    updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by INT,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_group (setting_group),
    INDEX idx_key (setting_key)
) ENGINE=InnoDB;

-- Complete settings from current website
INSERT INTO settings (setting_key, setting_value, setting_type, setting_group, description) VALUES 
-- General
('site_name', 'PipilikaX', 'text', 'general', 'Website name'),
('site_tagline', 'Your Space for AutoNotes', 'text', 'general', 'Website tagline'),
('site_logo', 'pipilika-logo.png', 'image', 'general', 'Main site logo'),
('site_logo_white', 'pipilika-logo-main-white.png', 'image', 'general', 'White logo for dark backgrounds'),
('site_favicon', 'pipilika-favicon.png', 'image', 'general', 'Site favicon'),

-- Homepage Hero
('hero_welcome_title', 'Welcome to PipilikaX', 'text', 'homepage', 'Hero section main title'),
('hero_phrase_1', 'Explore our world.', 'text', 'homepage', 'Typing animation phrase 1'),
('hero_phrase_2', 'Discover new horizons.', 'text', 'homepage', 'Typing animation phrase 2'),
('hero_phrase_3', 'Unleash your curiosity.', 'text', 'homepage', 'Typing animation phrase 3'),
('hero_phrase_4', 'Journey into the unknown.', 'text', 'homepage', 'Typing animation phrase 4'),
('hero_phrase_5', 'Experience the extraordinary.', 'text', 'homepage', 'Typing animation phrase 5'),
('hero_intro_text', 'Adventures beyond the stars, adrenaline that defies gravity – welcome to:', 'textarea', 'homepage', 'Hero introduction text'),
('hero_cta_text', 'Join the Journey', 'text', 'homepage', 'Hero button text'),
('hero_cta_url', '#', 'url', 'homepage', 'Hero button URL'),

-- Homepage About Section
('about_title', 'Get to know PipilikaX', 'text', 'homepage', 'About section title'),
('about_text', 'Pipilika X is a visionary initiative driven by curiosity, exploration, and the pursuit of knowledge. Inspired by the determination of ants (pipilika in Sanskrit) and the bold ambition of pioneers like SpaceX, Pipilika X is on a mission to make complex information about Earth and the universe easily accessible to everyone.\n\nWe believe that knowledge should not be reserved for a few—it should flow freely, like stardust, reaching minds across the globe. Whether it''s decoding the wonders of space, uncovering hidden patterns of our planet, or translating scientific breakthroughs into everyday language, Pipilika X is here to deliver insights that matter.\n\nOur journey isn''t just about reaching the stars—it''s about understanding them, and bringing that understanding back to Earth in the simplest, most engaging way possible.\n\nPipilika X – Know the world. Explore beyond.', 'textarea', 'homepage', 'About section content'),
('about_cta_text', 'Join the Journey Now', 'text', 'homepage', 'About button text'),

-- Footer
('footer_brand_name', 'PipilikaX', 'text', 'footer', 'Footer brand name'),
('footer_copyright', 'Copyright © 2025 – All rights reserved.', 'text', 'footer', 'Copyright text'),
('footer_github_url', 'https://github.com/azharanowar/pipilikaX', 'url', 'footer', 'GitHub repository link'),

-- Contact
('contact_email', 'info@pipilikax.com', 'text', 'contact', 'Contact email'),
('contact_phone', '+880 1234-567890', 'text', 'contact', 'Contact phone'),
('contact_address', 'Dhaka, Bangladesh', 'text', 'contact', 'Contact address'),

-- Social Media
('facebook_url', '#', 'url', 'social', 'Facebook page URL'),
('twitter_url', '#', 'url', 'social', 'Twitter profile URL'),
('linkedin_url', '#', 'url', 'social', 'LinkedIn page URL'),
('github_url', 'https://github.com/azharanowar/pipilikaX', 'url', 'social', 'GitHub repository URL'),

-- About Page
('about_page_subtitle', 'We are a passionate team dedicated to creating cutting-edge AI solutions that impact the future.', 'textarea', 'about', 'About page subtitle');

-- ============================================
-- 8. ACTIVITY LOG TABLE
-- ============================================
CREATE TABLE activity_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    description VARCHAR(255),
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- Sample activity logs
INSERT INTO activity_log (user_id, action, entity_type, entity_id, description, ip_address) VALUES 
(1, 'login', 'user', 1, 'Admin user logged in', '127.0.0.1'),
(2, 'create', 'blog_post', 1, 'Created new blog post: Webb Sees Sombrero Galaxy', '127.0.0.1'),
(2, 'publish', 'blog_post', 1, 'Published blog post: Webb Sees Sombrero Galaxy', '127.0.0.1');

-- ============================================
-- TRIGGERS
-- ============================================

DELIMITER $$

CREATE TRIGGER increment_category_count 
AFTER INSERT ON blog_posts
FOR EACH ROW
BEGIN
    IF NEW.status = 'published' THEN
        UPDATE categories SET post_count = post_count + 1 WHERE id = NEW.category_id;
    END IF;
END$$

CREATE TRIGGER update_category_count
AFTER UPDATE ON blog_posts
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        IF NEW.status = 'published' AND OLD.status != 'published' THEN
            UPDATE categories SET post_count = post_count + 1 WHERE id = NEW.category_id;
        ELSEIF OLD.status = 'published' AND NEW.status != 'published' THEN
            UPDATE categories SET post_count = post_count - 1 WHERE id = OLD.category_id;
        END IF;
    END IF;
END$$

CREATE TRIGGER decrement_category_count
AFTER DELETE ON blog_posts
FOR EACH ROW
BEGIN
    IF OLD.status = 'published' THEN
        UPDATE categories SET post_count = post_count - 1 WHERE id = OLD.category_id;
    END IF;
END$$

DELIMITER ;

-- ============================================
-- VIEWS
-- ============================================

CREATE VIEW published_posts AS
SELECT 
    p.id,
    p.title,
    p.slug,
    p.excerpt,
    p.featured_image,
    p.views,
    p.published_at,
    u.full_name AS author_name,
    u.username AS author_username,
    c.name AS category_name,
    c.slug AS category_slug
FROM blog_posts p
LEFT JOIN users u ON p.author_id = u.id
LEFT JOIN categories c ON p.category_id = c.id
WHERE p.status = 'published'
ORDER BY p.published_at DESC;

CREATE VIEW active_team AS
SELECT * FROM team_members 
WHERE is_active = TRUE 
ORDER BY display_order ASC;

CREATE VIEW active_navigation AS
SELECT * FROM navigation_menu 
WHERE is_active = TRUE 
ORDER BY display_order ASC;

-- ============================================
-- UPDATE CATEGORY POST COUNTS
-- ============================================

UPDATE categories c
SET post_count = (
    SELECT COUNT(*) 
    FROM blog_posts p 
    WHERE p.category_id = c.id AND p.status = 'published'
);

-- ============================================
-- INDEXES FOR PERFORMANCE
-- ============================================

CREATE INDEX idx_posts_status_published ON blog_posts(status, published_at);
CREATE INDEX idx_posts_author_status ON blog_posts(author_id, status);

/*
DATABASE SETUP COMPLETE!

Summary:
- 8 Tables created
- 3 Users (admin, editor, author)
- 4 Navigation menu items
- 5 Categories
- 6 Sample blog posts
- 4 Team members
- 3 Contact messages
- 30+ Settings entries
- Sample activity logs

Default Login:
Username: admin
Password: password123
⚠️ CHANGE THIS AFTER FIRST LOGIN!

Next Steps:
1. Import this file into MySQL:
   mysql -u root -p < database/pipilikax_complete.sql
2. Configure config/database.php
3. Start building PHP files
*/
