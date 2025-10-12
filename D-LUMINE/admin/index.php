<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include_once '../config/database.php';
include_once '../models/Product.php';
include_once '../models/Order.php';
include_once '../models/Contact.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$order = new Order($db);
$contact = new Contact($db);

$products_count = $product->read()->rowCount();
$orders_count = $order->readAll()->rowCount();
$contacts_count = $contact->readAll()->rowCount();
$unread_messages = $contact->getUnreadCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - D'LUMINE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f5f5; }
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #2c3e50; color: white; }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #34495e; }
        .sidebar-header h2 { font-family: 'Playfair Display', serif; }
        .sidebar nav ul { list-style: none; }
        .sidebar nav li { border-bottom: 1px solid #34495e; }
        .sidebar nav a { display: block; padding: 15px 20px; color: white; text-decoration: none; transition: background 0.3s; }
        .sidebar nav a:hover { background: #34495e; }
        .sidebar nav a.active { background: #000080; }
        .content { flex: 1; padding: 20px; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .stat-card i { font-size: 2rem; margin-bottom: 10px; color: #000080; }
        .stat-number { font-size: 2rem; font-weight: bold; display: block; }
        .stat-label { color: #666; }
        .badge { background: #e74c3c; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem; margin-left: 5px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>D'LUMINE Admin</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="products.php"><i class="fas fa-gem"></i> Products</a></li>
                    <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                    <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages 
                        <?php if($unread_messages > 0): ?>
                            <span class="badge"><?php echo $unread_messages; ?></span>
                        <?php endif; ?>
                    </a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>
        <div class="content">
            <h1>Dashboard</h1>
            <div class="stats">
                <div class="stat-card">
                    <i class="fas fa-gem"></i>
                    <span class="stat-number"><?php echo $products_count; ?></span>
                    <div class="stat-label">Total Products</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="stat-number"><?php echo $orders_count; ?></span>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-envelope"></i>
                    <span class="stat-number"><?php echo $contacts_count; ?></span>
                    <div class="stat-label">Messages</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-eye"></i>
                    <span class="stat-number"><?php echo $unread_messages; ?></span>
                    <div class="stat-label">Unread Messages</div>
                </div>
            </div>
            
            <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2>Quick Actions</h2>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 15px;">
                    <a href="products.php?action=add" style="background: #000080; color: white; padding: 15px; text-align: center; text-decoration: none; border-radius: 5px;">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                    <a href="orders.php" style="background: #27ae60; color: white; padding: 15px; text-align: center; text-decoration: none; border-radius: 5px;">
                        <i class="fas fa-list"></i> View Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>