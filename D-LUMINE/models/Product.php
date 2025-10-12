<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $image;
    public $badge;
    public $stock_quantity;
    public $is_active;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE - Add new product
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET name=:name, description=:description, price=:price, 
                     category_id=:category_id, image=:image, badge=:badge, 
                     stock_quantity=:stock_quantity, is_active=:is_active";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->badge = htmlspecialchars(strip_tags($this->badge));
        $this->stock_quantity = htmlspecialchars(strip_tags($this->stock_quantity));
        $this->is_active = htmlspecialchars(strip_tags($this->is_active));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":badge", $this->badge);
        $stmt->bindParam(":stock_quantity", $this->stock_quantity);
        $stmt->bindParam(":is_active", $this->is_active);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // READ - Get all products
    public function read() {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.is_active = 1 
                 ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ - Get products by category
    public function readByCategory($category_id) {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.category_id = ? AND p.is_active = 1 
                 ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category_id);
        $stmt->execute();
        return $stmt;
    }

    // READ - Get single product
    public function readOne() {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->category_id = $row['category_id'];
            $this->image = $row['image'];
            $this->badge = $row['badge'];
            $this->stock_quantity = $row['stock_quantity'];
            $this->is_active = $row['is_active'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    // UPDATE - Update product
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET name=:name, description=:description, price=:price, 
                     category_id=:category_id, image=:image, badge=:badge, 
                     stock_quantity=:stock_quantity, is_active=:is_active 
                 WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->image = htmlspecialchars(strip_tags($this->image));
        $this->badge = htmlspecialchars(strip_tags($this->badge));
        $this->stock_quantity = htmlspecialchars(strip_tags($this->stock_quantity));
        $this->is_active = htmlspecialchars(strip_tags($this->is_active));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":badge", $this->badge);
        $stmt->bindParam(":stock_quantity", $this->stock_quantity);
        $stmt->bindParam(":is_active", $this->is_active);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE - Delete product
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Search products
    public function search($keywords) {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE (p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?) 
                 AND p.is_active = 1 
                 ORDER BY p.created_at DESC";

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->execute();
        return $stmt;
    }

    // Get featured products
    public function getFeatured() {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.is_active = 1 
                 ORDER BY p.created_at DESC 
                 LIMIT 8";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    // READ - Get products by category name
    public function readByCategoryName($category_name) {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE c.name = ? AND p.is_active = 1 
                 ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category_name);
        $stmt->execute();
        return $stmt;
    }
}
?>