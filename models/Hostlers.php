<?php 


class Hostlers {
      private $conn;
      private $table = "hostlers";

      public function __construct($db) {
        $this->conn = $db;
    }
       public function register($username, $cnic, $email, $password ,$guardianCnic, $guardianNumber,$mobileNumber) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO $this->table (username, email, password,cnic,guardianCnic,guardianNumber,mobileNumber) VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssss", $username, $email, $hashedPassword,$cnic,$guardianCnic,$guardianNumber,$mobileNumber);
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




    public function fetchHostlerProfile($id) {
    $sql = "SELECT * FROM $this->table WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


    }





?>