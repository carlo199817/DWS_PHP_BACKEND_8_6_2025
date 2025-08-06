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
        $token = json_decode(getBearerToken(), true);
        $database = json_decode(getBearerToken(), true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();

        $itinerary = $processDb->find(configuration_process\itinerary::class,$input['itinerary_id']);
        $user_tracker_repository = $processDb->getRepository(process\user_tracker::class);
        $inputDate = new \DateTime($input['date']);
        $start = (clone $inputDate)->setTime(0, 0, 0);
        $end = (clone $inputDate)->setTime(23, 59, 59);

        $qb = $user_tracker_repository->createQueryBuilder('ut');
        $qb->where('ut.created_by = :userId')
         ->andWhere('ut.date_created BETWEEN :start AND :end')
         ->setParameter('userId', $itinerary->getAssignedto())
         ->setParameter('start', $start)
         ->setParameter('end', $end);
        $results = $qb->getQuery()->getResult();
        $track_list = [];
        foreach($results as $result){
          $track_list[] = [ 'id'=>$result->getId(),'longitude'=>$result->getLongitude(),'latitude'=>$result->getLatitude(),"date_created"=>$result->getDatecreated()->format('Y-m-d H-i-s')];
        }

        echo header("HTTP/1.1 200 OK");
        echo json_encode($track_list);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
