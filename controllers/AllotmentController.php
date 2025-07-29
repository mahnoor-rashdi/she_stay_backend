<?php
require_once __DIR__ . '/../models/Allotment.php';
require_once __DIR__ . '/../helpers/Response.php';

class AllotmentControllers {
    private $allotmentModel;

    public function __construct($db) {
        $this->allotmentModel = new Allotment($db);
    }

    public function createAllotment($data) {
    if (empty($data['hostlerId']) || empty($data['roomId']) || empty($data['branchId']) || empty($data['status'])) {
        return Response::json(false, "Missing required fields: hostlerId, roomId, branchId");
    }

    $result = $this->allotmentModel->createAllotment($data);
    
    if ($result) {
        return Response::json(true, "Allotment created successfully", $result);
    } else {
        return Response::json(false, "Failed to create allotment");
    }
}


    public function getAllAllotments() {
        $rooms = $this->allotmentModel->getAllAllotments();
        return Response::json(true, "Allotment fetched", $rooms);
    }

    public function getAllotmentById($id) {
        $room = $this->allotmentModel->getAllotmentById($id);
        if ($room) {
            return Response::json(true, "Allotment found", $room);
        } else {
            return Response::json(false, "Allotment not found");
        }
    }

    public function deleteAllotment($id) {
        $deleted = $this->allotmentModel->deleteAllotment($id);
        if ($deleted) {
            return Response::json(true, "Allotment deleted successfully");
        } else {
            return Response::json(false, "Allotment deletion failed");
        }
    }

    public function updateAllotment($id, $data) {
        $updated = $this->allotmentModel->updateAllotment($id, $data);
        if ($updated) {
            return Response::json(true, "Allotment updated successfully", $updated);
        } else {
            return Response::json(false, "Allotment update failed");
        }
    }
}
