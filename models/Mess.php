<?php

class Mess {
    private $conn;
    private $table = 'mess';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Mess Entry
    public function createMess($data) {
        $query = "INSERT INTO $this->table (day, mealType, branchId, messTimeFrom, messTimeTo) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssiss", $data['day'], $data['mealType'], $data['branchId'], $data['messTimeFrom'], $data['messTimeTo']);

        if ($stmt->execute()) {
            return [
                'id' => $stmt->insert_id,
                'day' => $data['day'],
                'mealType' => $data['mealType'],
                'branchId' => $data['branchId'],
                'messTimeFrom' => $data['messTimeFrom'],
                'messTimeTo' => $data['messTimeTo']
            ];
        }

        return false;
    }

    // Get All Mess Entries
    public function getAllMess() {
        $query = "SELECT * FROM $this->table ORDER BY id DESC";
        $result = $this->conn->query($query);
        $data = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // Get Mess By ID
    public function getMessById($id) {
        $query = "SELECT * FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Update Mess Entry
    public function updateMess($id, $data) {
        $query = "UPDATE $this->table 
                  SET day = ?, mealType = ?, branchId = ?, messTimeFrom = ?, messTimeTo = ?
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssissi", $data['day'], $data['mealType'], $data['branchId'], $data['messTimeFrom'], $data['messTimeTo'], $id);

        return $stmt->execute();
    }

    // Delete Mess Entry
    public function deleteMess($id) {
        $query = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
?>
