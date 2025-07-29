<?php

require_once __DIR__ . '/../models/Branches.php';
require_once __DIR__ . '/../helpers/Response.php';

class BranchController {
    private $branchModel;

    public function __construct($db) {
        $this->branchModel = new Branches($db);
    }

public function createBranch($data) {
    if (empty($data['address']) || empty($data['branchName'])) {
        Response::json(false, "Address and Branch Name are required");
    }

    // Create branch and return the full data
    $createdBranch = $this->branchModel->createBranch($data['address'], $data['branchName']);

    if ($createdBranch) {
        Response::json(true, "Branch created successfully", $createdBranch);
    } else {
        Response::json(false, "Failed to create branch");
    }
}


    public function getAllBranches() {
        $branches = $this->branchModel->getAllBranches();

        if (!empty($branches)) {
            Response::json(true, "Branches fetched successfully", $branches);
        } else {
            Response::json(false, "No branches found");
        }
    }



    public function getBranchById($id) {
        if (empty($id)) {
            Response::json(false, "Branch ID is required");
        }

        $branch = $this->branchModel->getBranchById($id);

        if ($branch) {
            Response::json(true, "Branch fetched successfully", $branch);
        } else {
            Response::json(false, "Branch not found");
        }
    }

public function updateBranch($id, $data) {
    if (empty($data['address']) || empty($data['branchName'])) {
        Response::json(false, "Address and Branch Name are required");
    }

    $updatedBranch = $this->branchModel->updateBranch($id, $data['address'], $data['branchName']);

    if ($updatedBranch) {
        Response::json(true, "Branch updated successfully", $updatedBranch);
    } else {
        Response::json(false, "Failed to update branch");
    }
}

    public function deleteBranch($id) {
        if (empty($id)) {
            Response::json(false, "Branch ID is required");
        }

        $success = $this->branchModel->deleteBranch($id);

        if ($success) {
            Response::json(true, "Branch deleted successfully");
        } else {
            Response::json(false, "Failed to delete branch");
        }
    }
}
