<?php
require_once __DIR__ . '/../models/MessMenu.php';
require_once __DIR__ . '/../helpers/Response.php';

class MessMenuController {
    private $messMenuModel;

    public function __construct($db) {
        $this->messMenuModel = new MessMenu($db);
    }

    public function createMessMenu($data) {
        if (empty($data['itemName']) || !isset($data['status'])) {
            return Response::json(false, "Missing required fields");
        }

        $result = $this->messMenuModel->createMessMenu($data);
        if ($result) {
            return Response::json(true, "Mess menu item created successfully", $result);
        } else {
            return Response::json(false, "Failed to create mess menu item");
        }
    }

    public function getAllMessMenu() {
        $items = $this->messMenuModel->getAllMessMenu();
        return Response::json(true, "Mess menu fetched", $items);
    }

    public function getMessMenuById($id) {
        $item = $this->messMenuModel->getMessMenuById($id);
        if ($item) {
            return Response::json(true, "Item found", $item);
        } else {
            return Response::json(false, "Item not found");
        }
    }

    public function updateMessMenu($id, $data) {
        $updated = $this->messMenuModel->updateMessMenu($id, $data);
        if ($updated) {
            return Response::json(true, "Menu item updated successfully", $updated);
        } else {
            return Response::json(false, "Failed to update item");
        }
    }

    public function deleteMessMenu($id) {
        $deleted = $this->messMenuModel->deleteMessMenu($id);
        if ($deleted) {
            return Response::json(true, "Item deleted successfully");
        } else {
            return Response::json(false, "Failed to delete item");
        }
    }
}
