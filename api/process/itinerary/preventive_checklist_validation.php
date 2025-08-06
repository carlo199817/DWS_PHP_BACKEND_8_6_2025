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


function getAllLatestReachedSchedules(array $results): ?array
{
    $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $now->modify('+1 minute');

    $latestDate = null;
    $matchedSchedules = [];

    foreach ($results as $result) {
        $schedules = $result->getSchedules();

        foreach ($schedules as $schedule) {
            $date = $schedule->getDateeffective();
            $date->setTimezone(new DateTimeZone('Asia/Manila'));

            if ($date <= $now) {
                if ($latestDate === null || $date > $latestDate) {
                    // Found a new latest date — reset collection
                    $latestDate = $date;
                    $matchedSchedules = [$schedule];
                } elseif ($date == $latestDate) {
                    // Same latest date — add to collection
                    $matchedSchedules[] = $schedule;
                }
            }
        }
    }

    return !empty($matchedSchedules) ? $matchedSchedules : null;
}

        $origin = new configuration\origin;
        $token = json_decode(getBearerToken(), true);
        $database = json_decode(getBearerToken(), true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();

        $validation = $processDb->find(configuration_process\validation::class,$input['validation_id']);
        $user = $entityManager->find(configuration\user::class,$token['user_id']);
        if($user->getUsertype()->getId()==2){
          $validation->setName($input['name']);
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
        $existing_user = $processDb->find(process\user::class,$itinerary->getStore());

         if($existing_user){
          $existing_user->setUseritineraryconnection($itinerary);
          $processDb->flush();
         }else{
          $user = new process\user;
          $user->setId($itinerary->getStore());
          $processDb->persist($user);
          $user->setUseritineraryconnection($itinerary);
          $processDb->flush();
         }

        $user_assign_repository = $processDb->getRepository(process\user_assign::class);

        $results = $user_assign_repository->createQueryBuilder('ua')
        ->where('ua.user_id = :user_id')
        ->setParameter('user_id', $itinerary->getStore())
        ->getQuery()
        ->getResult();


$matchedSchedules = getAllLatestReachedSchedules($results);
$matchedUserIds = [];

foreach ($matchedSchedules as $schedule) {
    $userId = $schedule->getUser();
    $matchedUserIds[] = $userId;
}

$uniqueUserIds = array_unique($matchedUserIds);

foreach ($uniqueUserIds as $userId) {

         $existing_user = $processDb->find(process\user::class,$userId);
         if($existing_user){
          $existing_user->setUseritineraryconnection($itinerary);
          $processDb->flush();
         }else{
          $user = new process\user;
          $user->setId($itinerary->getStore());
          $processDb->persist($user);
          $user->setUseritineraryconnection($itinerary);
          $processDb->flush();
         }
}



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
