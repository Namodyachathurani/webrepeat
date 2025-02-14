-- Set character set
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `role` ENUM('admin', 'user') DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create categories table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create products table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `price` DECIMAL(10,2) NOT NULL,
    `image_url` VARCHAR(255),
    `category` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create contact_messages table
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('new', 'read', 'replied') DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user
INSERT IGNORE INTO users (username, password, email, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@tshirtprinting.lk', 'admin');

-- Insert default categories
INSERT IGNORE INTO categories (name, description) VALUES
('Basic T-Shirts', 'Standard cotton t-shirts perfect for custom printing'),
('Premium T-Shirts', 'High-quality premium cotton and blended fabric t-shirts'),
('Polo Shirts', 'Professional polo shirts for business and events'),
('Sports Wear', 'Performance fabrics for sports and active wear'),
('Bulk Orders', 'Wholesale t-shirts for large orders and events'),
('Custom Design Services', 'Custom design and printing services');

-- Insert sample products
INSERT INTO products (name, description, price, category, image_url) VALUES
-- Basic T-Shirts
('Classic Cotton Tee', 'Standard 100% cotton t-shirt, perfect for everyday wear and custom printing', 12.99, 1, 'assets/images/products/category1/basic1.jpg'),
('Basic V-Neck', 'Simple V-neck cotton t-shirt available in multiple colors', 14.99, 1, 'assets/images/products/category1/basic2.jpg'),
('Essential Crew Neck', 'Comfortable crew neck t-shirt for casual wear', 11.99, 1, 'assets/images/products/category1/basic3.jpg'),

-- Premium T-Shirts
('Premium Cotton Blend', 'Luxury cotton-polyester blend t-shirt', 24.99, 2, 'assets/images/products/category2/premium1.jpg'),
('Organic Cotton Tee', '100% organic cotton premium t-shirt', 29.99, 2, 'assets/images/products/category2/premium2.jpg'),
('Bamboo Blend Comfort', 'Eco-friendly bamboo-cotton blend t-shirt', 34.99, 2, 'assets/images/products/category2/premium3.jpg'),

-- Polo Shirts
('Classic Business Polo', 'Traditional polo shirt for professional settings', 29.99, 3, 'assets/images/products/category3/polo1.jpg'),
('Premium Sport Polo', 'High-performance polo for active wear', 34.99, 3, 'assets/images/products/category3/polo2.jpg'),
('Cotton Pique Polo', 'Classic pique knit polo shirt', 32.99, 3, 'assets/images/products/category3/polo3.jpg'),

-- Sports Wear
('Performance Tee', 'Moisture-wicking performance t-shirt', 24.99, 4, 'assets/images/products/category4/sport1.jpg'),
('Team Sport Jersey', 'Customizable team sports jersey', 29.99, 4, 'assets/images/products/category4/sport2.jpg'),
('Athletic Training Shirt', 'Lightweight training shirt', 27.99, 4, 'assets/images/products/category4/sport3.jpg'),

-- Bulk Orders
('Event Basic Tee 50+', 'Basic t-shirt for events (min 50 pieces)', 8.99, 5, 'assets/images/products/category5/bulk1.jpg'),
('Bulk Cotton 100+', 'Standard cotton tee (min 100 pieces)', 7.99, 5, 'assets/images/products/category5/bulk2.jpg'),
('Team Package 25+', 'Sports team package (min 25 pieces)', 19.99, 5, 'assets/images/products/category5/bulk3.jpg'),

-- Custom Design Services
('Basic Logo Print', 'Simple logo printing service', 5.99, 6, 'assets/images/products/category6/design1.jpg'),
('Custom Artwork Design', 'Custom artwork design service', 49.99, 6, 'assets/images/products/category6/design2.jpg'),
('Photo Print Service', 'High-quality photo printing on shirts', 14.99, 6, 'assets/images/products/category6/design3.jpg');

SET FOREIGN_KEY_CHECKS = 1; 