<?php
// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get environment variables or use defaults
$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_DATABASE') ?: 'zeroweb1_namo';
$user = getenv('DB_USERNAME') ?: 'zeroweb1_namo';
$pass = getenv('DB_PASSWORD') ?: 'namopass';
$charset = 'utf8mb4';

try {
    // Create connection with retry logic
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $maxTries = 10;
    $tries = 0;
    $sleepTime = 3; // seconds

    while ($tries < $maxTries) {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            break;
        } catch (PDOException $e) {
            $tries++;
            if ($tries === $maxTries) {
                throw new PDOException("Database connection failed after $maxTries attempts: " . $e->getMessage());
            }
            sleep($sleepTime);
        }
    }
    
    // First, let's update our categories to be more specific
    $categories = [
        ['Basic T-Shirts', 'Standard cotton t-shirts perfect for custom printing'],
        ['Premium T-Shirts', 'High-quality premium cotton and blended fabric t-shirts'],
        ['Polo Shirts', 'Professional polo shirts for business and events'],
        ['Sports Wear', 'Performance fabrics for sports and active wear'],
        ['Bulk Orders', 'Wholesale t-shirts for large orders and events'],
        ['Custom Design Services', 'Custom design and printing services']
    ];
    
    // Clear existing categories and products
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE products");
    $pdo->exec("TRUNCATE TABLE categories");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "Existing categories and products cleared.<br>";
    
    // Insert new categories
    $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    foreach ($categories as $category) {
        $stmt->execute($category);
        echo "Category '{$category[0]}' added.<br>";
    }
    
    // Products for each category
    $products = [
        // Basic T-Shirts
        1 => [
            ['Classic Cotton Tee', 'Standard 100% cotton t-shirt, perfect for everyday wear and custom printing', 12.99],
            ['Basic V-Neck', 'Simple V-neck cotton t-shirt available in multiple colors', 14.99],
            ['Essential Crew Neck', 'Comfortable crew neck t-shirt for casual wear', 11.99],
            ['Youth Basic Tee', 'Kid-sized basic t-shirt for custom printing', 9.99],
            ['Basic Long Sleeve', 'Long sleeve cotton t-shirt for cooler weather', 16.99],
            ['Unisex Standard Fit', 'Universal fit t-shirt suitable for all genders', 13.99],
            ['Basic Black Tee', 'Classic black t-shirt perfect for custom designs', 12.99],
            ['Basic White Tee', 'Pure white t-shirt ideal for vibrant prints', 12.99],
            ['Color Range Basic', 'Basic t-shirt available in 20+ colors', 13.99],
            ['Basic Pocket Tee', 'Cotton t-shirt with a chest pocket', 14.99]
        ],
        
        // Premium T-Shirts
        2 => [
            ['Premium Cotton Blend', 'Luxury cotton-polyester blend t-shirt', 24.99],
            ['Organic Cotton Tee', '100% organic cotton premium t-shirt', 29.99],
            ['Bamboo Blend Comfort', 'Eco-friendly bamboo-cotton blend t-shirt', 34.99],
            ['Premium V-Neck', 'High-quality V-neck with superior stitching', 27.99],
            ['Premium Long Sleeve', 'Premium long sleeve t-shirt with cuffed sleeves', 32.99],
            ['Fitted Premium Tee', 'Tailored fit premium t-shirt', 29.99],
            ['Premium Pocket Design', 'Premium t-shirt with designer pocket', 31.99],
            ['Heavyweight Premium', 'Thick premium cotton t-shirt', 34.99],
            ['Premium Slim Fit', 'Modern slim fit premium t-shirt', 29.99],
            ['Premium Color Selection', 'Premium t-shirt in exclusive colors', 27.99]
        ],
        
        // Polo Shirts
        3 => [
            ['Classic Business Polo', 'Traditional polo shirt for professional settings', 29.99],
            ['Premium Sport Polo', 'High-performance polo for active wear', 34.99],
            ['Cotton Pique Polo', 'Classic pique knit polo shirt', 32.99],
            ['Modern Fit Polo', 'Contemporary fit polo with style', 36.99],
            ['Long Sleeve Polo', 'Professional long sleeve polo shirt', 39.99],
            ['Moisture Wicking Polo', 'Performance polo with moisture control', 37.99],
            ['Striped Design Polo', 'Polo shirt with classic stripe pattern', 34.99],
            ['Premium Cotton Polo', 'Luxury cotton polo shirt', 42.99],
            ['Casual Friday Polo', 'Relaxed fit polo for casual wear', 31.99],
            ['Executive Polo', 'Premium polo for business executives', 44.99]
        ],
        
        // Sports Wear
        4 => [
            ['Performance Tee', 'Moisture-wicking performance t-shirt', 24.99],
            ['Team Sport Jersey', 'Customizable team sports jersey', 29.99],
            ['Athletic Training Shirt', 'Lightweight training shirt', 27.99],
            ['Sports Long Sleeve', 'Long sleeve performance shirt', 31.99],
            ['Gym Training Tee', 'Durable gym workout t-shirt', 26.99],
            ['Running Performance', 'Breathable running shirt', 28.99],
            ['Basketball Jersey', 'Classic basketball jersey style', 32.99],
            ['Soccer Training Top', 'Professional soccer training shirt', 34.99],
            ['Sports Polo', 'Athletic polo shirt for sports', 36.99],
            ['Compression Shirt', 'Tight fit performance compression shirt', 39.99]
        ],
        
        // Bulk Orders
        5 => [
            ['Event Basic Tee 50+', 'Basic t-shirt for events (min 50 pieces)', 8.99],
            ['Bulk Cotton 100+', 'Standard cotton tee (min 100 pieces)', 7.99],
            ['Team Package 25+', 'Sports team package (min 25 pieces)', 19.99],
            ['Corporate Bulk 75+', 'Corporate polo package (min 75 pieces)', 24.99],
            ['School Event 200+', 'School event t-shirts (min 200 pieces)', 6.99],
            ['Club Package 50+', 'Club/Group package (min 50 pieces)', 9.99],
            ['Charity Event 300+', 'Charity event package (min 300 pieces)', 5.99],
            ['Sports Team 30+', 'Sports team bundle (min 30 pieces)', 18.99],
            ['Business Bulk 100+', 'Business polo package (min 100 pieces)', 22.99],
            ['Festival Package 500+', 'Festival/Event package (min 500 pieces)', 4.99]
        ],
        
        // Custom Design Services
        6 => [
            ['Basic Logo Print', 'Simple logo printing service', 5.99],
            ['Custom Artwork Design', 'Custom artwork design service', 49.99],
            ['Photo Print Service', 'High-quality photo printing on shirts', 14.99],
            ['Vector Art Creation', 'Custom vector art design service', 39.99],
            ['Text Design Service', 'Custom text and typography design', 19.99],
            ['Full Color Print', 'Full color custom printing service', 24.99],
            ['Premium Design Package', 'Complete design and print package', 79.99],
            ['Logo Digitizing', 'Logo digitizing for embroidery', 29.99],
            ['Rush Design Service', '24-hour rush design service', 69.99],
            ['Brand Identity Pack', 'Complete brand identity design package', 149.99]
        ]
    ];
    
    // Insert products
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, image_url) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($products as $category_id => $category_products) {
        foreach ($category_products as $product) {
            // Generate a placeholder image URL (you can replace these with real images later)
            $image_url = "assets/images/products/category{$category_id}/product" . rand(1, 999) . ".jpg";
            
            $stmt->execute([$product[0], $product[1], $product[2], $category_id, $image_url]);
            echo "Product '{$product[0]}' added to category {$category_id}.<br>";
        }
    }
    
    echo "<br>Seeding completed successfully!<br>";
    echo "Added " . count($categories) . " categories and " . (count($products) * 10) . " products.";
    
} catch (PDOException $e) {
    die("Seeding failed: " . $e->getMessage() . "<br>");
}
?> 