<?php
class Complain {
    private $conn;
    private $table = "complains";

    public function __construct($db) {
        // Assign the database connection to the instance variable
        $this->conn = $db;
    }

    public function createComplain($data) {
        $query = "INSERT INTO $this->table (description, hostlerId, branchId, status)
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("siii", $data['description'], $data['hostlerId'], $data['branchId'], $data['status']);

        if ($stmt->execute()) {
            $insertedId = $this->conn->insert_id;
            return $this->getComplainById($insertedId);
        }

        return false;
    }


 

public function getAllComplains() {
    $query = "SELECT 
                c.id as ComplainId,
                c.description as description, c.status as status,
                b.id as branchId, b.branchName, b.address,
                h.id as hostlerId, h.username, h.email, h.cnic
              FROM $this->table c
              JOIN branches b ON c.branchId = b.id
              JOIN hostlers h ON c.hostlerId = h.id";

    $result = $this->conn->query($query);

    $Complains = [];

    while ($row = $result->fetch_assoc()) {
        $Complains[] = [
            "id" => $row['ComplainId'],
            "description" => $row['description'], // now it will work
            "status" => $row['status'],
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

    return $Complains;
}






public function getComplainById($id) {
    $query = "SELECT 
            c.id as ComplainId,
            c.description as description, c.status as status,
            b.id as branchId, b.branchName, b.address,
            h.id as hostlerId, h.username, h.email, h.cnic
          FROM $this->table c
          JOIN branches b ON c.branchId = b.id
          JOIN hostlers h ON c.hostlerId = h.id
          WHERE c.id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) return null;

    return [
        "id" => $row['ComplainId'],
        "description" => $row['description'],
        "status" => $row['status'],
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







    public function deleteComplain($id) {
        $query = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }


public function updateComplain($id, $data) {
    $query = "UPDATE $this->table 
              SET hostlerId = ?, branchId = ?, description = ?, status = ?
              WHERE id = ?";
    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        echo "Prepare failed: " . $this->conn->error;
        return false;
    }

    $stmt->bind_param(
        "iissi",
        $data['hostlerId'],
        $data['branchId'],
        $data['description'],
        $data['status'],
        $id
    );

    if ($stmt->execute()) {
        return $this->getComplainById($id);
    }

    echo "Execute failed: " . $stmt->error;
    return false;
}


}
