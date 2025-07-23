<?php
require_once __DIR__ . '/../models/Wardens.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../vendor/autoload.php';


//   USING JSON WEB TOKEN PACKGES TO CREATE TOKEN AND VERIFY TOKEN
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class WardenControllers {
    private $wardens;
    private $jwtSecret ;// Keep it safe and hidden


  public function __construct($conn) {
        $this->wardens = new Wardens($conn); 
        $this->jwtSecret = getenv('JWT_SECRET');
    }

public function register($data) {
        if (
            empty($data['username']) || empty($data['cnic']) || empty($data['email']) ||
            empty($data['password'])  || empty($data['mobileNumber']) 
        ) {
            return Response::json(false, "All fields are required.");
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return Response::json(false, "Invalid email format.");
        }

        $success = $this->wardens->register(
            $data['username'],
            $data['cnic'],
            $data['email'],
            $data['password'],
         
            $data['mobileNumber']
        );

        return $success
            ? Response::json(true, "Wardens registered successfully.",$data)
            : Response::json(false, "Registration failed.");
    }


    public function login($data) {
        if (empty($data['email']) || empty($data['password'])) {
            return Response::json(false, "Email and password are required.");
        }

        $user = $this->wardens->login($data['email']);

        if ($user && password_verify($data['password'], $user['password'])) {
            unset($user['password']); // Hide hashed password

            // Generate JWT token
            $payload = [
                "iss" => "http://localhost", // issuer
                "aud" => "http://localhost",
                "iat" => time(),            // issued at
                "exp" => time() + 3600,     // 1 hour expiry
                "data" => [
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "email" => $user['email']
                ]
            ];

            $jwt = JWT::encode($payload, $this->jwtSecret, 'HS256');

            return Response::json(true, "Login successful.", [
                "user" => $user,
                "token" => $jwt
            ]);
        } else {
            return Response::json(false, "Invalid email or password.");
        }
    }




    public function getWardensProfile($token) {
    try {
        $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        $userId = $decoded->data->id;
    

        $user = $this->wardens->fetchWardensProfile($userId);

        if ($user) {
            unset($user['password']); // Hide password for security
            return Response::json(true, "Profile fetched successfully", $user);
        } else {
            return Response::json(false, "Wardens not found");
        }

    } catch (Exception $e) {
        return Response::json(false, "Invalid token: " . $e->getMessage());
    }
}

public function updateWardensProfile($token,$data) {
    try {
        $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        $userId = $decoded->data->id;
    

        $user = $this->wardens->fetchWardensProfile($userId);

         if (!$user) {
            return Response::json(false, "Warden not found");
        }
        $updatedData = [
            'id' => $userId,
            'username' => $data['username'] ?? $user['username'],
            'email' => $data['email'] ?? $user['email'],
            'cnic' => $data['cnic'] ?? $user['cnic'],
            'branchId' => $data['branchId'] ?? $user['branchId'],
            'mobileNumber' => $data['mobileNumber'] ?? $user['mobileNumber'],
        ];

        $updated = $this->wardens->updateWardensProfile($updatedData);

        if($updated){
            return Response::json(true, "Profile updated successfully", $updatedData);
        }
        else{
            return Response::json(false, "Failed to update profile");
        }


        
    } catch (Exception $e) {
        return Response::json(false, "Invalid token: " . $e->getMessage());
    }
}

// forgoet password 
public function forgotPassword($data) {
    if (empty($data['email']) || empty($data['newPassword'])) {
        return Response::json(false, "Email and new password are required.");
    }

    // Check if user exists
    $user = $this->wardens->login($data['email']); // Reusing login() to get user by email
    if (!$user) {
        return Response::json(false, "Email not found.");
    }

    $success = $this->wardens->updatePasswordByEmail($data['email'], $data['newPassword']);

    return $success
        ? Response::json(true, "Password updated successfully.")
        : Response::json(false, "Failed to update password.");
}

// udpaitng passsword   
public function updatePasswordWithToken($token, $data) {
    if (empty($data['newPassword'])) {
        return Response::json(false, "New password is required.");
    }

    try {
        $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
    //   print_r($decoded);
        $userId = $decoded->data->id;

        $user = $this->wardens->fetchWardensProfile($userId);
        // print_r($user);
        if (!$user) {
            return Response::json(false, "User not found.");
        }

        $success = $this->wardens->updatePasswordByEmail($user['email'], $data['newPassword']);

        return $success
            ? Response::json(true, "Password updated successfully.")
            : Response::json(false, "Failed to update password.");

    } catch (Exception $e) {
        return Response::json(false, "Invalid token: " . $e->getMessage());
    }
}


    // Optional: verify token from header
    public function verifyToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            return Response::json(true, "Token valid", $decoded->data);
        } catch (Exception $e) {
            return Response::json(false, "Invalid token: " . $e->getMessage());
        }
    }


    public function getAllWardens() {
    $wardens = $this->wardens->getAllWardens();

    if (!empty($wardens)) {
        return Response::json(true, "All wardens fetched successfully", $wardens);
    } else {
        return Response::json(false, "No wardens found");
    }
}




    


}
