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
