<?php
require_once __DIR__ . '/../models/Rooms.php';
require_once __DIR__ . '/../helpers/Response.php';

class RoomController {
    private $roomModel;

    public function __construct($db) {
        $this->roomModel = new Room($db);
    }

    public function createRoom($data) {
        if (empty($data['roomNo']) || empty($data['branchId'])) {
            return Response::json(false, "Missing required fields");
        }

        $result = $this->roomModel->createRoom($data);
        if ($result) {
            return Response::json(true, "Room created successfully", $result);
        } else {
            return Response::json(false, "Failed to create room");
        }
    }

    public function getAllRooms() {
        $rooms = $this->roomModel->getAllRooms();
        return Response::json(true, "Rooms fetched", $rooms);
    }

    public function getRoomById($id) {
        $room = $this->roomModel->getRoomById($id);
        if ($room) {
            return Response::json(true, "Room found", $room);
        } else {
            return Response::json(false, "Room not found");
        }
    }

    public function deleteRoom($id) {
        $deleted = $this->roomModel->deleteRoom($id);
        if ($deleted) {
            return Response::json(true, "Room deleted successfully");
        } else {
            return Response::json(false, "Room deletion failed");
        }
    }

    public function updateRoom($id, $data) {
        $updated = $this->roomModel->updateRoom($id, $data);
        if ($updated) {
            return Response::json(true, "Room updated successfully", $updated);
        } else {
            return Response::json(false, "Room update failed");
        }
    }
}
