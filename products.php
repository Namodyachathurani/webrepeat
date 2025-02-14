<?php
require_once 'includes/db.php';

// Get selected category from URL
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Fetch all categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll();

// Prepare products query
if ($category_id) {
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name 
                          FROM products p 
                          JOIN categories c ON p.category = c.id 
                          WHERE p.category = ? 
                          ORDER BY p.name");
    $stmt->execute([$category_id]);
} else {
    $stmt = $pdo->query("SELECT p.*, c.name as category_name 
                         FROM products p 
                         JOIN categories c ON p.category = c.id 
                         ORDER BY p.name");
}
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products - T-Shirt Printing Sri Lanka</title>
    <meta name="description" content="Browse our collection of t-shirts and printing services. Custom designs, bulk orders, and premium quality printing available.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .products-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .category-filter {
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .category-btn {
            padding: 0.5rem 1rem;
            background: var(--white);
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .category-btn:hover,
        .category-btn.active {
            background: var(--secondary-color);
            color: var(--white);
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .product-card {
            border: 1px solid #eee;
            padding-bottom: 1rem;
        }
        
        .product-details {
            padding: 1rem;
        }
        
        .product-category {
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .product-description {
            font-size: 0.9rem;
            color: #666;
            margin: 0.5rem 0;
        }
        
        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }
        
        .inquire-btn {
            padding: 0.5rem 1rem;
            background: var(--accent-color);
            color: var(--white);
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: background 0.3s ease;
        }
        
        .inquire-btn:hover {
            background: #c0392b;
        }
        
        .no-products {
            text-align: center;
            padding: 2rem;
            grid-column: 1 / -1;
        }
        
        @media (max-width: 768px) {
            .products-container {
                padding: 1rem;
            }
            
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1rem;
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
                <a href="products.php" class="active">Products</a>
                <a href="about.php">About</a>
                <a href="contact.php">Contact</a>
            </div>
        </nav>
    </header>

    <!-- Products Section -->
    <div class="products-container" style="margin-top: 80px;">
        <h1>Our Products</h1>
        <p>Browse our collection of high-quality t-shirts and printing services</p>
        
        <!-- Category Filter -->
        <div class="category-filter">
            <a href="products.php" class="category-btn <?php echo !$category_id ? 'active' : ''; ?>">
                All Products
            </a>
            <?php foreach ($categories as $category): ?>
            <a href="?category=<?php echo $category['id']; ?>" 
               class="category-btn <?php echo $category_id === $category['id'] ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($category['name']); ?>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Products Grid -->
        <div class="product-grid">
            <?php if (empty($products)): ?>
            <div class="no-products">
                <h2>No products found in this category</h2>
                <p>Please try selecting a different category</p>
            </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         class="product-image">
                    <div class="product-details">
                        <div class="product-category">
                            <?php echo htmlspecialchars($product['category_name']); ?>
                        </div>
                        <h3 class="product-title">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h3>
                        <p class="product-description">
                            <?php echo htmlspecialchars($product['description']); ?>
                        </p>
                        <div class="product-meta">
                            <span class="product-price">
                                LKR <?php echo number_format($product['price'] * 320, 2); ?>
                            </span>
                            <a href="contact.php?product=<?php echo $product['id']; ?>" 
                               class="inquire-btn">
                                Inquire Now
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
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