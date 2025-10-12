<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include_once '../config/database.php';
include_once '../models/Contact.php';

$database = new Database();
$db = $database->getConnection();
$contact = new Contact($db);

$message = '';
$message_type = '';

// Handle mark as read
if(isset($_GET['mark_read'])) {
    $contact->id = $_GET['mark_read'];
    if($contact->markAsRead()) {
        $message = "Message marked as read!";
        $message_type = "success";
    } else {
        $message = "Failed to mark message as read.";
        $message_type = "error";
    }
}

// Handle delete message
if(isset($_GET['delete_id'])) {
    $contact->id = $_GET['delete_id'];
    if($contact->delete()) {
        $message = "Message deleted successfully!";
        $message_type = "success";
    } else {
        $message = "Failed to delete message.";
        $message_type = "error";
    }
}

// Handle mark all as read
if(isset($_POST['mark_all_read'])) {
    $stmt = $contact->readAll();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $contact->id = $row['id'];
        $contact->markAsRead();
    }
    $message = "All messages marked as read!";
    $message_type = "success";
}

// Get all messages
$messages = $contact->readAll();
$unread_count = $contact->getUnreadCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - D'LUMINE Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background: #f5f5f5; 
        }
        .admin-container { 
            display: flex; 
            min-height: 100vh; 
        }
        .sidebar { 
            width: 250px; 
            background: #2c3e50; 
            color: white; 
        }
        .sidebar-header { 
            padding: 20px; 
            border-bottom: 1px solid #34495e; 
        }
        .sidebar-header h2 { 
            font-family: 'Playfair Display', serif; 
        }
        .sidebar nav ul { 
            list-style: none; 
        }
        .sidebar nav li { 
            border-bottom: 1px solid #34495e; 
        }
        .sidebar nav a { 
            display: block; 
            padding: 15px 20px; 
            color: white; 
            text-decoration: none; 
            transition: background 0.3s; 
        }
        .sidebar nav a:hover { 
            background: #34495e; 
        }
        .sidebar nav a.active { 
            background: #000080; 
        }
        .content { 
            flex: 1; 
            padding: 20px; 
        }
        .message { 
            padding: 12px 20px; 
            margin-bottom: 20px; 
            border-radius: 5px; 
            font-weight: 500; 
        }
        .success { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        .error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        .btn { 
            padding: 8px 15px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            text-decoration: none; 
            display: inline-block; 
            font-size: 0.9rem; 
            transition: all 0.3s ease; 
        }
        .btn-primary { 
            background: #000080; 
            color: white; 
        }
        .btn-primary:hover { 
            background: #000060; 
        }
        .btn-success { 
            background: #28a745; 
            color: white; 
        }
        .btn-success:hover { 
            background: #218838; 
        }
        .btn-danger { 
            background: #dc3545; 
            color: white; 
        }
        .btn-danger:hover { 
            background: #c82333; 
        }
        .btn-info { 
            background: #17a2b8; 
            color: white; 
        }
        .btn-info:hover { 
            background: #138496; 
        }
        .messages-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eaeaea;
        }
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #000080;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        .message-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #000080;
            transition: all 0.3s ease;
        }
        .message-item.unread {
            background: #f0f8ff;
            border-left-color: #ff6b6b;
        }
        .message-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .message-sender {
            font-weight: 600;
            color: #2c3e50;
        }
        .message-email {
            color: #000080;
            text-decoration: none;
        }
        .message-email:hover {
            text-decoration: underline;
        }
        .message-date {
            color: #666;
            font-size: 0.9rem;
        }
        .message-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .message-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .badge {
            background: #e74c3c;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-left: 10px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .empty-state i {
            font-size: 4rem;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        .empty-state h3 {
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }
        .message-detail .detail-row {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f5f5f5;
        }
        .detail-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #555;
            line-height: 1.6;
        }
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
            }
            .stats-cards {
                grid-template-columns: 1fr;
            }
            .message-header {
                flex-direction: column;
                gap: 10px;
            }
            .message-actions {
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>D'LUMINE Admin</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="products.php"><i class="fas fa-gem"></i> Products</a></li>
                    <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                    <li><a href="messages.php" class="active">
                        <i class="fas fa-envelope"></i> Messages 
                        <?php if($unread_count > 0): ?>
                            <span class="badge"><?php echo $unread_count; ?></span>
                        <?php endif; ?>
                    </a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Header -->
            <div class="messages-header">
                <h1>Customer Messages</h1>
                <form method="POST" style="display: inline;">
                    <button type="submit" name="mark_all_read" class="btn btn-primary">
                        <i class="fas fa-check-double"></i> Mark All as Read
                    </button>
                </form>
            </div>

            <!-- Status Message -->
            <?php if($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <i class="fas fa-envelope"></i>
                    <span class="stat-number"><?php echo $messages->rowCount(); ?></span>
                    <div class="stat-label">Total Messages</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-envelope-open"></i>
                    <span class="stat-number"><?php echo $messages->rowCount() - $unread_count; ?></span>
                    <div class="stat-label">Read Messages</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-envelope"></i>
                    <span class="stat-number"><?php echo $unread_count; ?></span>
                    <div class="stat-label">Unread Messages</div>
                </div>
            </div>

            <!-- Messages List -->
            <div class="messages-list">
                <?php if($messages->rowCount() > 0): ?>
                    <?php while($msg = $messages->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="message-item <?php echo !$msg['is_read'] ? 'unread' : ''; ?>" 
                             data-message-id="<?php echo $msg['id']; ?>">
                            <div class="message-header">
                                <div>
                                    <div class="message-sender">
                                        <?php echo htmlspecialchars($msg['name']); ?>
                                        <?php if(!$msg['is_read']): ?>
                                            <span class="badge">New</span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" 
                                       class="message-email">
                                        <?php echo htmlspecialchars($msg['email']); ?>
                                    </a>
                                </div>
                                <div class="message-date">
                                    <?php echo date('M j, Y g:i A', strtotime($msg['created_at'])); ?>
                                </div>
                            </div>
                            <div class="message-content">
                                <?php 
                                $message_preview = strlen($msg['message']) > 150 
                                    ? substr($msg['message'], 0, 150) . '...' 
                                    : $msg['message'];
                                echo nl2br(htmlspecialchars($message_preview));
                                ?>
                            </div>
                            <div class="message-actions">
                                <button class="btn btn-info view-message" 
                                        data-id="<?php echo $msg['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($msg['name']); ?>"
                                        data-email="<?php echo htmlspecialchars($msg['email']); ?>"
                                        data-message="<?php echo htmlspecialchars($msg['message']); ?>"
                                        data-date="<?php echo $msg['created_at']; ?>"
                                        data-read="<?php echo $msg['is_read']; ?>">
                                    <i class="fas fa-eye"></i> View Full Message
                                </button>
                                
                                <?php if(!$msg['is_read']): ?>
                                    <a href="messages.php?mark_read=<?php echo $msg['id']; ?>" 
                                       class="btn btn-success">
                                        <i class="fas fa-check"></i> Mark as Read
                                    </a>
                                <?php endif; ?>
                                
                                <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" 
                                   class="btn btn-primary">
                                    <i class="fas fa-reply"></i> Reply
                                </a>
                                
                                <a href="messages.php?delete_id=<?php echo $msg['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this message?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>No Messages Yet</h3>
                        <p>Customer messages will appear here when they contact you through the website.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Message Detail Modal -->
    <div class="modal" id="messageModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Message Details</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="message-detail" id="messageDetail">
                <!-- Message details will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // View Message Modal
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('messageModal');
            const closeModal = document.querySelector('.close-modal');
            const messageDetail = document.getElementById('messageDetail');
            
            // View message buttons
            document.querySelectorAll('.view-message').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const message = this.getAttribute('data-message');
                    const date = this.getAttribute('data-date');
                    const isRead = this.getAttribute('data-read');
                    
                    // Format date
                    const messageDate = new Date(date);
                    const formattedDate = messageDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    // Populate modal content
                    messageDetail.innerHTML = `
                        <div class="detail-row">
                            <div class="detail-label">From</div>
                            <div class="detail-value">
                                <strong>${name}</strong><br>
                                <a href="mailto:${email}">${email}</a>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Date</div>
                            <div class="detail-value">${formattedDate}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span class="badge" style="background: ${isRead === '1' ? '#28a745' : '#dc3545'}">
                                    ${isRead === '1' ? 'Read' : 'Unread'}
                                </span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Message</div>
                            <div class="detail-value" style="white-space: pre-wrap; background: #f8f9fa; padding: 15px; border-radius: 5px;">
                                ${message}
                            </div>
                        </div>
                        <div class="message-actions" style="margin-top: 20px;">
                            <a href="mailto:${email}" class="btn btn-primary">
                                <i class="fas fa-reply"></i> Reply to ${name}
                            </a>
                            ${isRead === '0' ? `
                                <a href="messages.php?mark_read=${id}" class="btn btn-success">
                                    <i class="fas fa-check"></i> Mark as Read
                                </a>
                            ` : ''}
                            <a href="messages.php?delete_id=${id}" class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this message?')">
                                <i class="fas fa-trash"></i> Delete Message
                            </a>
                        </div>
                    `;
                    
                    // Show modal
                    modal.classList.add('active');
                    
                    // If message is unread, mark it as read
                    if(isRead === '0') {
                        // You could add AJAX here to mark as read without page reload
                    }
                });
            });
            
            // Close modal
            closeModal.addEventListener('click', function() {
                modal.classList.remove('active');
            });
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if(e.target === modal) {
                    modal.classList.remove('active');
                }
            });
            
            // Auto-mark as read when viewing unread messages
            document.querySelectorAll('.view-message[data-read="0"]').forEach(button => {
                button.addEventListener('click', function() {
                    const messageId = this.getAttribute('data-id');
                    const messageItem = this.closest('.message-item');
                    
                    // Remove unread styling
                    setTimeout(() => {
                        messageItem.classList.remove('unread');
                        const badge = messageItem.querySelector('.badge');
                        if(badge) {
                            badge.remove();
                        }
                    }, 1000);
                });
            });
        });
        
        // Auto-refresh messages every 30 seconds
        setInterval(() => {
            // You can implement AJAX refresh here if needed
        }, 30000);
    </script>
</body>
</html>