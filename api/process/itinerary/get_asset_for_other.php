<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php';

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (getBearerToken()) {

          $origin = new configuration\origin;

        try {

              $token = json_decode(getBearerToken(), true);
              $database = $token['database'];
              $dbConnection = new DatabaseConnection($database);
              $processDb = $dbConnection->getEntityManager();

               $itinerary = $processDb->find(configuration_process\itinerary::class,$input['itinerary_id']);
                $itinerary_list = [];
                $itinerary_asset_list = [];
                $selected_asset = [];
                 foreach($itinerary->getItineraryasset() as $asset){
                   if($asset->getSelected()){
                     $selected_asset = ['value'=>$asset->getId(),'label'=>$asset->getDescription()];
                   }
                   array_push($itinerary_asset_list,['value'=>$asset->getId(),'label'=>$asset->getDescription()]);
                 }

                           array_push($itinerary_list,[
                           'selected_asset'=>$selected_asset,
                           'itinerary_asset'=>$itinerary_asset_list
                           ]);

              header('HTTP/1.1 200 OK');
              echo json_encode($itinerary_list);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
