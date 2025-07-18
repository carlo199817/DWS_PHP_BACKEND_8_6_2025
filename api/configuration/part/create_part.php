<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if(getBearerToken()){    
        $equipment = $entityManager->find(configuration_process\equipment::class, $input['equipment_id']);
        $part = new configuration_process\part;
        $part->setDescription($input['description']);
        $part->setQuestion($input['question']);
        $entityManager->persist($part);
        $equipment->setEquipmentpart($part);
        $entityManager->flush();
        echo header("HTTP/1.1 201 Created");
        echo json_encode(['Message' => "Equipment created"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
