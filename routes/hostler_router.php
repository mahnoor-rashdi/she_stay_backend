<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/HostlerController.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../vendor/autoload.php';

$database = new Database();
$conn = $database->getConnection();

$request = $_GET['request'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

$hostlerController = new HostlersController($conn);

switch ($request) {
    case 'hostler-register':
        $hostlerController->register($data);
        break;

    case 'hostler-login':
        $hostlerController->login($data);
        break;

    case 'get-profile':
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';

    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
        echo Response::json(false, "Authorization token not provided");
        exit;
    }

    $token = str_replace('Bearer ', '', $authHeader);
    echo $hostlerController->getUserProfile($token); // ✅ This does full job
    break;


    case 'forgot-password':
         $hostlerController->forgotPassword($data);
        break;

  case 'update-password':
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            echo Response::json(false, "Authorization token not provided");
            exit;
        }

        $token = str_replace('Bearer ', '', $authHeader);
        echo $hostlerController->updatePasswordWithToken($token, $data);
        break;


         case 'update-hostler-profile':
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            echo Response::json(false, "Authorization token not provided");
            exit;
        }

        $token = str_replace('Bearer ', '', $authHeader);
        echo $hostlerController->updateHostlerProfile($token, $data);
        break;


    default:
        echo json_encode(["success" => false, "message" => "Invalid endpoint"]);
}
