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

              $itinerary = $processDb->find(configuration_process\itinerary::class, $input['itinerary_id']);

               $user_store = $entityManager->find(configuration\user::class,$itinerary->getStore());
               $path = $entityManager->find(configuration\path::class, $itinerary->getPath());


              header('HTTP/1.1 200 OK');
              echo json_encode([
                           'id'=>$itinerary->getId(),
                           'store'=>$user_store->getStore()->getOutletname(),
                           'address'=>$user_store->getStore()->getAddress(),
                           'check_in'=>$itinerary->getCheckintime()?$itinerary->getCheckintime()->format('Y-m-d\TH:i'):null,
                           'check_out'=>$itinerary->getCheckouttime()?$itinerary->getCheckouttime()->format('Y-m-d\TH:i'):null,
                           'check_in_image'=>$itinerary->getCheckinimage()?$origin->getOrigin($path->getDescription(),$itinerary->getCheckinimage())
                                             :$origin->getOrigin($path->getDescription(),"no_image.png"),
                           'check_out_image'=>$itinerary->getCheckoutimage()?$origin->getOrigin($path->getDescription(),$itinerary->getCheckoutimage())
                                             :$origin->getOrigin($path->getDescription(),"no_image.png"),
                            'check_in_latitude'=>$itinerary->getCheckinlatitude(),
                            'check_in_longitude'=>$itinerary->getCheckinlongitude(),
                            'check_out_latitude'=>$itinerary->getCheckoutlatitude(),
                            'check_out_longitude'=>$itinerary->getCheckoutlongitude(),
                           ]);

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
