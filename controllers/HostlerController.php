<?php
require_once __DIR__ . '/../models/Hostlers.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../vendor/autoload.php';


//   USING JSON WEB TOKEN PACKGES TO CREATE TOKEN AND VERIFY TOKEN
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class HostlersController {
    private $hostlers;
    private $jwtSecret ;// Keep it safe and hidden


  public function __construct($conn) {
        $this->hostlers = new Hostlers($conn);
        $this->jwtSecret = getenv('JWT_SECRET');
    }

    public function register($data) {
        if (
            empty($data['username']) || empty($data['cnic']) || empty($data['email']) ||
            empty($data['password']) || empty($data['guardianCnic']) ||
            empty($data['guardianNumber']) || empty($data['mobileNumber'])
        ) {
            return Response::json(false, "All fields are required.");
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return Response::json(false, "Invalid email format.");
        }

        $success = $this->hostlers->register(
            $data['username'],
            $data['cnic'],
            $data['email'],
            $data['password'],
            $data['guardianCnic'],
            $data['guardianNumber'],
            $data['mobileNumber']
        );

        return $success
            ? Response::json(true, "Hostler registered successfully.",$data)
            : Response::json(false, "Registration failed.");
    }


    public function login($data) {
        if (empty($data['email']) || empty($data['password'])) {
            return Response::json(false, "Email and password are required.");
        }

        $user = $this->hostlers->login($data['email']);

        if ($user && password_verify($data['password'], $user['password'])) {
            unset($user['password']); // Hide hashed password

            // Generate JWT token
            $payload = [
                "iss" => "http://localhost:8080", // issuer
                "aud" => "http://localhost:8080",
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

public function getUserProfile($token) {
    try {
        $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        $userId = $decoded->data->id;
    

        $user = $this->hostlers->fetchHostlerProfile($userId);

        if ($user) {
            unset($user['password']); // Hide password for security
            return Response::json(true, "Profile fetched successfully", $user);
        } else {
            return Response::json(false, "User not found");
        }

    } catch (Exception $e) {
        return Response::json(false, "Invalid token: " . $e->getMessage());
    }
}

public function updateHostlerProfile($token,$data) {
    try {
        $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        $userId = $decoded->data->id;
    

        $user = $this->hostlers->fetchHostlerProfile($userId);

         if (!$user) {
            return Response::json(false, "User not found");
        }
        $updatedData = [
            'id' => $userId,
            'username' => $data['username'] ?? $user['username'],
            'email' => $data['email'] ?? $user['email'],
            'cnic' => $data['cnic'] ?? $user['cnic'],
            'guardianCnic' => $data['guardianCnic'] ?? $user['guardianCnic'],
            'guardianNumber' => $data['guardianNumber'] ?? $user['guardianNumber'],
            'mobileNumber' => $data['mobileNumber'] ?? $user['mobileNumber'],
        ];

        $updated = $this->hostlers->updateHostlerProfile($updatedData);

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
    $user = $this->hostlers->login($data['email']); // Reusing login() to get user by email
    if (!$user) {
        return Response::json(false, "Email not found.");
    }

    $success = $this->hostlers->updatePasswordByEmail($data['email'], $data['newPassword']);

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

        $user = $this->hostlers->fetchHostlerProfile($userId);
        // print_r($user);
        if (!$user) {
            return Response::json(false, "User not found.");
        }

        $success = $this->hostlers->updatePasswordByEmail($user['email'], $data['newPassword']);

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
}
