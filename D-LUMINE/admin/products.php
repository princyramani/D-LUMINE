<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include_once '../config/database.php';
include_once '../models/Product.php';
include_once '../models/Category.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$category = new Category($db);

$message = '';
$message_type = '';

// Handle form actions
if($_POST){
    if(isset($_POST['add_product'])){
        $product->name = $_POST['name'];
        $product->description = $_POST['description'];
        $product->price = $_POST['price'];
        $product->category_id = $_POST['category_id'];
        $product->image = $_POST['image'];
        $product->badge = $_POST['badge'];
        $product->stock_quantity = $_POST['stock_quantity'];
        $product->is_active = 1;
        
        if($product->create()){
            $message = "Product added successfully!";
            $message_type = "success";
        } else {
            $message = "Failed to add product.";
            $message_type = "error";
        }
    }
    
    if(isset($_POST['update_product'])){
        $product->id = $_POST['id'];
        $product->name = $_POST['name'];
        $product->description = $_POST['description'];
        $product->price = $_POST['price'];
        $product->category_id = $_POST['category_id'];
        $product->image = $_POST['image'];
        $product->badge = $_POST['badge'];
        $product->stock_quantity = $_POST['stock_quantity'];
        
        if($product->update()){
            $message = "Product updated successfully!";
            $message_type = "success";
        } else {
            $message = "Failed to update product.";
            $message_type = "error";
        }
    }
}

// Handle delete
if(isset($_GET['delete_id'])){
    $product->id = $_GET['delete_id'];
    if($product->delete()){
        $message = "Product deleted successfully!";
        $message_type = "success";
    } else {
        $message = "Failed to delete product.";
        $message_type = "error";
    }
}

// Handle edit
$edit_product = null;
if(isset($_GET['edit_id'])){
    $product->id = $_GET['edit_id'];
    if($product->readOne()){
        $edit_product = $product;
    }
}

$products = $product->read();
$categories = $category->read();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - D'LUMINE</title>
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
        .message { padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #000080; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-edit { background: #ffc107; color: black; }
        table { width: 100%; background: white; border-collapse: collapse; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #f8f9fa; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px; }
        .form-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
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
                    <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="products.php" class="active"><i class="fas fa-gem"></i> Products</a></li>
                    <li><a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                    <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>
        <div class="content">
            <h1>Products Management</h1>
            
            <?php if($message): ?>
                <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Add/Edit Product Form -->
            <div class="form-container">
                <h2><?php echo $edit_product ? 'Edit Product' : 'Add New Product'; ?></h2>
                <form method="POST">
                    <?php if($edit_product): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_product->id; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo $edit_product ? $edit_product->name : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3"><?php echo $edit_product ? $edit_product->description : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (₹)</label>
                        <input type="number" id="price" name="price" step="0.01" 
                               value="<?php echo $edit_product ? $edit_product->price : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php while($cat = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                    <?php echo ($edit_product && $edit_product->category_id == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Image URL</label>
                        <input type="text" id="image" name="image" 
                               value="<?php echo $edit_product ? $edit_product->image : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="badge">Badge</label>
                        <input type="text" id="badge" name="badge" 
                               value="<?php echo $edit_product ? $edit_product->badge : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="stock_quantity">Stock Quantity</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" 
                               value="<?php echo $edit_product ? $edit_product->stock_quantity : '0'; ?>" required>
                    </div>
                    
                    <button type="submit" name="<?php echo $edit_product ? 'update_product' : 'add_product'; ?>" 
                            class="btn btn-primary">
                        <?php echo $edit_product ? 'Update Product' : 'Add Product'; ?>
                    </button>
                    
                    <?php if($edit_product): ?>
                        <a href="products.php" class="btn">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Products List -->
            <div class="form-container">
                <h2>All Products</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="width: 50px; height: 50px; object-fit: cover;"></td>
                            <td><?php echo $row['name']; ?></td>
                            <td>₹<?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo ucfirst($row['category_name']); ?></td>
                            <td><?php echo $row['stock_quantity']; ?></td>
                            <td>
                                <a href="products.php?edit_id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="products.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>