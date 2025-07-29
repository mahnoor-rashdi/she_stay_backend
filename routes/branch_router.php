<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/BranchController.php';

$database = new Database();
$conn = $database->getConnection();

$request = $_GET['request'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

$branchController = new BranchController($conn);

switch ($request) {
    case 'create-branch':
        $branchController->createBranch($data);
        break;
    
    case 'getall-branches':
        $branchController->getAllBranches();
        break;

    case 'get-branch':
        $id = $_GET['id'] ?? null;
        
        $branchController->getBranchById($id);
        break;

    case 'update-branch':
        $id = $_GET['id'] ?? null;
        $branchController->updateBranch($id, $data);
        break;

    case 'delete-branch':
        $id = $_GET['id'] ?? null;
    
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

           
            $branchController->deleteBranch($id);
       
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Invalid request method. Use DELETE."
        ]);
    }
    break;


    default:
        echo json_encode(["status" => false, "message" => "Invalid endpoint"]);
}
