<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Error reporting - turn off in production
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/HostlerController.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../vendor/autoload.php';

try {
    $database = new Database();
    $conn = $database->getConnection();

    $request = $_GET['request'] ?? '';
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Get input data based on method
    $data = [];
    if ($method === 'POST' || $method === 'PUT') {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON input");
        }
    }

    $hostlerController = new HostlersController($conn);

    switch ($request) {
        case 'hostler-register':
            if ($method === 'POST') {
                $result = $hostlerController->register($data);
                echo is_string($result) ? $result : json_encode($result);
            } else {
                http_response_code(405);
                echo Response::json(false, "Method not allowed. Use POST.");
            }
            break;

    case 'hostler-login':
        if ($method === 'POST') {
            $hostlerController->login($data);
        } else {
            echo Response::json(false, "Method not allowed. Use POST.");
        }
        break;

    case 'get-profile':
        if ($method === 'GET') {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? '';

            if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
                echo Response::json(false, "Authorization token not provided");
                exit;
            }

            $token = str_replace('Bearer ', '', $authHeader);
            echo $hostlerController->getUserProfile($token);
        } else {
            echo Response::json(false, "Method not allowed. Use GET.");
        }
        break;

    case 'forgot-password':
        if ($method === 'POST') {
            $hostlerController->forgotPassword($data);
        } else {
            echo Response::json(false, "Method not allowed. Use POST.");
        }
        break;

    case 'update-password':
        if ($method === 'PUT') {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? '';

            if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
                echo Response::json(false, "Authorization token not provided");
                exit;
            }

            $token = str_replace('Bearer ', '', $authHeader);
            echo $hostlerController->updatePasswordWithToken($token, $data);
        } else {
            echo Response::json(false, "Method not allowed. Use PUT.");
        }
        break;

    case 'update-hostler-profile':
        if ($method === 'PUT') {
            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? '';

            if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
                echo Response::json(false, "Authorization token not provided");
                exit;
            }

            $token = str_replace('Bearer ', '', $authHeader);
            echo $hostlerController->updateHostlerProfile($token, $data);
        } else {
            echo Response::json(false, "Method not allowed. Use PUT.");
        }
        break;

    case 'getall-hostlers':
        if ($method === 'GET') {
            $hostlerController->getAllHostlers();
        } else {
            echo Response::json(false, "Method not allowed. Use GET.");
        }
        break;

    default:
        echo json_encode(["success" => false, "message" => "Invalid endpoint"]);
}
} catch (Exception $e) {
    http_response_code(500);
    echo Response::json(false, "Server error: " . $e->getMessage());
}