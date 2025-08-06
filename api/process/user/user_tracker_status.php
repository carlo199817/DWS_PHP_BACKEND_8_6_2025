<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../database.php';
$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

if ($_SERVER['REQUEST_METHOD'] === "GET") {

        if(getBearerToken()){

function formatTotalDuration(array $results): string
{
    $totalSeconds = count($results);

    $hours = floor($totalSeconds / 3600);
    $minutes = floor(($totalSeconds % 3600) / 60);
    $seconds = $totalSeconds % 60;

    return "{$hours}h {$minutes}m {$seconds}s";
}



            $token = json_decode(getBearerToken(),true);
            $databaseName = $token['database'];
            $dbConnection = new DatabaseConnection($databaseName);
            $processDb = $dbConnection->getEntityManager();

            $user = $entityManager->find(configuration\user::class,$token['user_id']);


            if($user->getStart()){

            $user_tracker_repository = $processDb->getRepository(process\user_tracker::class);
            $queryBuilder = $user_tracker_repository->createQueryBuilder('p');

$timezone = new \DateTimeZone('Asia/Manila');
$start = new \DateTime('today', $timezone);
$end = new \DateTime('today', $timezone);
$end->setTime(23, 59, 59);

$queryBuilder
    ->where('p.date_created BETWEEN :start AND :end')
    ->andWhere('p.created_by = :createdBy')
    ->setParameter('start', $start)
    ->setParameter('end', $end)
    ->setParameter('createdBy', $token['user_id']);

$results = $queryBuilder->getQuery()->getResult();

               if($results){
                echo header("HTTP/1.1 200 OK");
                echo json_encode([
                             'duration'=>formatTotalDuration($results),
                             'status'=>$user->getStart()
                            ]);
               }else{
                 echo header("HTTP/1.1 200 OK");
                 echo json_encode([
                                'duration'=>null,
                                'status'=>$user->getStart()
                                ]);
               }
            }else{

            $user_tracker_repository = $processDb->getRepository(process\user_tracker::class);
            $queryBuilder = $user_tracker_repository->createQueryBuilder('p');

            $start = new \DateTime('now' . ' 00:00:00');
            $end = new \DateTime('now' . ' 23:59:59');

            $queryBuilder
               ->where('p.date_created BETWEEN :start AND :end')
               ->andWhere('p.created_by = :createdBy')
               ->setParameter('start', $start)
               ->setParameter('end', $end)
               ->setParameter('createdBy', $token['user_id']);

             $results = $queryBuilder->getQuery()->getResult();
                if($results){
                echo header("HTTP/1.1 200 OK");
                echo json_encode([
                             'duration'=>formatTotalDuration($results),
                             'status'=>$user->getStart()
                             ]);
                }else{
                 echo header("HTTP/1.1 200 OK");
                 echo json_encode(['duration'=>null]);
               }
            }
        }

    }else{
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
