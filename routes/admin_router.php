<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/AdminControllers.php';

$database = new Database();
$conn = $database->getConnection();

$request = $_GET['request'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

$adminController = new AdminController($conn);

switch ($request) {
    case 'admin-register':
        echo $adminController->register($data);
        break;
    case 'admin-login':
        echo $adminController->login($data);
        break;
   
    default:
        echo json_encode(["status" => false, "message" => "Invalid endpoint"]);
}
?>
