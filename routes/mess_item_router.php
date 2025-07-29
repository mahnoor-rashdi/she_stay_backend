<?php

require_once __DIR__ . '/../controllers/MessItemControllers.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/Response.php';

$database = new Database();
$conn = $database->getConnection();

$controller = new MessItemController($conn);

$request = $_GET['request'] ?? '';

switch ($request) {
    case 'create':
        $data = json_decode(file_get_contents("php://input"), true);
        echo $controller->createMessItem($data);
        break;

    case 'getAll':
        echo $controller->getAllMessItems();
        break;

    case 'getById':
        if (!isset($_GET['id'])) {
            echo Response::json(false, "ID is required");
            break;
        }
        echo $controller->getMessItemById($_GET['id']);
        break;

    case 'delete':
        if (!isset($_GET['id'])) {
            echo Response::json(false, "ID is required to delete");
            break;
        }
        echo $controller->deleteMessItem($_GET['id']);
        break;

    default:
        echo Response::json(false, "Invalid request");
        break;
}
