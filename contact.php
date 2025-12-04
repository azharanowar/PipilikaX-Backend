<?php
/**
 * Contact Page - PipilikaX
 * Dynamic version with form processing
 */

$page_title = 'Contact Us';

// Include header and navigation
require_once __DIR__ . '/includes/templates/header.php';
require_once __DIR__ . '/includes/templates/navigation.php';

$success = false;
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    // Get form data
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    // Validation
    if (empty($name)) {
        $errors[] = 'Please enter your name.';
    }

    if (empty($email)) {
        $errors[] = 'Please enter your email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($message)) {
        $errors[] = 'Please enter your message.';
    }

    // If no errors, save to database
    if (empty($errors)) {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

        $stmt = $pdo->prepare("
            INSERT INTO contact_messages (name, email, subject, message, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        if ($stmt->execute([$name, $email, $subject, $message, $ip_address, $user_agent])) {
            $success = true;
        } else {
            $errors[] = 'There was an error sending your message. Please try again.';
        }
    }
}

// Get contact info from settings
$contact_address = getSetting('contact_address', 'Dong-eui University, Intelligent Computing Department');
$contact_email = getSetting('contact_email', 'contact@pipilikaX.com');
$contact_phone = getSetting('contact_phone', '010-5149-3665');
$map_embed = getSetting('contact_map_embed', 'https://maps.google.com/maps?q=busan&t=k&z=13&ie=UTF8&iwloc=&output=embed');
?>

<main class="contact-section">
    <h1>Contact Us</h1>
    <p class="subtitle">We'd love to hear from you. Let's get in touch!</p>

    <div class="contact-wrapper">
        <!-- Contact Info -->
        <div class="contact-info">
            <h2>Our Location</h2>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($contact_address); ?></p>
            <p><strong>Email:</strong> <a
                    href="mailto:<?php echo htmlspecialchars($contact_email); ?>"><?php echo htmlspecialchars($contact_email); ?></a>
            </p>
            <p><strong>Phone:</strong> <a
                    href="tel:<?php echo htmlspecialchars($contact_phone); ?>"><?php echo htmlspecialchars($contact_phone); ?></a>
            </p>
        </div>

        <!-- Contact Form -->
        <form class="contact-form" method="POST" action="">
            <h2>Send a Message</h2>

            <?php if ($success): ?>
                <div class="alert alert-success"
                    style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                    <strong>Thank you!</strong> Your message has been sent successfully. We'll get back to you soon!
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger"
                    style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
                    <strong>Error:</strong>
                    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <input type="text" name="name" placeholder="Your Name"
                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>

            <input type="email" name="email" placeholder="Your Email"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>

            <input type="text" name="subject" placeholder="Subject (optional)"
                value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">

            <textarea name="message" placeholder="Your Message" rows="5"
                required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>

            <button type="submit" name="submit_contact" class="btn">
                Send Message <img src="<?php echo ASSETS_URL; ?>/images/arrow-white.svg" alt="Arrow" />
            </button>
        </form>
    </div>

    <!-- Map -->
    <div class="map-container">
        <h2>Find Us Here</h2>
        <iframe src="<?php echo htmlspecialchars($map_embed); ?>" frameborder="0" allowfullscreen loading="lazy">
        </iframe>
    </div>
</main>

<?php require_once __DIR__ . '/includes/templates/footer.php'; ?>