<?php
class Admin {
    private $conn;
    private $table = "admins";


    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO $this->table (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        return $stmt->execute();
    }


    public function login($email) {
        $sql = "SELECT * FROM $this->table WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>
