<?php
class Room {
    private $conn;
    private $table = "rooms";

    public function __construct($db) {
        // Assign the database connection to the instance variable
        $this->conn = $db;
    }

    public function createRoom($data) {
        $query = "INSERT INTO $this->table (roomType, roomNo, bath, noOfBeds, branchId, roomCharges)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("siiiid", $data['roomType'], $data['roomNo'], $data['bath'], $data['noOfBeds'], $data['branchId'], $data['roomCharges']);

        if ($stmt->execute()) {
            $insertedId = $this->conn->insert_id;
            return $this->getRoomById($insertedId);
        }

        return false;
    }

public function getAllRooms() {
    $query = "SELECT r.*, b.id as branchId, b.branchName, b.address 
              FROM $this->table r 
              JOIN branches b ON r.branchId = b.id";
    $result = $this->conn->query($query);

    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = [
            "id" => $row['id'],
            "roomType" => $row['roomType'],
            "roomNo" => $row['roomNo'],
            "bath" => $row['bath'],
            "noOfBeds" => $row['noOfBeds'],
            "roomCharges" => $row['roomCharges'],
            "branch" => [
                "id" => $row['branchId'],
                "branchName" => $row['branchName'],
                "address" => $row['address']
            ]
        ];
    }

    return $rooms;
}


    public function getRoomById($id) {
        $query = "SELECT r.*, b.branchName, b.address 
                  FROM $this->table r
                  JOIN branches b ON r.branchId = b.id
                  WHERE r.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if (!$row) return null;   
return [
        "id" => $row['id'],
        "roomType" => $row['roomType'],
        "roomNo" => $row['roomNo'],
        "bath" => $row['bath'],
        "noOfBeds" => $row['noOfBeds'],
        "roomCharges" => $row['roomCharges'],
        "branch" => [
            "id" => $row['branchId'],
            "branchName" => $row['branchName'],
            "address" => $row['address']
        ]
    ];
}





    public function deleteRoom($id) {
        $query = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function updateRoom($id, $data) {
        $query = "UPDATE $this->table 
                  SET roomType = ?, roomNo = ?, bath = ?, noOfBeds = ?, branchId = ?, roomCharges = ? 
                  WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("siiiidi", $data['roomType'], $data['roomNo'], $data['bath'], $data['noOfBeds'], $data['branchId'], $data['roomCharges'], $id);

        if ($stmt->execute()) {
            return $this->getRoomById($id);
        }

        return false;
    }
}
