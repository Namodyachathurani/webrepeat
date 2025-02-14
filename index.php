<?php
require_once 'includes/db.php';

// Fetch featured products
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY RAND() LIMIT 4");
$stmt->execute();
$featured_products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T-Shirt Printing Sri Lanka - Custom T-Shirts & Design Services</title>
    <meta name="description" content="Professional t-shirt printing services in Sri Lanka. Custom designs, bulk orders, and premium quality printing for all your needs.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
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
                <a href="contact.php">Contact</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Custom T-Shirt Printing in Sri Lanka</h1>
            <p>Premium quality printing services for individuals, businesses, and events. Turn your ideas into wearable art.</p>
            <a href="products.php" class="cta-button">Explore Our Products</a>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-products">
        <div class="section-title">
            <h2>Featured Products</h2>
            <p>Discover our most popular t-shirt styles and printing options</p>
        </div>
        <div class="products-grid">
            <?php foreach ($featured_products as $product): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                <div class="product-info">
                    <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="product-price">LKR <?php echo number_format($product['price'] * 320, 2); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Services -->
    <section class="services">
        <div class="section-title">
            <h2>Our Services</h2>
            <p>Professional printing solutions for every need</p>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-tshirt"></i>
                </div>
                <h3>Custom Printing</h3>
                <p>High-quality custom designs printed on premium materials</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Bulk Orders</h3>
                <p>Special rates for large orders and events</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-pencil-alt"></i>
                </div>
                <h3>Design Services</h3>
                <p>Professional design team to bring your ideas to life</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about">
        <div class="about-content">
            <h2>Why Choose Us?</h2>
            <p>With years of experience in the t-shirt printing industry, we provide top-quality products and exceptional service. Our state-of-the-art printing technology ensures vibrant, long-lasting designs that meet your exact specifications.</p>
            <a href="about.php" class="cta-button">Learn More About Us</a>
        </div>
    </section>

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