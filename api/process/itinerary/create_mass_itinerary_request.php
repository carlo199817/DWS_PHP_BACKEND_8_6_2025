<?php
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

function validateRequest($request)
{
    $requiredFields = ["value", "justification", "date_planned","itinerary_type"];
    foreach ($requiredFields as $field) {
        if (!isset($request[$field])) {
            return false;
        }
    }
    return true;
}


function parseDate($dateStr)
{
    return  DateTime::createFromFormat('Y-m-d\TH:i', $dateStr) ?: null;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (getBearerToken()) {

        $token = json_decode(getBearerToken(), true);
        $database = json_decode(getBearerToken(), true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();
        $ahead_user = $entityManager->find(configuration\user::class,$token['user_id']);

        if (isset($input) && is_array($input)) {
            foreach ($input as $index => $request) {
                if (validateRequest($request)) {

                    $preventive = new configuration_process\preventive();
                    if($request["itinerary_type"]==2){
                      $preventive->setStore(18010);
                    }else if($request["itinerary_type"]==21){
                      $preventive->setStore(18011);
                    }else if($request["itinerary_type"]==22){
                      $preventive->setStore(18012);
                    }else{
                      $preventive->setStore($request["value"]);
                    }
                    $preventive->setUser($token['user_id']);
                    $preventive->setRemark($request["justification"]);
                    $date_planned = parseDate($request["date_planned"]);
                    $preventive->setDateplanned($date_planned);
                    $preventive->setItinerarytype($request["itinerary_type"]);
                    $timezone = new DateTimeZone('Asia/Manila');
                    $date = new DateTime('now', $timezone);
                    $preventive->setDateCreated($date);
                    $preventive->setCreatedby($ahead_user->getBidirectional()->first()->getId());
                    $processDb->persist($preventive);

                } else {
                    $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
                    $response['body'] = json_encode(['Message' => "Invalid Input"]);
                    echo json_encode($response);
                    exit;
                }
            }
            $processDb->flush();
            echo header("HTTP/1.1 200 OK");
            echo json_encode(['Message' => "Mass Itinerary Request Created"]);

        } else {
            echo header("HTTP/1.1 400 Bad Request");
            echo json_encode(['Message' => "Invalid Input"]);
        }

    }

} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
