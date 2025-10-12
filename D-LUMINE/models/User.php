<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $address;
    public $role;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE - Register new user
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET name=:name, email=:email, password=:password, 
                     phone=:phone, address=:address, role='customer'";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->address = htmlspecialchars(strip_tags($this->address));

        // Hash password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":address", $this->address);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Check if email exists
    public function emailExists() {
        $query = "SELECT id, name, password, phone, address, role 
                 FROM " . $this->table_name . " 
                 WHERE email = ? 
                 LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->password = $row['password'];
            $this->phone = $row['phone'];
            $this->address = $row['address'];
            $this->role = $row['role'];
            return true;
        }
        return false;
    }

    // UPDATE - Update user profile
    public function update() {
        $password_set = !empty($this->password) ? ", password=:password" : "";

        $query = "UPDATE " . $this->table_name . " 
                 SET name=:name, email=:email, phone=:phone, address=:address 
                 {$password_set} 
                 WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->address = htmlspecialchars(strip_tags($this->address));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":id", $this->id);

        if(!empty($this->password)){
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt->bindParam(":password", $this->password);
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get user by ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->address = $row['address'];
            $this->role = $row['role'];
            return true;
        }
        return false;
    }
}
?>