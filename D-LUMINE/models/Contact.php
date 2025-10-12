<?php
class Contact {
    private $conn;
    private $table_name = "contact_messages";

    public $id;
    public $name;
    public $email;
    public $message;
    public $is_read;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE - Add new contact message
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET name=:name, email=:email, message=:message";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->message = htmlspecialchars(strip_tags($this->message));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":message", $this->message);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // READ - Get all messages (for admin)
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " 
                 ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ - Get unread messages count
    public function getUnreadCount() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " 
                 WHERE is_read = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    // UPDATE - Mark as read
    public function markAsRead() {
        $query = "UPDATE " . $this->table_name . " 
                 SET is_read=1 
                 WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE - Delete message
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}


?>