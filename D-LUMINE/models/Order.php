<?php
class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $user_id;
    public $total_amount;
    public $status;
    public $shipping_address;
    public $payment_method;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE - Create new order
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET user_id=:user_id, total_amount=:total_amount, 
                     status=:status, shipping_address=:shipping_address, 
                     payment_method=:payment_method";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->total_amount = htmlspecialchars(strip_tags($this->total_amount));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->shipping_address = htmlspecialchars(strip_tags($this->shipping_address));
        $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));

        // Bind parameters
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":shipping_address", $this->shipping_address);
        $stmt->bindParam(":payment_method", $this->payment_method);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // READ - Get orders by user
    public function readByUser($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE user_id = ? 
                 ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    // READ - Get all orders (for admin)
    public function readAll() {
        $query = "SELECT o.*, u.name as user_name 
                 FROM " . $this->table_name . " o 
                 LEFT JOIN users u ON o.user_id = u.id 
                 ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // UPDATE - Update order status
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " 
                 SET status=:status 
                 WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>