<?php 


class Wardens {
      private $conn;
      private $table = "wardens";

      public function __construct($db) {
        $this->conn = $db;
    }
       public function register($username, $cnic, $email, $password ,$mobileNumber) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO $this->table (username, email, password,cnic,mobileNumber) VALUES (?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $hashedPassword,$cnic,$mobileNumber);
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



//get hostler profile  
    public function fetchWardensProfile($id) {
    $sql = "SELECT * FROM $this->table WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


public function updateWardensProfile($data) {
    $sql = "UPDATE $this->table SET 
                username = ?, 
                email = ?, 
                cnic = ?, 
                mobileNumber = ?

            WHERE id = ?";  // assuming you are using `id` to identify the user


    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
        "sssss", 
        $data['username'], 
        $data['email'], 
        $data['cnic'], 
        $data['mobileNumber'], 
     
        $data['id'] // this should be included in $data
    );

    return $stmt->execute();
}



// update psswod by email // reset password     
public function updatePasswordByEmail($email, $newPassword) {
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $sql = "UPDATE $this->table SET password = ? WHERE email = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $email);
    return $stmt->execute();
}


public function getAllWardens() {
    $sql = "SELECT * FROM $this->table";
    $result = $this->conn->query($sql);

    $wardens = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $wardens[] = $row;
        }
    }
    return $wardens;
}






}





?>