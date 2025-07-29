<?php

require_once __DIR__ . '/../controllers/StaffControllers.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/Response.php'; // Make sure Response class is included

$database = new Database();
$conn = $database->getConnection();

$staffController = new StaffController($conn);

// Get request type from query
$request = $_GET['request'] ?? '';

switch ($request) {
    case 'create':
        $data = json_decode(file_get_contents("php://input"), true);
        echo $staffController->createStaff($data);
        break;

    case 'getAll':
        echo $staffController->getAllStaff();
        break;

    case 'getById':
        if (!isset($_GET['id'])) {
            echo Response::json(false, "Staff ID is required");
            break;
        }
        echo $staffController->getStaffById($_GET['id']);
        break;

    case 'update':
        if (!isset($_GET['id'])) {
            echo Response::json(false, "Staff ID is required for update");
            break;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        echo $staffController->updateStaff($_GET['id'], $data);
        break;

    case 'delete':
        if (!isset($_GET['id'])) {
            echo Response::json(false, "Staff ID is required for delete");
            break;
        }
        echo $staffController->deleteStaff($_GET['id']);
        break;

    default:
        echo Response::json(false, "Invalid request");
        break;
}
