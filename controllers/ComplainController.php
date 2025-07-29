<?php
require_once __DIR__ . '/../models/Complain.php';
require_once __DIR__ . '/../helpers/Response.php';

class ComplainControllers {
    private $complainModel;


    public function __construct($db) {
        $this->complainModel = new Complain($db);
    }

public function createComplain($data) {
    if (!isset($data['hostlerId']) || !isset($data['description']) || !isset($data['branchId']) || !isset($data['status'])) {
        return Response::json(false, "Missing required fields: hostlerId, description, branchId, status");
    }

    $result = $this->complainModel->createComplain($data);

    if ($result) {
        return Response::json(true, "Complain created successfully", $result);
    } else {
        return Response::json(false, "Failed to create complain");
    }
}




    public function getAllComplains() {
        $rooms = $this->complainModel->getAllComplains();
        return Response::json(true, "complain fetched", $rooms);
    }

    public function getComplainById($id) {
        $room = $this->complainModel->getComplainById($id);
        if ($room) {
            return Response::json(true, "complain found", $room);
        } else {
            return Response::json(false, "complain not found");
        }
    }

    public function deleteComplain($id) {
        $deleted = $this->complainModel->deleteComplain($id);
        if ($deleted) {
            return Response::json(true, "complain deleted successfully");
        } else {
            return Response::json(false, "complain deletion failed");
        }
    }

    public function updateComplain($id, $data) {
        $updated = $this->complainModel->updateComplain($id, $data);
        if ($updated) {
            return Response::json(true, "complain updated successfully", $updated);
        } else {
            return Response::json(false, "complain update failed");
        }
    }
}
