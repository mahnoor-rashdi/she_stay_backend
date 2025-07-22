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



// get hostler profile
    public function fetchHostlerProfile($id) {
    $sql = "SELECT * FROM $this->table WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}






public function updateHostlerProfile($data) {
    $sql = "UPDATE $this->table SET 
                username = ?, 
                email = ?, 
                cnic = ?, 
                guardianCnic = ?, 
                guardianNumber = ?, 
                mobileNumber = ?
            WHERE id = ?";  // assuming you are using `id` to identify the user

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
        "ssssssi", 
        $data['username'], 
        $data['email'], 
        $data['cnic'], 
        $data['guardianCnic'], 
        $data['guardianNumber'], 
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



    }

    




?>