<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../database.php'; 
$databaseName = "dws_db_2025"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();

$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$mainDb = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if(getBearerToken()){
            $token = json_decode(getBearerToken(),true);
            $itinerary = $entityManager->find(configuration_process\itinerary::class, $input['itinerary_id']);
            $justification_list = [];
            foreach($itinerary->getItineraryjustification() as $justification){
                $user =$mainDb->find(configuration\user::class, $justification->getCreatedby()->getId());
                $justification_list[] = [
                    "id" =>$justification->getId(),
                    "description" =>$justification->getDescription(),
                    "date_created" =>$justification->getDatecreated()->format('Y-m-d H:i:s'),
                    "created_by" => $user->getFirstname() . " " . $user->getLastname(),  
                ];
            }
            echo header("HTTP/1.1 200 OK");
            echo json_encode($justification_list);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
