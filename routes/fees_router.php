<?php

require_once './../controllers/FeesControllers.php';
require_once './../config/Database.php';


$database = new Database();
$conn = $database->getConnection();

$FeesController = new FeesControllers($conn);

// Handle based on URL
$request = $_GET['request'] ?? '';

switch ($request) {
    case 'create':
        $data = json_decode(file_get_contents("php://input"), true);
        $FeesController->createFees($data);
        break;

    case 'getAll':
        $FeesController->getAllFees();
        break;

    case 'getById':
        if (!isset($_GET['id'])) {
            Response::json(false, "Fess ID is required");
        }
        $FeesController->getFeesById($_GET['id']);
        break;

    case 'update':
        if (!isset($_GET['id'])) {
            Response::json(false, "Fess ID is required for update");
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $FeesController->updateFess($_GET['id'], $data);
        break;

    case 'delete':
        if (!isset($_GET['id'])) {
            Response::json(false, "Fess ID is required for delete");
        }
        $FeesController->deleteFess($_GET['id']);
        break;

    default:
        Response::json(false, "Invalid request");
        break;
}
