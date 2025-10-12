<?php
// Include database and product class
include_once '../config/database.php';
include_once '../models/Product.php';

// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize product object
$product = new Product($db);

// Handle GET requests
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $category = $_GET['category'] ?? 'all';
    $search = $_GET['search'] ?? '';
    
    if(!empty($search)) {
        // Search products
        $stmt = $product->search($search);
    } elseif($category != 'all') {
        // Get products by category name
        $stmt = $product->readByCategoryName($category);
    } else {
        // Get all products
        $stmt = $product->read();
    }
    
    $products_arr = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $product_item = array(
            "id" => $row['id'],
            "name" => $row['name'],
            "description" => $row['description'],
            "price" => $row['price'],
            "category" => $row['category_name'],
            "image" => $row['image'],
            "badge" => $row['badge'],
            "stock_quantity" => $row['stock_quantity']
        );
        array_push($products_arr, $product_item);
    }
    
    echo json_encode($products_arr);
}
?>