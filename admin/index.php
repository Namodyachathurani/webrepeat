<?php
require_once '../includes/db.php';
require_once 'includes/auth_check.php';

// Get statistics
$stats = [
    'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'categories' => $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
    'messages' => $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn(),
    'unread_messages' => $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'")->fetchColumn()
];

// Get recent messages
$recent_messages = $pdo->query("
    SELECT * FROM contact_messages 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll();

// Get recent products
$recent_products = $pdo->query("
    SELECT p.*, c.name as category_name 
    FROM products p 
    JOIN categories c ON p.category = c.id 
    ORDER BY p.created_at DESC 
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - T-Shirt Printing Sri Lanka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
        }

        .admin-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background: var(--primary-color);
            color: var(--white);
            padding: 1rem;
        }

        .sidebar-header {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu a {
            color: var(--white);
            text-decoration: none;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s ease;
            border-radius: 4px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
        }

        /* Main Content */
        .main-content {
            padding: 2rem;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .welcome-message {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            color: var(--primary-color);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .recent-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .recent-section {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .recent-section h2 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .message-list,
        .product-list {
            list-style: none;
        }

        .message-item,
        .product-item {
            padding: 1rem 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .message-item:last-child,
        .product-item:last-child {
            border-bottom: none;
        }

        .message-header,
        .product-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .message-subject,
        .product-name {
            font-weight: 500;
        }

        .message-date,
        .product-date {
            font-size: 0.875rem;
            color: #666;
        }

        .message-preview {
            font-size: 0.875rem;
            color: #666;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-new {
            background: var(--warning-color);
            color: #000;
        }

        .logout-btn {
            color: var(--white);
            text-decoration: none;
            padding: 0.5rem 1rem;
            background: var(--accent-color);
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        @media (max-width: 768px) {
            .admin-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .main-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="index.php" class="active"><i class="fas fa-dashboard"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-tshirt"></i> Products</a></li>
                <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="dashboard-header">
                <h1 class="welcome-message">Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h1>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <div class="stat-number"><?php echo $stats['products']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Categories</h3>
                    <div class="stat-number"><?php echo $stats['categories']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Messages</h3>
                    <div class="stat-number"><?php echo $stats['messages']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Unread Messages</h3>
                    <div class="stat-number"><?php echo $stats['unread_messages']; ?></div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="recent-grid">
                <!-- Recent Messages -->
                <div class="recent-section">
                    <h2>Recent Messages</h2>
                    <ul class="message-list">
                        <?php foreach ($recent_messages as $message): ?>
                        <li class="message-item">
                            <div class="message-header">
                                <span class="message-subject"><?php echo htmlspecialchars($message['subject']); ?></span>
                                <span class="status-badge status-<?php echo $message['status']; ?>">
                                    <?php echo ucfirst($message['status']); ?>
                                </span>
                            </div>
                            <div class="message-date">
                                <?php echo date('M d, Y', strtotime($message['created_at'])); ?>
                            </div>
                            <div class="message-preview">
                                <?php echo htmlspecialchars(substr($message['message'], 0, 100)) . '...'; ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Recent Products -->
                <div class="recent-section">
                    <h2>Recent Products</h2>
                    <ul class="product-list">
                        <?php foreach ($recent_products as $product): ?>
                        <li class="product-item">
                            <div class="product-header">
                                <span class="product-name"><?php echo htmlspecialchars($product['name']); ?></span>
                                <span class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></span>
                            </div>
                            <div class="product-date">
                                Added: <?php echo date('M d, Y', strtotime($product['created_at'])); ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 