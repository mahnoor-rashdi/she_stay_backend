<?php
class Fees {
    private $conn;
    private $table = "fees";

    public function __construct($db) {
        // Assign the database connection to the instance variable
        $this->conn = $db;
    }

   public function createFees($data) {
    $query = "INSERT INTO $this->table 
        (hostlerId, arriesAmount, currentAmount, totalAmount, paidAmount, branchId, discount, paymentMethod, feesMonth, feesPaidDate, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $this->conn->prepare($query);

    // Types: i = int, s = string
    $stmt->bind_param(
        "iiiiiiissii", 
        $data['hostlerId'],
        $data['arriesAmount'],
        $data['currentAmount'],
        $data['totalAmount'],
        $data['paidAmount'],
        $data['branchId'],
        $data['discount'],
        $data['paymentMethod'],    // string
        $data['feesMonth'],        // string (date)
        $data['feesPaidDate'],     // string (date)
        $data['status']
    );

    if ($stmt->execute()) {
        $insertedId = $this->conn->insert_id;
        return $this->getFeesById($insertedId);
    }

    return false;
}


 

public function getAllFees() {
    $query = "SELECT 
                f.id as FeesId,
                f.arriesAmount as arriesAmount, f.currentAmount as currentAmount, f.totalAmount as totalAmount, f.paidAmount as paidAmount,f.feesMonth as feesMonth,f.feesPaidDate as feesPaidDate,f.discount as discount,f.paymentMethod as paymentMethod, f.status as status,
                b.id as branchId, b.branchName, b.address,
                h.id as hostlerId, h.username, h.email, h.cnic
              FROM $this->table f
              JOIN branches b ON f.branchId = b.id
              JOIN hostlers h ON f.hostlerId = h.id";

    $result = $this->conn->query($query);

    $Fees = [];

    while ($row = $result->fetch_assoc()) {
        $Fees[] = [
            "id" => $row['FeesId'],

             "totalAmount" => $row['totalAmount'], // now it will work
             "arriesAmount" => $row['arriesAmount'], // now it will work
             "currentAmount" => $row['currentAmount'], // now it will work
            "paidAmount" => $row['paidAmount'], // now it will work
             "feesMonth" => $row['feesMonth'], // now it will work
             "feesPaidDate" => $row['feesPaidDate'], // now it will work
             

            "discount" => $row['discount'], // now it will work
            "paymentMethod" => $row['paymentMethod'], // now it will work

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

    return $Fees;
}






public function getFeesById($id) {
    $query = "SELECT 
           f.id as FeesId,
           f.arriesAmount as arriesAmount, f.currentAmount as currentAmount, f.totalAmount as totalAmount, f.paidAmount as paidAmount,f.feesMonth as feesMonth,f.feesPaidDate as feesPaidDate,f.discount as discount,f.paymentMethod as paymentMethod, f.status as status,
            b.id as branchId, b.branchName, b.address,
            h.id as hostlerId, h.username, h.email, h.cnic
          FROM $this->table f
          JOIN branches b ON f.branchId = b.id
          JOIN hostlers h ON f.hostlerId = h.id
          WHERE f.id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) return null;

    return [
         "id" => $row['FeesId'],

             "totalAmount" => $row['totalAmount'], // now it will work
             "arriesAmount" => $row['arriesAmount'], // now it will work
             "currentAmount" => $row['currentAmount'], // now it will work
            "paidAmount" => $row['paidAmount'], // now it will work
             "feesMonth" => $row['feesMonth'], // now it will work
             "feesPaidDate" => $row['feesPaidDate'], // now it will work
            "discount" => $row['discount'], // now it will work
            "paymentMethod" => $row['paymentMethod'], // now it will work

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







    public function deleteFees($id) {
        $query = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }


public function updateFees($id, $data) {
    $query = "UPDATE $this->table 
              SET hostlerId = ?, branchId = ?, arriesAmount = ?, currentAmount = ?, totalAmount = ?, paidAmount = ?, discount = ?, paymentMethod = ?, feesMonth = ?, feesPaidDate = ?, status = ?
              WHERE id = ?";
    
    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        echo "Prepare failed: " . $this->conn->error;
        return false;
    }

    $stmt->bind_param(
        "iiiiiiisssii", // 7 int, 3 string, 2 int
        $data['hostlerId'],
        $data['branchId'],
        $data['arriesAmount'],
        $data['currentAmount'],
        $data['totalAmount'],
        $data['paidAmount'],
        $data['discount'],
        $data['paymentMethod'],  // string
        $data['feesMonth'],      // string (e.g., "2025-07-01")
        $data['feesPaidDate'],   // string (e.g., "2025-07-25")
        $data['status'],
        $id
    );

    if ($stmt->execute()) {
        return $this->getFeesById($id);
    }

    echo "Execute failed: " . $stmt->error;
    return false;
}



}
