<?php
require_once '../includes/db.php';
require_once 'includes/auth_check.php';

$success_message = '';
$error_message = '';

// Handle status update
if (isset($_POST['update_status']) && isset($_POST['message_id']) && isset($_POST['status'])) {
    try {
        $stmt = $pdo->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $_POST['message_id']]);
        $success_message = "Message status updated successfully!";
    } catch (PDOException $e) {
        $error_message = "Failed to update message status.";
    }
}

// Handle delete action
if (isset($_POST['delete_message']) && isset($_POST['message_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$_POST['message_id']]);
        $success_message = "Message deleted successfully!";
    } catch (PDOException $e) {
        $error_message = "Failed to delete message.";
    }
}

// Get filters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare base query
$query = "SELECT * FROM contact_messages WHERE 1=1";
$params = [];

// Add filters
if ($status_filter !== '') {
    $query .= " AND status = ?";
    $params[] = $status_filter;
}

if ($search !== '') {
    $query .= " AND (name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
}

// Get total count for pagination
$count_stmt = $pdo->prepare($query);
$count_stmt->execute($params);
$total_messages = $count_stmt->rowCount();

// Pagination
$items_per_page = 10;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$total_pages = ceil($total_messages / $items_per_page);
$offset = ($current_page - 1) * $items_per_page;

// Add sorting and pagination to query
$query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
$params[] = $items_per_page;
$params[] = $offset;

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$messages = $stmt->fetchAll();

// Get message counts by status
$status_counts = $pdo->query("
    SELECT status, COUNT(*) as count 
    FROM contact_messages 
    GROUP BY status
")->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages Management - T-Shirt Printing Sri Lanka</title>
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

        /* Sidebar styles */
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

        .page-header {
            margin-bottom: 2rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 0.875rem;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary-color);
        }

        /* Filters */
        .filters {
            background: var(--white);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .filter-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        /* Messages Table */
        .messages-table {
            width: 100%;
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        .messages-table th,
        .messages-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }

        .messages-table th {
            background: var(--primary-color);
            color: var(--white);
            font-weight: 500;
        }

        .messages-table th:first-child {
            border-top-left-radius: 8px;
        }

        .messages-table th:last-child {
            border-top-right-radius: 8px;
        }

        .messages-table tr:last-child td {
            border-bottom: none;
        }

        .message-preview {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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

        .status-read {
            background: var(--secondary-color);
            color: var(--white);
        }

        .status-replied {
            background: var(--success-color);
            color: var(--white);
        }

        .message-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            border: none;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            color: var(--white);
        }

        .btn-view {
            background: var(--secondary-color);
        }

        .btn-view:hover {
            background: #2980b9;
        }

        .btn-delete {
            background: var(--accent-color);
        }

        .btn-delete:hover {
            background: #c0392b;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--white);
            padding: 2rem;
            border-radius: 8px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-color);
        }

        .message-details {
            margin-bottom: 1.5rem;
        }

        .message-details p {
            margin-bottom: 0.5rem;
        }

        .message-content {
            background: var(--light-gray);
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            white-space: pre-wrap;
        }

        .status-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .page-link {
            padding: 0.5rem 1rem;
            background: var(--white);
            color: var(--text-color);
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .page-link:hover,
        .page-link.active {
            background: var(--secondary-color);
            color: var(--white);
        }

        /* Alerts */
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

            .messages-table {
                display: block;
                overflow-x: auto;
            }

            .message-preview {
                max-width: 200px;
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
                <li><a href="index.php"><i class="fas fa-dashboard"></i> Dashboard</a></li>
                <li><a href="products.php"><i class="fas fa-tshirt"></i> Products</a></li>
                <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                <li><a href="messages.php" class="active"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <h1>Messages Management</h1>
            </div>

            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Messages</h3>
                    <div class="stat-number"><?php echo array_sum($status_counts); ?></div>
                </div>
                <div class="stat-card">
                    <h3>New Messages</h3>
                    <div class="stat-number"><?php echo $status_counts['new'] ?? 0; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Read Messages</h3>
                    <div class="stat-number"><?php echo $status_counts['read'] ?? 0; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Replied Messages</h3>
                    <div class="stat-number"><?php echo $status_counts['replied'] ?? 0; ?></div>
                </div>
            </div>

            <!-- Filters -->
            <form class="filters" method="GET">
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="filter-control">
                        <option value="">All Status</option>
                        <option value="new" <?php echo $status_filter === 'new' ? 'selected' : ''; ?>>New</option>
                        <option value="read" <?php echo $status_filter === 'read' ? 'selected' : ''; ?>>Read</option>
                        <option value="replied" <?php echo $status_filter === 'replied' ? 'selected' : ''; ?>>Replied</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="search" class="filter-control" 
                           value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Search messages...">
                </div>

                <div class="filter-group" style="flex: 0 0 auto; align-self: flex-end;">
                    <button type="submit" class="btn btn-view">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>

            <!-- Messages Table -->
            <table class="messages-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($messages)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No messages found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($message['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($message['name']); ?></td>
                            <td><?php echo htmlspecialchars($message['subject']); ?></td>
                            <td class="message-preview"><?php echo htmlspecialchars($message['message']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $message['status']; ?>">
                                    <?php echo ucfirst($message['status']); ?>
                                </span>
                            </td>
                            <td class="message-actions">
                                <button class="btn btn-view" onclick="showMessage(<?php 
                                    echo htmlspecialchars(json_encode([
                                        'id' => $message['id'],
                                        'name' => $message['name'],
                                        'email' => $message['email'],
                                        'subject' => $message['subject'],
                                        'message' => $message['message'],
                                        'created_at' => $message['created_at'],
                                        'status' => $message['status']
                                    ])); 
                                ?>)">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <form method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                    <button type="submit" name="delete_message" class="btn btn-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?><?php echo $status_filter ? '&status=' . $status_filter : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="page-link">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?><?php echo $status_filter ? '&status=' . $status_filter : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="page-link <?php echo $current_page === $i ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?><?php echo $status_filter ? '&status=' . $status_filter : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="page-link">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Message View Modal -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Message Details</h2>
                <button class="modal-close" onclick="hideModal()">&times;</button>
            </div>
            <div class="message-details">
                <p><strong>From:</strong> <span id="messageFrom"></span></p>
                <p><strong>Email:</strong> <span id="messageEmail"></span></p>
                <p><strong>Subject:</strong> <span id="messageSubject"></span></p>
                <p><strong>Date:</strong> <span id="messageDate"></span></p>
                <p><strong>Status:</strong> <span id="messageStatus"></span></p>
            </div>
            <div class="message-content" id="messageContent"></div>
            <div class="status-actions">
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="message_id" id="messageId">
                    <input type="hidden" name="update_status" value="1">
                    <button type="submit" name="status" value="read" class="btn btn-view">
                        <i class="fas fa-check"></i> Mark as Read
                    </button>
                    <button type="submit" name="status" value="replied" class="btn btn-view">
                        <i class="fas fa-reply"></i> Mark as Replied
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showMessage(data) {
            const modal = document.getElementById('messageModal');
            document.getElementById('messageFrom').textContent = data.name;
            document.getElementById('messageEmail').textContent = data.email;
            document.getElementById('messageSubject').textContent = data.subject;
            document.getElementById('messageDate').textContent = new Date(data.created_at).toLocaleString();
            document.getElementById('messageStatus').textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
            document.getElementById('messageContent').textContent = data.message;
            document.getElementById('messageId').value = data.id;
            modal.classList.add('active');

            // If message is new, automatically mark it as read
            if (data.status === 'new') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="message_id" value="${data.id}">
                    <input type="hidden" name="update_status" value="1">
                    <input type="hidden" name="status" value="read">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function hideModal() {
            const modal = document.getElementById('messageModal');
            modal.classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideModal();
            }
        });
    </script>
</body>
</html> 