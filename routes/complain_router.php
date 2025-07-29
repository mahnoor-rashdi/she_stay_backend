<?php

require_once './../controllers/ComplainController.php';
require_once './../config/Database.php';


$database = new Database();
$conn = $database->getConnection();

$ComplainController = new ComplainControllers($conn);

// Handle based on URL
$request = $_GET['request'] ?? '';

switch ($request) {
    case 'create':
        $data = json_decode(file_get_contents("php://input"), true);
        $ComplainController->createComplain($data);
        break;

    case 'getAll':
        $ComplainController->getAllComplains();
        break;

    case 'getById':
        if (!isset($_GET['id'])) {
            Response::json(false, "Complain ID is required");
        }
        $ComplainController->getComplainById($_GET['id']);
        break;

    case 'update':
        if (!isset($_GET['id'])) {
            Response::json(false, "Complain ID is required for update");
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $ComplainController->updateComplain($_GET['id'], $data);
        break;

    case 'delete':
        if (!isset($_GET['id'])) {
            Response::json(false, "Complain ID is required for delete");
        }
        $ComplainController->deleteComplain($_GET['id']);
        break;

    default:
        Response::json(false, "Invalid request");
        break;
}
