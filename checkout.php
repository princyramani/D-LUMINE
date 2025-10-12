<?php
session_start();
include_once 'config/database.php';
include_once 'models/Product.php';
include_once 'models/Order.php';
include_once 'models/Payment.php';

// Redirect to login if not authenticated
if(!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = 'checkout.php';
    header("Location: auth/login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$order = new Order($db);
$payment = new Payment($db);

$error = '';
$success = '';

// Get cart from session
$cart = $_SESSION['cart'] ?? [];
$total_amount = 0;

// Calculate total
foreach($cart as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// Handle checkout
if($_POST && isset($_POST['place_order'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $shipping_address = $_POST['shipping_address'] ?? '';
    $billing_address = $_POST['billing_address'] ?? $shipping_address;
    $payment_method = $_POST['payment_method'] ?? 'cod';
    
    // Validation
    if(empty($name) || empty($email) || empty($phone) || empty($shipping_address)) {
        $error = "Please fill in all required fields.";
    } elseif(empty($cart)) {
        $error = "Your cart is empty.";
    } else {
        // Create order
        $order->user_id = $_SESSION['user_id'];
        $order->customer_name = $name;
        $order->customer_email = $email;
        $order->customer_phone = $phone;
        $order->total_amount = $total_amount;
        $order->status = 'pending';
        $order->shipping_address = $shipping_address;
        $order->billing_address = $billing_address;
        $order->payment_method = $payment_method;
        
        if($order->create()) {
            $order_id = $order->id;
            
            // Create payment record
            $payment->order_id = $order_id;
            $payment->payment_method = $payment_method;
            $payment->amount = $total_amount;
            $payment->payment_status = ($payment_method == 'cod') ? 'pending' : 'completed';
            $payment->transaction_id = 'TXN' . time() . rand(1000, 9999);
            
            if($payment->create()) {
                // Clear cart
                unset($_SESSION['cart']);
                $success = "Order placed successfully! Your order ID is #" . str_pad($order_id, 6, '0', STR_PAD_LEFT);
                
                // Redirect to success page
                header("Location: order-success.php?order_id=" . $order_id);
                exit;
            } else {
                $error = "Payment processing failed. Please try again.";
            }
        } else {
            $error = "Failed to create order. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - D'LUMINE</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .checkout-container {
            padding: 100px 0 50px;
            background: #f9f7f4;
            min-height: 100vh;
        }
        .checkout-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
            align-items: start;
        }
        .checkout-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .order-summary {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: sticky;
            top: 100px;
        }
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eaeaea;
        }
        .form-section h3 {
            margin-bottom: 20px;
            color: #000080;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        .form-group label.required::after {
            content: " *";
            color: #e74c3c;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #eaeaea;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #000080;
            box-shadow: 0 0 0 3px rgba(0,0,128,0.1);
        }
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .payment-method {
            border: 2px solid #eaeaea;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        .payment-method:hover {
            border-color: #000080;
        }
        .payment-method.selected {
            border-color: #000080;
            background: #f0f8ff;
            transform: translateY(-2px);
        }
        .payment-method i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #000080;
        }
        .payment-method .method-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .payment-method .method-desc {
            font-size: 0.8rem;
            color: #666;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eaeaea;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .item-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }
        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .item-details h4 {
            margin-bottom: 5px;
            font-size: 0.95rem;
        }
        .item-details .item-category {
            color: #666;
            font-size: 0.8rem;
            margin-bottom: 5px;
        }
        .item-details .item-quantity {
            color: #666;
            font-size: 0.9rem;
        }
        .item-price {
            color: #000080;
            font-weight: 600;
            text-align: right;
        }
        .order-totals {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eaeaea;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .total-row.final {
            font-size: 1.2rem;
            font-weight: 600;
            color: #000080;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 1px solid #eaeaea;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .checkbox-group input[type="checkbox"] {
            width: auto;
            transform: scale(1.2);
        }
        .payment-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
            display: none;
        }
        .payment-form.active {
            display: block;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .btn-checkout {
            width: 100%;
            padding: 15px;
            background: #000080;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        .btn-checkout:hover {
            background: #000060;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,128,0.3);
        }
        .btn-checkout:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        .empty-cart-message {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        .empty-cart-message i {
            font-size: 3rem;
            color: #bdc3c7;
            margin-bottom: 15px;
        }
        @media (max-width: 968px) {
            .checkout-content {
                grid-template-columns: 1fr;
            }
            .order-summary {
                position: static;
            }
        }
        @media (max-width: 768px) {
            .payment-methods {
                grid-template-columns: 1fr;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php"><img src="image/d'luminelogo.jfif" alt="D'LUMINE Logo"/>D'LUMINE</a>
                </div>
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php#products">Shop</a></li>
                        <li><a href="index.php#collaboration">Collaborations</a></li>
                        <li><a href="index.php#about">About</a></li>
                        <li><a href="index.php#contact">Contact</a></li>
                        <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                            <li><a href="admin/index.php">Admin</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="header-actions">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="user-dropdown">
                            <button class="icon-btn user-btn">
                                <i class="fas fa-user"></i>
                            </button>
                            <div class="dropdown-menu">
                                <p>Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                                <a href="user/orders.php">My Orders</a>
                                <a href="auth/logout.php">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="auth-buttons">
                            <a href="auth/login.php" class="btn-auth-small">Login</a>
                            <a href="auth/register.php" class="btn-auth-small primary">Sign Up</a>
                        </div>
                    <?php endif; ?>
                    
                    <button class="icon-btn cart-btn" onclick="window.location.href='index.php'">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="cart-count"><?php echo count($cart); ?></span>
                    </button>
                </div>
                <button class="mobile-menu-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <section class="checkout-container">
        <div class="container">
            <h1 class="section-title">Checkout</h1>

            <?php if($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if(empty($cart)): ?>
                <div class="empty-cart-message">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your cart is empty</h3>
                    <p>Add some beautiful jewelry items to your cart before proceeding to checkout.</p>
                    <a href="index.php#products" class="btn btn-primary" style="margin-top: 20px;">
                        Continue Shopping
                    </a>
                </div>
            <?php else: ?>
                <div class="checkout-content">
                    <!-- Checkout Form -->
                    <div class="checkout-form">
                        <form method="POST" id="checkoutForm">
                            <!-- Shipping Information -->
                            <div class="form-section">
                                <h3><i class="fas fa-shipping-fast"></i> Shipping Information</h3>
                                <div class="form-group">
                                    <label for="name" class="required">Full Name</label>
                                    <input type="text" id="name" name="name" class="form-control" required 
                                           value="<?php echo $_SESSION['user_name'] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email" class="required">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control" required 
                                           value="<?php echo $_SESSION['user_email'] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="required">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" required
                                           placeholder="+91 1234567890">
                                </div>
                                <div class="form-group">
                                    <label for="shipping_address" class="required">Shipping Address</label>
                                    <textarea id="shipping_address" name="shipping_address" class="form-control" rows="4" required
                                              placeholder="Enter your complete shipping address including street, city, state, and PIN code"></textarea>
                                </div>
                            </div>

                            <!-- Billing Information -->
                            <div class="form-section">
                                <h3><i class="fas fa-file-invoice"></i> Billing Information</h3>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="same_as_shipping" checked>
                                    <label for="same_as_shipping">Same as shipping address</label>
                                </div>
                                <div class="form-group" id="billing_address_group" style="display: none;">
                                    <label for="billing_address">Billing Address</label>
                                    <textarea id="billing_address" name="billing_address" class="form-control" rows="4"
                                              placeholder="Enter your billing address (if different from shipping)"></textarea>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="form-section">
                                <h3><i class="fas fa-credit-card"></i> Payment Method</h3>
                                <div class="payment-methods">
                                    <div class="payment-method selected" data-method="cod">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <div class="method-name">Cash on Delivery</div>
                                        <div class="method-desc">Pay when you receive</div>
                                    </div>
                                    <div class="payment-method" data-method="card">
                                        <i class="fas fa-credit-card"></i>
                                        <div class="method-name">Credit/Debit Card</div>
                                        <div class="method-desc">Secure card payment</div>
                                    </div>
                                    <div class="payment-method" data-method="upi">
                                        <i class="fas fa-mobile-alt"></i>
                                        <div class="method-name">UPI Payment</div>
                                        <div class="method-desc">Instant UPI transfer</div>
                                    </div>
                                </div>
                                <input type="hidden" name="payment_method" id="payment_method" value="cod">
                                
                                <!-- Card Payment Form -->
                                <div class="payment-form" id="card_payment">
                                    <h4 style="margin-bottom: 15px;">Card Details</h4>
                                    <div class="form-group">
                                        <label for="card_number" class="required">Card Number</label>
                                        <input type="text" id="card_number" name="card_number" class="form-control" 
                                               placeholder="1234 5678 9012 3456" maxlength="19">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="expiry_date" class="required">Expiry Date</label>
                                            <input type="text" id="expiry_date" name="expiry_date" class="form-control" 
                                                   placeholder="MM/YY" maxlength="5">
                                        </div>
                                        <div class="form-group">
                                            <label for="cvv" class="required">CVV</label>
                                            <input type="text" id="cvv" name="cvv" class="form-control" 
                                                   placeholder="123" maxlength="3">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="card_holder" class="required">Card Holder Name</label>
                                        <input type="text" id="card_holder" name="card_holder" class="form-control" 
                                               placeholder="John Doe">
                                    </div>
                                </div>
                                
                                <!-- UPI Payment Form -->
                                <div class="payment-form" id="upi_payment">
                                    <h4 style="margin-bottom: 15px;">UPI Details</h4>
                                    <div class="form-group">
                                        <label for="upi_id" class="required">UPI ID</label>
                                        <input type="text" id="upi_id" name="upi_id" class="form-control" 
                                               placeholder="yourname@upi">
                                    </div>
                                    <div style="background: #e8f4f8; padding: 15px; border-radius: 5px; margin-top: 15px;">
                                        <p style="margin: 0; font-size: 0.9rem; color: #0c5460;">
                                            <i class="fas fa-info-circle"></i> 
                                            You will be redirected to your UPI app for payment confirmation.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="place_order" class="btn-checkout" id="placeOrderBtn">
                                <i class="fas fa-lock"></i> Place Order - ₹<?php echo number_format($total_amount, 2); ?>
                            </button>
                        </form>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary">
                        <h3 style="margin-bottom: 20px;">Order Summary</h3>
                        <div class="order-items">
                            <?php foreach($cart as $item): ?>
                                <div class="order-item">
                                    <div class="item-info">
                                        <div class="item-image">
                                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                        </div>
                                        <div class="item-details">
                                            <h4><?php echo $item['name']; ?></h4>
                                            <div class="item-category"><?php echo ucfirst($item['category']); ?></div>
                                            <div class="item-quantity">Qty: <?php echo $item['quantity']; ?></div>
                                        </div>
                                    </div>
                                    <div class="item-price">
                                        ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-totals">
                            <div class="total-row">
                                <span>Subtotal:</span>
                                <span>₹<?php echo number_format($total_amount, 2); ?></span>
                            </div>
                            <div class="total-row">
                                <span>Shipping:</span>
                                <span>FREE</span>
                            </div>
                            <div class="total-row">
                                <span>Tax (GST):</span>
                                <span>₹<?php echo number_format($total_amount * 0.18, 2); ?></span>
                            </div>
                            <div class="total-row final">
                                <span>Total Amount:</span>
                                <span>₹<?php echo number_format($total_amount * 1.18, 2); ?></span>
                            </div>
                        </div>

                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 20px;">
                            <p style="margin: 0; font-size: 0.9rem; color: #666; text-align: center;">
                                <i class="fas fa-shield-alt"></i> 
                                Your payment information is secure and encrypted
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script>
        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                // Remove selected class from all methods
                document.querySelectorAll('.payment-method').forEach(m => {
                    m.classList.remove('selected');
                });
                
                // Add selected class to clicked method
                this.classList.add('selected');
                
                // Update hidden input
                const paymentMethod = this.getAttribute('data-method');
                document.getElementById('payment_method').value = paymentMethod;
                
                // Hide all payment forms
                document.querySelectorAll('.payment-form').forEach(form => {
                    form.classList.remove('active');
                });
                
                // Show relevant payment form
                if(paymentMethod === 'card') {
                    document.getElementById('card_payment').classList.add('active');
                } else if(paymentMethod === 'upi') {
                    document.getElementById('upi_payment').classList.add('active');
                }
            });
        });
        
        // Same as shipping address
        document.getElementById('same_as_shipping').addEventListener('change', function() {
            const billingGroup = document.getElementById('billing_address_group');
            if(this.checked) {
                billingGroup.style.display = 'none';
                document.getElementById('billing_address').value = '';
            } else {
                billingGroup.style.display = 'block';
            }
        });
        
        // Auto-format card number
        document.getElementById('card_number')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ');
            if (formattedValue) {
                e.target.value = formattedValue;
            }
        });
        
        // Auto-format expiry date
        document.getElementById('expiry_date')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
        
        // Form validation
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const paymentMethod = document.getElementById('payment_method').value;
            const placeOrderBtn = document.getElementById('placeOrderBtn');
            
            // Disable button to prevent double submission
            placeOrderBtn.disabled = true;
            placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing Order...';
            
            if(paymentMethod === 'card') {
                const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
                const expiryDate = document.getElementById('expiry_date').value;
                const cvv = document.getElementById('cvv').value;
                const cardHolder = document.getElementById('card_holder').value;
                
                if(!cardNumber || !expiryDate || !cvv || !cardHolder) {
                    e.preventDefault();
                    alert('Please fill in all card details.');
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.innerHTML = '<i class="fas fa-lock"></i> Place Order - ₹<?php echo number_format($total_amount * 1.18, 2); ?>';
                    return false;
                }
                
                if(cardNumber.length !== 16) {
                    e.preventDefault();
                    alert('Please enter a valid 16-digit card number.');
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.innerHTML = '<i class="fas fa-lock"></i> Place Order - ₹<?php echo number_format($total_amount * 1.18, 2); ?>';
                    return false;
                }
                
                if(cvv.length !== 3) {
                    e.preventDefault();
                    alert('Please enter a valid 3-digit CVV.');
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.innerHTML = '<i class="fas fa-lock"></i> Place Order - ₹<?php echo number_format($total_amount * 1.18, 2); ?>';
                    return false;
                }
            } else if(paymentMethod === 'upi') {
                const upiId = document.getElementById('upi_id').value;
                if(!upiId) {
                    e.preventDefault();
                    alert('Please enter your UPI ID.');
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.innerHTML = '<i class="fas fa-lock"></i> Place Order - ₹<?php echo number_format($total_amount * 1.18, 2); ?>';
                    return false;
                }
                
                if(!upiId.includes('@')) {
                    e.preventDefault();
                    alert('Please enter a valid UPI ID (e.g., yourname@upi).');
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.innerHTML = '<i class="fas fa-lock"></i> Place Order - ₹<?php echo number_format($total_amount * 1.18, 2); ?>';
                    return false;
                }
            }
            
            // If all validations pass, form will submit
            return true;
        });
        
        // Auto-fill billing address from shipping address
        document.getElementById('shipping_address').addEventListener('blur', function() {
            if(document.getElementById('same_as_shipping').checked) {
                document.getElementById('billing_address').value = this.value;
            }
        });
    </script>
</body>
</html>