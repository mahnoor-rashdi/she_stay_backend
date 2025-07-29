<?php
require_once __DIR__ . '/../models/Fees.php';
require_once __DIR__ . '/../helpers/Response.php';

class FeesControllers {
    private $feesModel;


    public function __construct($db) {
        $this->feesModel = new Fees($db);
    }

public function createFees($data) {
    if (!isset($data['hostlerId']) || !isset($data['arriesAmount']) || !isset($data['branchId']) || !isset($data['currentAmount'])  || !isset($data['totalAmount']) || !isset($data['paidAmount'])|| !isset($data['discount'])|| !isset($data['paymentMethod'])|| !isset($data['feesMonth'])|| !isset($data['feesPaidDate'])|| !isset($data['status'])) {
        return Response::json(false, "Missing required fields:");
    }

    $result = $this->feesModel->createFees($data);

    if ($result) {
        return Response::json(true, "fees created successfully", $result);
    } else {
        return Response::json(false, "Failed to create fess");
    }
}



    public function getAllFees() {
        $fees = $this->feesModel->getAllFees();
        return Response::json(true, "fees fetched", $fees);
    }

    public function getFeesById($id) {
        $fess = $this->feesModel->getFeesById($id);
        if ($fess) {
            return Response::json(true, "fess found", $fess);
        } else {
            return Response::json(false, "fess not found");
        }
    }

    public function deleteFess($id) {
        $deleted = $this->feesModel->deleteFees($id);
     if ($deleted) {
            return Response::json(true, "fess deleted successfully");
        } else {
            return Response::json(false, "fess deletion failed");
        }
    }

    public function updateFess($id, $data) {
        $updated = $this->feesModel->updatefees($id, $data);
     if ($updated) {
            return Response::json(true, "fess updated successfully", $updated);
        } else {
            return Response::json(false, "fess update failed");
        }
    }
}
