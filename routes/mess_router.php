<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/Response.php'; 
require_once __DIR__ . '/../controllers/MessContollers.php'; // ✅ fixed filename

$database = new Database();
$conn = $database->getConnection();
$messController = new MessController($conn);

$request = $_GET['request'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

switch ($request) {
    case 'create':
        $messController->createMess($data);
        break;

    case 'getAll':
        $messController->getAllMess();
        break;

    case 'getById':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $messController->getMessById($id);
        break;

    case 'update':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $messController->updateMess($id, $data);
        break;

    case 'delete':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $messController->deleteMess($id);
        break;

    default:
        echo Response::json(false, "Invalid request.");
        break;
}
