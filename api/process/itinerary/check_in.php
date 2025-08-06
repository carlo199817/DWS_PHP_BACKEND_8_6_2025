<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PATCH");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php';

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "PATCH") {

    if (getBearerToken()) {

     function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float {
        static $RADIUS_OF_EARTH_KM = 6371;
        $latDistance = deg2rad($lat2 - $lat1);
        $lonDistance = deg2rad($lon2 - $lon1);
        $a = sin($latDistance / 2) * sin($latDistance / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDistance / 2) * sin($lonDistance / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $RADIUS_OF_EARTH_KM * $c * 1000;
        return $distance;
    }

    function parseCoordinates(string $coordinates): ?array {
    $parts = explode(',', $coordinates);
    if (count($parts) === 2) {
        $lat = floatval(trim($parts[0]));
        $lon = floatval(trim($parts[1]));
        if (is_numeric($lat) && is_numeric($lon)) {
            return [$lat, $lon];
        }
    }
    return null;
   }
          $origin = new configuration\origin;

        try {

              $token = json_decode(getBearerToken(), true);
              $database = $token['database'];
              $dbConnection = new DatabaseConnection($database);
              $processDb = $dbConnection->getEntityManager();

              $itinerary_repository = $processDb->getRepository(configuration_process\itinerary::class);
              $queryBuilder = $itinerary_repository->createQueryBuilder('p');

              $timezone = new \DateTimeZone('Asia/Manila');
              $todayStart = new \DateTime('today', $timezone);
              $todayEnd = new \DateTime('today', $timezone);
              $todayEnd->setTime(23, 59, 59);


              $queryBuilder
                ->where('p.schedule BETWEEN :start AND :end')
                ->andWhere('p.assigned_to = :assignedTo')
                ->setParameter('start', $todayStart)
                ->setParameter('end', $todayEnd)
                ->setParameter('assignedTo', $token['user_id']);

              $results = $queryBuilder->getQuery()->getResult();
              $allow_check_in = "allow_check_in";

             foreach ($results as $result) {
                if($result->getId()!=$input['itinerary_id']){
                $checkIn = $result->getCheckintime();
                $checkOut = $result->getCheckouttime();
                if (!empty($checkIn) && empty($checkOut)) {
                   $allow_check_in = "forgot_to_check_out";
                }
              }
             }


              if($allow_check_in=="forgot_to_check_out"){
                 header('HTTP/1.1 409 Conflict');
                 echo json_encode(['Message'=>"Check-out missing. Please check out first !"]);
              }else{
                     $user_store = $entityManager->find(configuration\user::class ,$input['store_id']);
                     $user_coordinates = parseCoordinates($input['coordinates']);
                     $user_latitude = $user_coordinates[0];
                     $user_longitude = $user_coordinates[1];

                     $distance = calculateDistance($user_store->getStore()->getLatitude(),$user_store->getStore()->getLongitude(),$user_latitude,$user_longitude);
                     $result = ($distance <= $user_store->getStore()->getDistance()) ? true : false;

                     if($result){

                        $itinerary = $processDb->find(configuration_process\itinerary::class ,$input['itinerary_id']);
                          if(!$itinerary->getCheckintime()){
                           $timezone = new DateTimeZone('Asia/Manila');
                           $date = new DateTime('now', $timezone);
                           $itinerary->setCheckintime($date);
                           $itinerary->setCheckinimage($input['image']);
                           $itinerary->setCheckinlatitude($user_latitude);
                           $itinerary->setCheckinlongitude($user_longitude);
                           $processDb->flush();
                           header('HTTP/1.1 200 OK');
                           echo json_encode(['Message' => 'Check-in successful.']);
                        }else{
                           header('HTTP/1.1 409 Conflict');
                           echo json_encode(['Message' => 'Already checked in.']);
                        }

                     }else{
                       header('HTTP/1.1 403 Forbidden');
                       echo json_encode(['Message' => 'Check-in denied: you are not at the store location.']);
                     }
               }

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
