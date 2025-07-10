<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Headers:Content-Type, Authorization");
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
        $token = json_decode(getBearerToken(), true);
        $database = $token['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();

        $preventive = $processDb->find(configuration_process\preventive::class, $input['preventive_id']);
        $user = $entityManager->find(configuration\user::class, $preventive->getUser());
        $created_by = $entityManager->find(configuration\user::class, $preventive->getCreatedby());
        $store = $entityManager->find(configuration\user::class, $preventive->getStore());
        $itinerary_type = $entityManager->find(configuration_process\itinerary_type::class, $preventive->getItinerarytype());
        header('HTTP/1.1 200 OK');
        echo json_encode([
            'id' => $preventive->getId(),
            'user_id' => $user->getId(),
            'itinerary_type' => $itinerary_type->getDescription(),
            'itinerary_type_id' => $preventive->getItinerarytype(),
            'user' => "( ".$user->getUsertype()->getDescription()." ) ".$user->getFirstname() . ' ' . $user->getLastname(),
            'store' => $store->getStore()->getOutletname(),
            'store_id'=> $store->getId(),
            'itinerary' => $preventive->getItinerary(),
            'date_planned' => $preventive->getDateplanned()->format('Y-m-d\TH:i'),
            'remark' => $preventive->getRemark(),
            'created_by' => $created_by->getFirstname() . ' ' . $created_by->getLastname(),
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["Message" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
