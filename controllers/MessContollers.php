<?php
require_once __DIR__ . '/../models/Mess.php';
require_once __DIR__ . '/../helpers/Response.php';

class MessController {
    private $messModel;

    public function __construct($db) {
        $this->messModel = new Mess($db);
    }

    // Create a new mess entry
    public function createMess($data) {
        if (
            empty($data['day']) ||
            empty($data['mealType']) ||
            empty($data['branchId']) ||
            empty($data['messTimeFrom']) ||
            empty($data['messTimeTo'])
        ) {
            return Response::json(false, "Missing required fields");
        }

        $result = $this->messModel->createMess($data);
        if ($result) {
            return Response::json(true, "Mess entry created successfully", $result);
        } else {
            return Response::json(false, "Failed to create mess entry");
        }
    }

    // Get all mess entries
    public function getAllMess() {
        $messList = $this->messModel->getAllMess();
        return Response::json(true, "Mess list fetched successfully", $messList);
    }

    // Get single mess entry by ID
    public function getMessById($id) {
        $mess = $this->messModel->getMessById($id);
        if ($mess) {
            return Response::json(true, "Mess entry found", $mess);
        } else {
            return Response::json(false, "Mess entry not found");
        }
    }

    // Delete a mess entry
    public function deleteMess($id) {
        $deleted = $this->messModel->deleteMess($id);
        if ($deleted) {
            return Response::json(true, "Mess entry deleted successfully");
        } else {
            return Response::json(false, "Failed to delete mess entry");
        }
    }

    // Update a mess entry
    public function updateMess($id, $data) {
        $updated = $this->messModel->updateMess($id, $data);
        if ($updated) {
            return Response::json(true, "Mess entry updated successfully", $updated);
        } else {
            return Response::json(false, "Failed to update mess entry");
        }
    }
}
?>
