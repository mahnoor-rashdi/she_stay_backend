<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "backend";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->conn->connect_error) {
            die(json_encode([
                "status" => false,
                "message" => "Database Connection Failed: " . $this->conn->connect_error
            ]));
        }

        $this->createAdminsTable();
    }

    public function getConnection() {
        return $this->conn;
    }

    private function createAdminsTable() {
        $query = "CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if (!$this->conn->query($query)) {
            die(json_encode([
                "status" => false,
                "message" => "Failed to create 'admins' table: " . $this->conn->error
            ]));
        }
    }
}
?>
