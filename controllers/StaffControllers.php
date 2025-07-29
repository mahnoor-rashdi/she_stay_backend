<?php
require_once __DIR__ . '/../models/Staff.php';
require_once __DIR__ . '/../helpers/Response.php';

class StaffController {
    private $staffModel;

    public function __construct($db) {
        $this->staffModel = new Staff($db);
    }

    // Create a new staff member
    public function createStaff($data) {
        if (
            empty($data['name']) ||
            !isset($data['workType']) ||  // can be '0' or '1'
            empty($data['salary']) ||
            empty($data['cnic']) ||
            empty($data['phoneNumber']) ||
            empty($data['branchId'])
        ) {
            return Response::json(false, "Missing required fields");
        }

        $result = $this->staffModel->createStaff($data);
        if ($result) {
            return Response::json(true, "Staff created successfully", $result);
        } else {
            return Response::json(false, "Failed to create staff");
        }
    }

    // Get all staff
    public function getAllStaff() {
        $staff = $this->staffModel->getAllStaff();
        return Response::json(true, "Staff list fetched successfully", $staff);
    }

    // Get single staff by ID
    public function getStaffById($id) {
        $staff = $this->staffModel->getStaffById($id);
        if ($staff) {
            return Response::json(true, "Staff found", $staff);
        } else {
            return Response::json(false, "Staff not found");
        }
    }

    // Delete a staff member
    public function deleteStaff($id) {
        $deleted = $this->staffModel->deleteStaff($id);
        if ($deleted) {
            return Response::json(true, "Staff deleted successfully");
        } else {
            return Response::json(false, "Failed to delete staff");
        }
    }

    // Update a staff member
    public function updateStaff($id, $data) {
        $updated = $this->staffModel->updateStaff($id, $data);
        if ($updated) {
            return Response::json(true, "Staff updated successfully", $updated);
        } else {
            return Response::json(false, "Failed to update staff");
        }
    }
}



?>