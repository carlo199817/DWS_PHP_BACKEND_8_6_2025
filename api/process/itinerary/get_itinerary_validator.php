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

    if (getBearerToken()) {

        $origin = new configuration\origin;
        $token = json_decode(getBearerToken(), true);
        $database = json_decode(getBearerToken(), true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();

        $user = $entityManager->find(configuration\user::class,$token['user_id']);
        $itinerary = $processDb->find(configuration_process\itinerary::class, $input['itinerary_id']);
        $itinerary_validator_list = [];


$hasMatchedValidator = false;
foreach ($itinerary->getItineraryvalidation() as $validator) {
    if ($validator->getUsertype() !== null && $user->getUsertype()->getId() == $validator->getUsertype()) {
        $hasMatchedValidator = true;
        break;
    }
}

        foreach ($itinerary->getItineraryvalidation() as $validator) {
          $user_type = null;
          $created_by = null;
          if($validator->getUsertype()){
           $user_type = $entityManager->find(configuration_process\user_type::class,$validator->getUsertype());
          }

          if($validator->getCreatedby()){
           $created_by = $entityManager->find(configuration\user::class,$validator->getCreatedby());
          }


           $path = null;
           if($validator->getPath()){
            $path = $entityManager->find(configuration\path::class,$validator->getPath());
           }

   $able = false;
    if ($validator->getUsertype()) {
        if ($user->getUsertype()->getId() == $validator->getUsertype()) {
            $able = true;
        }
    } else {
        if (!$hasMatchedValidator) {
            $able = true;
        }
    }
            $itinerary_validator_list[] = [
                                         'id'=>$validator->getId(),
                                         'user_type_id'=>$validator->getUsertype(),
                                         'user_type'=>$user_type?$user_type->getDescription():"Any technician supervisor",
                                         'remark'=>$validator->getValidationremark(),
                                         'name'=>$validator->getUsertype()!=2?$created_by?$created_by->getFirstname()." ".$created_by->getLastname():null:$validator->getName(),
                                         'signature'=>$validator->getSignature()?$origin->getOrigin($path->getDescription(), $validator->getSignature()):null,
                                         'able'=>$able
                                        ];
        }

	echo header("HTTP/1.1 200 OK");
        echo json_encode($itinerary_validator_list);
    }

} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
