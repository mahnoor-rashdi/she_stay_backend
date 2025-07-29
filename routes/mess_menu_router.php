<?php

require_once __DIR__ . '/../controllers/MessMenuControllers.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/Response.php';

$database = new Database();
$conn = $database->getConnection();

$menuController = new MessMenuController($conn);

$request = $_GET['request'] ?? '';

switch ($request) {
    case 'create':
        $data = json_decode(file_get_contents("php://input"), true);
        echo $menuController->createMessMenu($data);
        break;

    case 'getAll':
        echo $menuController->getAllMessMenu();
        break;

    case 'getById':
        if (!isset($_GET['id'])) {
            echo Response::json(false, "ID is required");
            break;
        }
        echo $menuController->getMessMenuById($_GET['id']);
        break;

    case 'update':
        if (!isset($_GET['id'])) {
            echo Response::json(false, "ID is required for update");
            break;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        echo $menuController->updateMessMenu($_GET['id'], $data);
        break;

    case 'delete':
        if (!isset($_GET['id'])) {
            echo Response::json(false, "ID is required for delete");
            break;
        }
        echo $menuController->deleteMessMenu($_GET['id']);
        break;

    default:
        echo Response::json(false, "Invalid request");
        break;
}
