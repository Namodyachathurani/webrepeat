<?php
// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Increase execution time limit
set_time_limit(300); // Set to 5 minutes
ini_set('max_execution_time', 300);

// Database connection details
$host = 'localhost:3306';
$db   = 'zeroweb1_namo';
$user = 'zeroweb1_namo';
$pass = 'namopass';
$charset = 'utf8mb4';

// Connection string
$dsn = "mysql:host=$host;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_TIMEOUT           => 60,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
];

try {
    echo "Starting installation process...<br>";
    flush();
    ob_flush();

    // Create connection
    echo "Attempting to connect to database server...<br>";
    flush();
    ob_flush();
    
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Create database if not exists
    echo "Creating database if it doesn't exist...<br>";
    flush();
    ob_flush();
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`");
    $pdo->exec("USE `$db`");
    
    echo "Connected successfully<br>";
    flush();
    ob_flush();
    
    // Create users table
    echo "Creating users table...<br>";
    flush();
    ob_flush();
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `role` ENUM('admin', 'user') DEFAULT 'user',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Users table created successfully<br>";
    
    // Create categories table
    echo "Creating categories table...<br>";
    flush();
    ob_flush();
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS `categories` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `name` VARCHAR(100) NOT NULL,
        `description` TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Categories table created successfully<br>";
    
    // Create products table
    echo "Creating products table...<br>";
    flush();
    ob_flush();
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS `products` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `name` VARCHAR(255) NOT NULL,
        `description` TEXT,
        `price` DECIMAL(10,2) NOT NULL,
        `image_url` VARCHAR(255),
        `category` INT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (`category`) REFERENCES `categories`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Products table created successfully<br>";
    
    // Create contact_messages table
    echo "Creating contact messages table...<br>";
    flush();
    ob_flush();
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS `contact_messages` (
        `id` INT PRIMARY KEY AUTO_INCREMENT,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `subject` VARCHAR(200) NOT NULL,
        `message` TEXT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `status` ENUM('new', 'read', 'replied') DEFAULT 'new'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Contact messages table created successfully<br>";
    
    // Create default admin user
    echo "Creating default admin user...<br>";
    flush();
    ob_flush();
    
    $default_admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['admin', $default_admin_password, 'admin@tshirtprinting.lk', 'admin']);
    echo "Default admin user created successfully<br>";
    
    // Create some default categories
    echo "Creating default categories...<br>";
    flush();
    ob_flush();
    
    $default_categories = [
        ['T-Shirts', 'Regular t-shirts for printing'],
        ['Polo Shirts', 'Professional polo shirts for printing'],
        ['Custom Designs', 'Custom design printing services']
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name, description) VALUES (?, ?)");
    foreach ($default_categories as $category) {
        $stmt->execute($category);
    }
    echo "Default categories created successfully<br>";
    
    echo "<br>Installation completed successfully!<br>";
    echo "Default admin credentials:<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "<strong>Please change these credentials immediately after first login!</strong>";
    
} catch (PDOException $e) {
    die("Installation failed: " . $e->getMessage() . "<br>Error code: " . $e->getCode());
} catch (Exception $e) {
    die("General error: " . $e->getMessage() . "<br>");
}
?> 