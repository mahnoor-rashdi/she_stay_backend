<?php
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../helpers/Response.php';

class AdminController {
    private $admin;

    public function __construct($conn) {
        $this->admin = new Admin($conn);
    }


    public function register($data) {
        if (!$data['username'] || !$data['email'] || !$data['password']) {
            return Response::json(false, "All fields are required.");
        }

        $result = $this->admin->register($data['username'], $data['email'], $data['password']);
        return $result
            ? Response::json(true, "Admin registered successfully.")
            : Response::json(false, "Registration failed.");
    }

    public function login($data) {
        if (!$data['email'] || !$data['password']) {
            return Response::json(false, "Email and password are required.");
        }

        $user = $this->admin->login($data['email']);
        if ($user && password_verify($data['password'], $user['password'])) {
            unset($user['password']); // hide password
            return Response::json(true, "Login successful.", $user);
        } else {
            return Response::json(false, "Invalid email or password.");
        }
    }
}
?>
