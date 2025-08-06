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

    if (getBearerToken()) {

        $origin = new configuration\origin;
        $token = json_decode(getBearerToken(), true);
        $database = json_decode(getBearerToken(), true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();

        $validation = $processDb->find(configuration_process\validation::class,$input['validation_id']);
        $user = $entityManager->find(configuration\user::class,$token['user_id']);


        if($user->getUsertype()->getId()==2){
         if($input['name']!=""){
          $validation->setName($input['name']);
         }else{
           http_response_code(409);
           echo json_encode(["Message" => "Name is required!"]);
           exit;
          }
        }
        $validation->setSignature($input['signature']);
        $timezone = new DateTimeZone('Asia/Manila');
        $date = new DateTime('now', $timezone);
        $validation->setDateCreated($date);
        $validation->setPath(7);
        $validation->setCreatedby($token['user_id']);
        $validation->setValid(true);
        $validation->setValidationremark($input['validation_remark']);
        $processDb->flush();

        $valid = true;
        $itinerary = $processDb->find(configuration_process\itinerary::class,$input['itinerary_id']);

        foreach ($itinerary->getItineraryvalidation() as $validator) {
          if(!$validator->getValid()){
           $valid = false;
          }
        }
        if($valid){
         $itinerary->setDone(true);
         $processDb->flush();
        }

	echo header("HTTP/1.1 200 OK");
        echo json_encode(["Message" => "Validation successful !"]);
    }

} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
