<?php

require_once './../controllers/AllotmentController.php';
require_once './../config/Database.php';


$database = new Database();
$conn = $database->getConnection();

$AllotmentController = new AllotmentControllers($conn);

// Handle based on URL
$request = $_GET['request'] ?? '';

switch ($request) {
    case 'create':
        $data = json_decode(file_get_contents("php://input"), true);
        $AllotmentController->createAllotment($data);
        break;

    case 'getAll':
        $AllotmentController->getAllAllotments();
        break;

    case 'getById':
        if (!isset($_GET['id'])) {
            Response::json(false, "Allotment ID is required");
        }
        $AllotmentController->getAllotmentById($_GET['id']);
        break;

    case 'update':
        if (!isset($_GET['id'])) {
            Response::json(false, "Allotment ID is required for update");
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $AllotmentController->updateAllotment($_GET['id'], $data);
        break;

    case 'delete':
        if (!isset($_GET['id'])) {
            Response::json(false, "Allotment ID is required for delete");
        }
        $AllotmentController->deleteAllotment($_GET['id']);
        break;

    default:
        Response::json(false, "Invalid request");
        break;
}
