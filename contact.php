<?php
require_once 'includes/db.php';

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message]);
            $success_message = "Thank you for your message. We'll get back to you soon!";
            
            // Clear form data after successful submission
            $name = $email = $subject = $message = '';
        } catch (PDOException $e) {
            $error_message = "Sorry, there was an error sending your message. Please try again later.";
        }
    }
}

// Get product details if product ID is provided
$product_info = '';
if (isset($_GET['product'])) {
    $stmt = $pdo->prepare("SELECT name FROM products WHERE id = ?");
    $stmt->execute([$_GET['product']]);
    $product = $stmt->fetch();
    if ($product) {
        $product_info = "Inquiry about: " . $product['name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - T-Shirt Printing Sri Lanka</title>
    <meta name="description" content="Get in touch with us for all your t-shirt printing needs. Custom quotes, inquiries, and support available.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            margin-top: 2rem;
        }

        .contact-info {
            padding: 2rem;
            background: var(--light-gray);
            border-radius: 8px;
        }

        .contact-item {
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .contact-icon {
            font-size: 1.5rem;
            color: var(--secondary-color);
            width: 24px;
        }

        .contact-text h3 {
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .contact-form {
            padding: 2rem;
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background: var(--secondary-color);
            color: var(--white);
            padding: 1rem 2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background: #2980b9;
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .business-hours {
            margin-top: 2rem;
        }

        .hours-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }

        .hours-day {
            font-weight: 500;
            color: var(--primary-color);
        }

        .map-container {
            margin-top: 3rem;
            border-radius: 8px;
            overflow: hidden;
        }

        .map-container iframe {
            width: 100%;
            height: 400px;
            border: 0;
        }

        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .contact-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="/" class="logo">T-ShirtPrinting.lk</a>
            <div class="nav-links">
                <a href="/">Home</a>
                <a href="products.php">Products</a>
                <a href="about.php">About</a>
                <a href="contact.php" class="active">Contact</a>
            </div>
        </nav>
    </header>

    <!-- Contact Section -->
    <div class="contact-container" style="margin-top: 80px;">
        <h1>Contact Us</h1>
        <p>Get in touch with us for custom quotes, inquiries, or support.</p>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form">
                <h2>Send us a Message</h2>
                <form method="POST" action="contact.php">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" 
                               value="<?php echo htmlspecialchars($subject ?? $product_info); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" class="form-control" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="contact-info">
                <h2>Contact Information</h2>
                
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-text">
                        <h3>Address</h3>
                        <p>123 Main Street<br>Colombo 03<br>Sri Lanka</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-text">
                        <h3>Phone</h3>
                        <p>+94 11 234 5678<br>+94 77 123 4567</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-text">
                        <h3>Email</h3>
                        <p>info@tshirtprinting.lk<br>support@tshirtprinting.lk</p>
                    </div>
                </div>

                <div class="business-hours">
                    <h3>Business Hours</h3>
                    <div class="hours-grid">
                        <div class="hours-day">Monday - Friday:</div>
                        <div>9:00 AM - 6:00 PM</div>
                        <div class="hours-day">Saturday:</div>
                        <div>9:00 AM - 3:00 PM</div>
                        <div class="hours-day">Sunday:</div>
                        <div>Closed</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.798467112767!2d79.84882597486755!3d6.914682818494421!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25963120b1509%3A0x47076a738457f880!2sColombo%2003%2C%20Colombo!5e0!3m2!1sen!2slk!4v1708561271611!5m2!1sen!2slk" 
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> T-ShirtPrinting.lk. All rights reserved.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </footer>
</body>
</html> 