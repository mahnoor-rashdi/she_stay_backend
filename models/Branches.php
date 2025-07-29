<?php

class Branches {
    private $conn;
    private $table = "branches";

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE branch
 public function createBranch($address, $branchName) {
    $sql = "INSERT INTO $this->table (address, branchName) VALUES (?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss", $address, $branchName);

    if ($stmt->execute()) {
        $lastId = $this->conn->insert_id;
        return $this->getBranchById($lastId);  // Get and return the full data
    }

    return false;
}
    // READ all branches
    public function getAllBranches() {
        $sql = "SELECT * FROM $this->table";
        $result = $this->conn->query($sql);

        $branches = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $branches[] = $row;
            }
        }

        return $branches;
    }

    // READ branch by ID
    public function getBranchById($id) {
        $sql = "SELECT * FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // UPDATE branch
public function updateBranch($id, $address, $branchName) {
    $sql = "UPDATE $this->table SET address = ?, branchName = ? WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ssi", $address, $branchName, $id);

    if ($stmt->execute()) {
        return $this->getBranchById($id);  // Fetch and return updated row
    }

    return false;
}



    // DELETE branch
    public function deleteBranch($id) {
        $sql = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
