<?php

require_once './../controllers/RoomController.php';
require_once './../config/Database.php';


$database = new Database();
$conn = $database->getConnection();

$roomController = new RoomController($conn);

// Handle based on URL
$request = $_GET['request'] ?? '';

switch ($request) {
    case 'create':
        $data = json_decode(file_get_contents("php://input"), true);
        $roomController->createRoom($data);
        break;

    case 'getAll':
        $roomController->getAllRooms();
        break;

    case 'getById':
        if (!isset($_GET['id'])) {
            Response::json(false, "Room ID is required");
        }
        $roomController->getRoomById($_GET['id']);
        break;

    case 'update':
        if (!isset($_GET['id'])) {
            Response::json(false, "Room ID is required for update");
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $roomController->updateRoom($_GET['id'], $data);
        break;

    case 'delete':
        if (!isset($_GET['id'])) {
            Response::json(false, "Room ID is required for delete");
        }
        $roomController->deleteRoom($_GET['id']);
        break;

    default:
        Response::json(false, "Invalid request");
        break;
}
