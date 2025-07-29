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
          $this->createHostlersTable();
          $this->createWardensTable();
           $this->createbranchesTable();
           $this->createroomsTable();
           $this->createallotmenstTable();
         $this->createcomplainTable();
        $this->createfeesTable();
         $this->createStaffTable();  
         $this->createMessTable();
         $this->createMessMenuTable();
         $this->createMessItemTable();

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

    //HOSTLERS TABLE CCREATING 
private function createHostlersTable() {
    $query = "CREATE TABLE IF NOT EXISTS hostlers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        cnic VARCHAR(20) NOT NULL,
        guardianCnic VARCHAR(20),
        guardianNumber VARCHAR(20),
        mobileNumber VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$this->conn->query($query)) {
        die(json_encode([
            "status" => false,
            "message" => "Failed to create 'hostlers' table: " . $this->conn->error
        ]));
    }
}
private function createWardensTable() {
    $query = "CREATE TABLE IF NOT EXISTS wardens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        cnic VARCHAR(20) NOT NULL,
        branchId INT NULL,
        mobileNumber VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$this->conn->query($query)) {
        die(json_encode([
            "status" => false,
            "message" => "Failed to create 'wardens' table: " . $this->conn->error
        ]));
    }
}

private function createBranchesTable() {
    $query = "CREATE TABLE IF NOT EXISTS branches (
        id INT AUTO_INCREMENT PRIMARY KEY,
        branchName VARCHAR(100) NOT NULL,
        address VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$this->conn->query($query)) {
        die(json_encode([
            "status" => false,
            "message" => "Failed to create 'branches' table: " . $this->conn->error
        ]));
    }
}
private function createRoomsTable() {
    $query = "CREATE TABLE IF NOT EXISTS rooms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        roomType ENUM('0', '1') NOT NULL COMMENT '0 = AC Room, 1 = Non-AC Room',
        roomNo INT NOT NULL,
        bath TINYINT(1) NOT NULL DEFAULT 0,
        noOfBeds INT NOT NULL,
        branchId INT NOT NULL,
        roomCharges DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (branchId) REFERENCES branches(id) ON DELETE CASCADE
    )";

    if (!$this->conn->query($query)) {
        die(json_encode([
            "status" => false,
            "message" => "Failed to create 'rooms' table: " . $this->conn->error
        ]));
    }
}

private function createallotmenstTable() {
    $query = "CREATE TABLE IF NOT EXISTS allotment  (
        id INT AUTO_INCREMENT PRIMARY KEY,
        roomId INT NOT NULL,
        hostlerId INT NOT NULL,
        branchId INT NOT NULL,
       allotmentDate DATE NOT NULL DEFAULT CURRENT_DATE,
        status TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (branchId) REFERENCES branches(id) ON DELETE CASCADE ON UPDATE CASCADE ,
        FOREIGN KEY (hostlerId) REFERENCES hostlers(id) ON DELETE CASCADE ON UPDATE CASCADE ,
        FOREIGN KEY (roomId) REFERENCES rooms(id) ON DELETE  CASCADE ON UPDATE CASCADE
        
    )";

    if (!$this->conn->query($query)) {
        die(json_encode([
            "status" => false,
            "message" => "Failed to create 'allotment' table: " . $this->conn->error
        ]));
    }
}
private function createcomplainTable() {
    $query = "CREATE TABLE IF NOT EXISTS complains  (
        id INT AUTO_INCREMENT PRIMARY KEY,
        description VARCHAR(255) NOT NULL,
        hostlerId INT NOT NULL,
        branchId INT NOT NULL,
        status TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (branchId) REFERENCES branches(id) ON DELETE CASCADE ON UPDATE CASCADE ,
        FOREIGN KEY (hostlerId) REFERENCES hostlers(id) ON DELETE CASCADE ON UPDATE CASCADE 

        
    )";

    if (!$this->conn->query($query)) {
        die(json_encode([
            "status" => false,
            "message" => "Failed to create 'complains' table: " . $this->conn->error
        ]));
    }
}

private function createfeesTable() {


    
    $query = "CREATE TABLE IF NOT EXISTS fees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        description VARCHAR(255) NOT NULL,
        hostlerId INT NOT NULL,
        branchId INT NOT NULL,
        arriesAmount INT DEFAULT 0,
        currentAmount INT NOT NULL,
        totalAmount INT NOT NULL,
        paidAmount INT NOT NULL,
        discount INT DEFAULT 0,
        paymentMethod VARCHAR(50),
        feesPaidDate DATE,
        feesMonth DATE,
        status TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 = received, 1 = not received' ,      
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (branchId) REFERENCES branches(id) ON DELETE CASCADE ON UPDATE CASCADE ,
        FOREIGN KEY (hostlerId) REFERENCES hostlers(id) ON DELETE CASCADE ON UPDATE CASCADE 

        
    )";

    if (!$this->conn->query($query)) {
        die(json_encode([
            "status" => false,
            "message" => "Failed to create 'complains' table: " . $this->conn->error
        ]));
    }
}



private function createStaffTable() {
    $query = "CREATE TABLE IF NOT EXISTS staff (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name varchar(100) NOT NULL,
        workType ENUM('0', '1') NOT NULL COMMENT '0 = AC Room, 1 = Non-AC Room',
        salary DECIMAL(10,2) NOT NULL,
        cnic VARCHAR(20) NOT NULL,
        phoneNumber VARCHAR(15) NOT NULL,
        branchId INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (branchId) REFERENCES branches(id) ON DELETE CASCADE ON UPDATE CASCADE
    )";

    if (!$this->conn->query($query)) {
        die(json_encode([
            "status" => false,
            "message" => "Failed to create 'staff' table: " . $this->conn->error
        ]));
    }
}

private function createMessTable() {
    $query = "CREATE TABLE IF NOT EXISTS mess (
        id INT AUTO_INCREMENT PRIMARY KEY,
        day VARCHAR(20) NOT NULL,
        mealType ENUM('Breakfast', 'Lunch', 'Dinner') NOT NULL,
        branchId INT NOT NULL,
        messTimeFrom TIME NOT NULL,
        messTimeTo TIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (branchId) REFERENCES branches(id) ON DELETE CASCADE ON UPDATE CASCADE
    )";

    if (!$this->conn->query($query)) {
        die(json_encode([
            "status" => false,
            "message" => "Failed to create 'mess' table: " . $this->conn->error
        ]));
    }
}

private function createMessMenuTable() {
    $query = "CREATE TABLE IF NOT EXISTS mess_menu (
        id INT AUTO_INCREMENT PRIMARY KEY,
        itemName VARCHAR(100) NOT NULL,
        status TINYINT(1) DEFAULT 1,  
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

    )";

    if (!$this->conn->query($query)) {
        die(json_encode([
            "status" => false,
            "message" => "Failed to create 'mess_menu' table: " . $this->conn->error
        ]));
    }
}

private function createMessItemTable() {
    $query = "CREATE TABLE IF NOT EXISTS mess_item (
        id INT AUTO_INCREMENT PRIMARY KEY,
        messId INT NOT NULL,
        messMenuId INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (messId) REFERENCES mess(id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (messMenuId) REFERENCES mess_menu(id) ON DELETE CASCADE ON UPDATE CASCADE
    )";

    if (!$this->conn->query($query)) {
        die(json_encode([
            "status" => false,
            "message" => "Failed to create 'mess_item' table: " . $this->conn->error
        ]));
    }
}






}
?>
