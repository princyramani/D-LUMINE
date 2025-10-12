<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include_once '../config/database.php';
include_once '../models/Order.php';
include_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);

$message = '';
$message_type = '';

// Handle status update
if(isset($_POST['update_status'])) {
    $order->id = $_POST['order_id'];
    $order->status = $_POST['status'];
    
    if($order->updateStatus()) {
        $message = "Order status updated successfully!";
        $message_type = "success";
    } else {
        $message = "Failed to update order status.";
        $message_type = "error";
    }
}

// Handle order deletion
if(isset($_GET['delete_id'])) {
    // First delete order items, then the order
    // This would require additional methods in your Order model
    $message = "Order deletion feature would be implemented here.";
    $message_type = "info";
}

// Get all orders
$orders = $order->readAll();

// Get status counts for statistics
$status_counts = [
    'pending' => 0,
    'confirmed' => 0,
    'shipped' => 0,
    'delivered' => 0,
    'cancelled' => 0
];

$total_orders = 0;
$total_revenue = 0;

// Calculate statistics
$orders_data = $orders->fetchAll(PDO::FETCH_ASSOC);
foreach($orders_data as $order_item) {
    $status_counts[$order_item['status']]++;
    $total_orders++;
    $total_revenue += $order_item['total_amount'];
}

// Re-create the statement for display
$orders = $order->readAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - D'LUMINE Admin</title>
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
        .info { 
            background: #d1ecf1; 
            color: #0c5460; 
            border: 1px solid #bee5eb; 
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
        .btn-warning { 
            background: #ffc107; 
            color: black; 
        }
        .btn-warning:hover { 
            background: #e0a800; 
        }
        .btn-info { 
            background: #17a2b8; 
            color: white; 
        }
        .btn-info:hover { 
            background: #138496; 
        }
        .btn-danger { 
            background: #dc3545; 
            color: white; 
        }
        .btn-danger:hover { 
            background: #c82333; 
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        .orders-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eaeaea;
        }
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            border-top: 4px solid #000080;
        }
        .stat-card.total { border-top-color: #000080; }
        .stat-card.pending { border-top-color: #ffc107; }
        .stat-card.confirmed { border-top-color: #17a2b8; }
        .stat-card.shipped { border-top-color: #007bff; }
        .stat-card.delivered { border-top-color: #28a745; }
        .stat-card.cancelled { border-top-color: #dc3545; }
        .stat-card.revenue { border-top-color: #6f42c1; }
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .stat-card.total i { color: #000080; }
        .stat-card.pending i { color: #ffc107; }
        .stat-card.confirmed i { color: #17a2b8; }
        .stat-card.shipped i { color: #007bff; }
        .stat-card.delivered i { color: #28a745; }
        .stat-card.cancelled i { color: #dc3545; }
        .stat-card.revenue i { color: #6f42c1; }
        .stat-number {
            font-size: 1.8rem;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .filter-group {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        .filter-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }
        .orders-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eaeaea;
        }
        table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #cce7ff; color: #004085; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .order-actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        .customer-info {
            font-size: 0.9rem;
            color: #666;
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
            max-width: 600px;
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
        .order-details .detail-section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f5f5f5;
        }
        .detail-section h4 {
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        .detail-value {
            color: #333;
        }
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .order-items th,
        .order-items td {
            padding: 10px;
            border-bottom: 1px solid #eaeaea;
            text-align: left;
        }
        .order-items th {
            background: #f8f9fa;
            font-weight: 600;
        }
        @media (max-width: 1200px) {
            .stats-cards {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
            }
            .stats-cards {
                grid-template-columns: 1fr 1fr;
            }
            .orders-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            .filter-group {
                flex-direction: column;
                align-items: flex-start;
            }
            table {
                display: block;
                overflow-x: auto;
            }
            .order-actions {
                flex-direction: column;
            }
        }
        @media (max-width: 576px) {
            .stats-cards {
                grid-template-columns: 1fr;
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
                    <li><a href="orders.php" class="active"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                    <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Header -->
            <div class="orders-header">
                <h1>Orders Management</h1>
                <div class="header-actions">
                    <button class="btn btn-primary" onclick="exportOrders()">
                        <i class="fas fa-download"></i> Export Orders
                    </button>
                </div>
            </div>

            <!-- Status Message -->
            <?php if($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="stats-cards">
                <div class="stat-card total">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="stat-number"><?php echo $total_orders; ?></span>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-card revenue">
                    <i class="fas fa-rupee-sign"></i>
                    <span class="stat-number">₹<?php echo number_format($total_revenue, 2); ?></span>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-card pending">
                    <i class="fas fa-clock"></i>
                    <span class="stat-number"><?php echo $status_counts['pending']; ?></span>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card confirmed">
                    <i class="fas fa-check-circle"></i>
                    <span class="stat-number"><?php echo $status_counts['confirmed']; ?></span>
                    <div class="stat-label">Confirmed</div>
                </div>
                <div class="stat-card shipped">
                    <i class="fas fa-shipping-fast"></i>
                    <span class="stat-number"><?php echo $status_counts['shipped']; ?></span>
                    <div class="stat-label">Shipped</div>
                </div>
                <div class="stat-card delivered">
                    <i class="fas fa-box-open"></i>
                    <span class="stat-number"><?php echo $status_counts['delivered']; ?></span>
                    <div class="stat-label">Delivered</div>
                </div>
                <div class="stat-card cancelled">
                    <i class="fas fa-times-circle"></i>
                    <span class="stat-number"><?php echo $status_counts['cancelled']; ?></span>
                    <div class="stat-label">Cancelled</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters">
                <div class="filter-group">
                    <select class="filter-select" id="statusFilter" onchange="filterOrders()">
                        <option value="all">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    
                    <select class="filter-select" id="sortFilter" onchange="sortOrders()">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="price_high">Highest Amount</option>
                        <option value="price_low">Lowest Amount</option>
                    </select>
                    
                    <input type="text" class="filter-select" id="searchFilter" placeholder="Search orders..." onkeyup="searchOrders()">
                </div>
            </div>

            <!-- Orders Table -->
            <div class="orders-table">
                <?php if($orders->rowCount() > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($order = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr class="order-row" data-status="<?php echo $order['status']; ?>" data-amount="<?php echo $order['total_amount']; ?>" data-date="<?php echo $order['created_at']; ?>">
                                    <td>
                                        <strong>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></strong>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <?php 
                                            // In a real application, you'd fetch customer name from users table
                                            echo "Customer #" . $order['user_id']; 
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo date('M j, Y', strtotime($order['created_at'])); ?>
                                    </td>
                                    <td>
                                        <strong>₹<?php echo number_format($order['total_amount'], 2); ?></strong>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $order['status']; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span style="color: #28a745;">
                                            <?php echo ucfirst($order['payment_method'] ?? 'COD'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="order-actions">
                                            <button class="btn btn-info btn-sm view-order" 
                                                    data-order='<?php echo json_encode($order); ?>'>
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            
                                            <button class="btn btn-warning btn-sm update-status" 
                                                    data-order-id="<?php echo $order['id']; ?>"
                                                    data-current-status="<?php echo $order['status']; ?>">
                                                <i class="fas fa-edit"></i> Status
                                            </button>
                                            
                                            <a href="orders.php?delete_id=<?php echo $order['id']; ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>No Orders Yet</h3>
                        <p>Customer orders will appear here when they make purchases on your website.</p>
                        <a href="products.php" class="btn btn-primary" style="margin-top: 15px;">
                            <i class="fas fa-gem"></i> Manage Products
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal" id="orderModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Order Details</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="order-details" id="orderDetails">
                <!-- Order details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal" id="statusModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Order Status</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="status-update">
                <form method="POST" id="statusForm">
                    <input type="hidden" name="order_id" id="statusOrderId">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Select Status:</label>
                        <select name="status" class="filter-select" style="width: 100%;" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="order-actions">
                        <button type="submit" name="update_status" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                        <button type="button" class="btn btn-secondary close-status-modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Order Details Modal
        document.addEventListener('DOMContentLoaded', function() {
            const orderModal = document.getElementById('orderModal');
            const statusModal = document.getElementById('statusModal');
            const closeModals = document.querySelectorAll('.close-modal, .close-status-modal');
            const orderDetails = document.getElementById('orderDetails');
            const statusForm = document.getElementById('statusForm');
            
            // View order buttons
            document.querySelectorAll('.view-order').forEach(button => {
                button.addEventListener('click', function() {
                    const orderData = JSON.parse(this.getAttribute('data-order'));
                    
                    // Format date
                    const orderDate = new Date(orderData.created_at);
                    const formattedDate = orderDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    // Populate modal content
                    orderDetails.innerHTML = `
                        <div class="detail-section">
                            <h4>Order Information</h4>
                            <div class="detail-row">
                                <span class="detail-label">Order ID:</span>
                                <span class="detail-value">#${orderData.id.toString().padStart(6, '0')}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Order Date:</span>
                                <span class="detail-value">${formattedDate}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value">
                                    <span class="status-badge status-${orderData.status}">
                                        ${orderData.status.charAt(0).toUpperCase() + orderData.status.slice(1)}
                                    </span>
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Total Amount:</span>
                                <span class="detail-value"><strong>₹${parseFloat(orderData.total_amount).toFixed(2)}</strong></span>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <h4>Customer Information</h4>
                            <div class="detail-row">
                                <span class="detail-label">Customer ID:</span>
                                <span class="detail-value">${orderData.user_id}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Payment Method:</span>
                                <span class="detail-value">${orderData.payment_method || 'Cash on Delivery'}</span>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <h4>Shipping Address</h4>
                            <div class="detail-value" style="background: #f8f9fa; padding: 15px; border-radius: 5px; white-space: pre-wrap;">
                                ${orderData.shipping_address || 'No shipping address provided.'}
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <h4>Order Items</h4>
                            <div style="color: #666; font-style: italic;">
                                Order items details would be displayed here in a complete implementation.
                            </div>
                        </div>
                        
                        <div class="order-actions" style="margin-top: 20px;">
                            <button class="btn btn-warning update-status" 
                                    data-order-id="${orderData.id}"
                                    data-current-status="${orderData.status}">
                                <i class="fas fa-edit"></i> Update Status
                            </button>
                            <a href="mailto:customer@example.com" class="btn btn-primary">
                                <i class="fas fa-envelope"></i> Contact Customer
                            </a>
                        </div>
                    `;
                    
                    // Show modal
                    orderModal.classList.add('active');
                });
            });
            
            // Update status buttons
            document.querySelectorAll('.update-status').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.getAttribute('data-order-id');
                    const currentStatus = this.getAttribute('data-current-status');
                    
                    // Set form values
                    document.getElementById('statusOrderId').value = orderId;
                    document.querySelector('select[name="status"]').value = currentStatus;
                    
                    // Show status modal
                    statusModal.classList.add('active');
                });
            });
            
            // Close modals
            closeModals.forEach(button => {
                button.addEventListener('click', function() {
                    orderModal.classList.remove('active');
                    statusModal.classList.remove('active');
                });
            });
            
            // Close modal when clicking outside
            [orderModal, statusModal].forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if(e.target === modal) {
                        modal.classList.remove('active');
                    }
                });
            });
        });
        
        // Filtering and Sorting Functions
        function filterOrders() {
            const status = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.order-row');
            
            rows.forEach(row => {
                if (status === 'all' || row.getAttribute('data-status') === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        function sortOrders() {
            const sortBy = document.getElementById('sortFilter').value;
            const tbody = document.querySelector('tbody');
            const rows = Array.from(document.querySelectorAll('.order-row'));
            
            rows.sort((a, b) => {
                switch(sortBy) {
                    case 'newest':
                        return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
                    case 'oldest':
                        return new Date(a.getAttribute('data-date')) - new Date(b.getAttribute('data-date'));
                    case 'price_high':
                        return parseFloat(b.getAttribute('data-amount')) - parseFloat(a.getAttribute('data-amount'));
                    case 'price_low':
                        return parseFloat(a.getAttribute('data-amount')) - parseFloat(b.getAttribute('data-amount'));
                    default:
                        return 0;
                }
            });
            
            // Clear and re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
        }
        
        function searchOrders() {
            const searchTerm = document.getElementById('searchFilter').value.toLowerCase();
            const rows = document.querySelectorAll('.order-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        function exportOrders() {
            // In a complete implementation, this would generate a CSV or PDF
            alert('Export functionality would be implemented here. This could generate a CSV file with all order data.');
        }
        
        // Auto-refresh orders every 60 seconds
        setInterval(() => {
            // You can implement AJAX refresh here if needed
        }, 60000);
    </script>
</body>
</html>