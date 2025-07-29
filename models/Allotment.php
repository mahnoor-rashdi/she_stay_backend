<?php
class Allotment {
    private $conn;
    private $table = "allotment";

    public function __construct($db) {
        // Assign the database connection to the instance variable
        $this->conn = $db;
    }

    public function createAllotment($data) {
        $query = "INSERT INTO $this->table (roomId, hostlerId, branchId, allotmentDate, status)
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiisi", $data['roomId'], $data['hostlerId'], $data['branchId'], $data['allotmentDate'], $data['status']);

        if ($stmt->execute()) {
            $insertedId = $this->conn->insert_id;
            return $this->getAllotmentById($insertedId);
        }

        return false;
    }


 

public function getAllAllotments() {
    $query = "SELECT 
                a.id as allotmentId,
                r.id as roomId, r.roomType, r.roomNo, r.bath, r.noOfBeds, r.roomCharges,
                b.id as branchId, b.branchName, b.address,
                h.id as hostlerId, h.username, h.email, h.cnic
              FROM $this->table a
              JOIN rooms r ON a.roomId = r.id
              JOIN branches b ON r.branchId = b.id
              JOIN hostlers h ON a.hostlerId = h.id";

    $result = $this->conn->query($query);

    $allotments = [];

    while ($row = $result->fetch_assoc()) {
        $allotments[] = [
            "id" => $row['allotmentId'],
            "room" => [
                "id" => $row['roomId'],
                "roomType" => $row['roomType'],
                "roomNo" => $row['roomNo'],
                "bath" => $row['bath'],
                "noOfBeds" => $row['noOfBeds'],
                "roomCharges" => $row['roomCharges']
            ],
            "branch" => [
                "id" => $row['branchId'],
                "branchName" => $row['branchName'],
                "address" => $row['address']
            ],
            "hostler" => [
                "id" => $row['hostlerId'],
                "username" => $row['username'],
                "email" => $row['email'],
                "cnic" => $row['cnic']
            ]
        ];
    }

    return $allotments;
}





    public function getAllotmentById($id) {
        $query = "SELECT 
                a.id as allotmentId,
                r.id as roomId, r.roomType, r.roomNo, r.bath, r.noOfBeds, r.roomCharges,
                b.id as branchId, b.branchName, b.address,
                h.id as hostlerId, h.username, h.email, h.cnic
              FROM $this->table a
              JOIN rooms r ON a.roomId = r.id
              JOIN branches b ON r.branchId = b.id
              JOIN hostlers h ON a.hostlerId = h.id
              WHERE a.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if (!$row) return null;
        
    
return ["id" => $row['allotmentId'],
            "room" => [
                "id" => $row['roomId'],
                "roomType" => $row['roomType'],
                "roomNo" => $row['roomNo'],
                "bath" => $row['bath'],
                "noOfBeds" => $row['noOfBeds'],
                "roomCharges" => $row['roomCharges']
            ],
            "branch" => [
                "id" => $row['branchId'],
                "branchName" => $row['branchName'],
                "address" => $row['address']
            ],
            "hostler" => [
                "id" => $row['hostlerId'],
                "username" => $row['username'],
                "email" => $row['email'],
                "cnic" => $row['cnic']
            ]
            ];
        
}





    public function deleteAllotment($id) {
        $query = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }


  public function updateAllotment($id, $data) {
    $query = "UPDATE $this->table 
              SET roomId = ?, hostlerId = ?, branchId = ?, allotmentDate = ?, status = ?
              WHERE id = ?";
    $stmt = $this->conn->prepare($query);

    $stmt->bind_param(
        "iiiisi",  // 3 integers, 1 string (date), 1 integer, 1 integer (id)
        $data['roomId'],
        $data['hostlerId'],
        $data['branchId'],
        $data['allotmentDate'], // string (e.g., '2025-07-22')
        $data['status'],
        $id
    );

    if ($stmt->execute()) {
        return $this->getAllotmentById($id);
    }

    return false;
}

}
