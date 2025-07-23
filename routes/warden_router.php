<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/WardenControllers.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../vendor/autoload.php';

$database = new Database();
$conn = $database->getConnection();

$request = $_GET['request'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

$wardensController = new WardenControllers($conn);

switch ($request) {
    case 'wardens-register':
        $wardensController->register($data);
        break;

    case 'wardens-login':
        $wardensController->login($data);
        break;

    case 'get-profile':
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';

    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
        echo Response::json(false, "Authorization token not provided");
        exit;
    }

    $token = str_replace('Bearer ', '', $authHeader);
    echo $wardensController->getWardensProfile($token); // ✅ This does full job
    break;


    case 'forgot-password':
         $wardensController->forgotPassword($data);
        break;

  case 'update-password':
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            echo Response::json(false, "Authorization token not provided");
            exit;
        }

        $token = str_replace('Bearer ', '', $authHeader);
        echo $wardensController->updatePasswordWithToken($token, $data);
        break;


         case 'update-profile':
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            echo Response::json(false, "Authorization token not provided");
            exit;
        }

        $token = str_replace('Bearer ', '', $authHeader);
        echo $wardensController->updateWardensProfile($token, $data);
        break;

   case 'getall-wardens':
         $wardensController->getAllWardens();
        break;
    default:
        echo json_encode(["success" => false, "message" => "Invalid endpoint"]);
}
