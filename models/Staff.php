<?php
class Staff {
    private $conn;
    private $table = "staff";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a staff member
    public function createStaff($data) {
        $query = "INSERT INTO $this->table (name, workType, salary, cnic, phoneNumber, branchId)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "ssdssi",
            $data['name'],
            $data['workType'],  // should be '0' or '1'
            $data['salary'],
            $data['cnic'],
            $data['phoneNumber'],
            $data['branchId']
        );

        if ($stmt->execute()) {
            $insertedId = $this->conn->insert_id;
            return $this->getStaffById($insertedId);
        }

        return false;
    }

    // Get all staff
    public function getAllStaff() {
        $query = "SELECT s.*, b.branchName, b.address 
                  FROM $this->table s 
                  JOIN branches b ON s.branchId = b.id";
        $result = $this->conn->query($query);

        $staffList = [];
        while ($row = $result->fetch_assoc()) {
            $staffList[] = [
                "id" => $row['id'],
                "name" => $row['name'],
                "workType" => $row['workType'], // '0' or '1'
                "salary" => $row['salary'],
                "cnic" => $row['cnic'],
                "phoneNumber" => $row['phoneNumber'],
                "created_at" => $row['created_at'],
                "branch" => [
                    "id" => $row['branchId'],
                    "branchName" => $row['branchName'],
                    "address" => $row['address']
                ]
            ];
        }

        return $staffList;
    }

    // Get staff by ID
    public function getStaffById($id) {
        $query = "SELECT s.*, b.branchName, b.address 
                  FROM $this->table s
                  JOIN branches b ON s.branchId = b.id
                  WHERE s.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) return null;

        return [
            "id" => $row['id'],
            "name" => $row['name'],
            "workType" => $row['workType'],
            "salary" => $row['salary'],
            "cnic" => $row['cnic'],
            "phoneNumber" => $row['phoneNumber'],
            "created_at" => $row['created_at'],
            "branch" => [
                "id" => $row['branchId'],
                "branchName" => $row['branchName'],
                "address" => $row['address']
            ]
        ];
    }

    // Update staff
    public function updateStaff($id, $data) {
        $query = "UPDATE $this->table 
                  SET name = ?, workType = ?, salary = ?, cnic = ?, phoneNumber = ?, branchId = ?
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "ssdssii",
            $data['name'],
            $data['workType'],
            $data['salary'],
            $data['cnic'],
            $data['phoneNumber'],
            $data['branchId'],
            $id
        );

        if ($stmt->execute()) {
            return $this->getStaffById($id);
        }

        return false;
    }

    // Delete staff
    public function deleteStaff($id) {
        $query = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
