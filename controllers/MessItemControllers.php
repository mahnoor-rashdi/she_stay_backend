<?php
require_once __DIR__ . '/../models/MessItem.php';
require_once __DIR__ . '/../helpers/Response.php';

class MessItemController {
    private $model;

    public function __construct($db) {
        $this->model = new MessItem($db);
    }

    public function createMessItem($data) {
        if (empty($data['messId']) || empty($data['messMenuId'])) {
            return Response::json(false, "messId and messMenuId are required");
        }

        $result = $this->model->createMessItem($data);
        if ($result) {
            return Response::json(true, "Mess item linked successfully", $result);
        } else {
            return Response::json(false, "Failed to link mess item");
        }
    }

    public function getAllMessItems() {
        $items = $this->model->getAllMessItems();
        return Response::json(true, "All mess items fetched", $items);
    }

    public function getMessItemById($id) {
        $item = $this->model->getMessItemById($id);
        if ($item) {
            return Response::json(true, "Item found", $item);
        } else {
            return Response::json(false, "Item not found");
        }
    }

    public function deleteMessItem($id) {
        $deleted = $this->model->deleteMessItem($id);
        if ($deleted) {
            return Response::json(true, "Item deleted successfully");
        } else {
            return Response::json(false, "Failed to delete item");
        }
    }
}
