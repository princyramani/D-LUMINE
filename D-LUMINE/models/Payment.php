<?php
class Payment {
    private $conn;
    private $table_name = "payments";

    public $id;
    public $order_id;
    public $payment_method;
    public $payment_status;
    public $amount;
    public $transaction_id;
    public $payment_details;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE - Create payment record
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET order_id=:order_id, payment_method=:payment_method, 
                     payment_status=:payment_status, amount=:amount,
                     transaction_id=:transaction_id, payment_details=:payment_details";

        $stmt = $this->conn->prepare($query);

        $this->order_id = htmlspecialchars(strip_tags($this->order_id));
        $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));
        $this->payment_status = htmlspecialchars(strip_tags($this->payment_status));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->transaction_id = htmlspecialchars(strip_tags($this->transaction_id));
        $this->payment_details = htmlspecialchars(strip_tags($this->payment_details));

        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->bindParam(":payment_method", $this->payment_method);
        $stmt->bindParam(":payment_status", $this->payment_status);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":transaction_id", $this->transaction_id);
        $stmt->bindParam(":payment_details", $this->payment_details);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // READ - Get payment by order ID
    public function readByOrder($order_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE order_id = ? 
                 LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $order_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->order_id = $row['order_id'];
            $this->payment_method = $row['payment_method'];
            $this->payment_status = $row['payment_status'];
            $this->amount = $row['amount'];
            $this->transaction_id = $row['transaction_id'];
            $this->payment_details = $row['payment_details'];
            return true;
        }
        return false;
    }

    // UPDATE - Update payment status
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " 
                 SET payment_status=:payment_status 
                 WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":payment_status", $this->payment_status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>