<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PATCH");
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
        if(getBearerToken()){    
        $equipment = $entityManager->find(configuration_process\equipment::class,$input['equipment_id']);
        $partIds = $input['part_id']; 

        foreach($equipment->getEquipmentpart() as $part){
            $equipment->removeEquipmentpart($equipment->getEquipmentpart(),$part);
            $entityManager->flush();
        }
        foreach ($partIds as $partId) {
            $part = $entityManager->find(configuration_process\part::class, $partId);
            if ($part) {
                $equipment->setEquipmentpart($part); 
            }
        }
        $entityManager->flush();
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Linked Successfully"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
