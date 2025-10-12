<?php
session_start();
include_once '../config/database.php';
include_once '../models/Order.php';

// Redirect to login if not authenticated
if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);

// Get user's orders
$orders = $order->readByUser($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - D'LUMINE</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .orders-container {
            padding: 100px 0 50px;
            background: #f9f7f4;
            min-height: 100vh;
        }
        .orders-content {
            max-width: 1000px;
            margin: 0 auto;
        }
        .order-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 4px solid #000080;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }
        .order-id {
            font-weight: 600;
            color: #000080;
            font-size: 1.1rem;
        }
        .order-date {
            color: #666;
        }
        .order-status {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #cce7ff; color: #004085; }
        .status-delivered { background: #d4edda; color: #155724; }
        .order-details {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 20px;
        }
        .order-amount {
            text-align: right;
            font-size: 1.2rem;
            font-weight: 600;
            color: #000080;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
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
        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .order-details {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            .order-amount {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <!-- Include your header -->
    <?php include '../header.php'; ?>

    <section class="orders-container">
        <div class="container">
            <h1 class="section-title">My Orders</h1>
            
            <div class="orders-content">
                <?php if($orders->rowCount() > 0): ?>
                    <?php while($order = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div>
                                    <div class="order-id">Order #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></div>
                                    <div class="order-date">Placed on <?php echo date('F j, Y', strtotime($order['created_at'])); ?></div>
                                </div>
                                <div class="order-status status-<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </div>
                            </div>
                            <div class="order-details">
                                <div>
                                    <strong>Shipping Address:</strong><br>
                                    <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                                </div>
                                <div>
                                    <strong>Payment Method:</strong><br>
                                    <?php echo ucfirst($order['payment_method']); ?>
                                </div>
                                <div class="order-amount">
                                    ₹<?php echo number_format($order['total_amount'], 2); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>No Orders Yet</h3>
                        <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                        <a href="../index.php#products" class="btn btn-primary" style="margin-top: 15px;">
                            Start Shopping
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>