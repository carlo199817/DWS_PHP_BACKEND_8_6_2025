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

              $itinerary_repository = $processDb->getRepository(configuration_process\itinerary::class);
              $queryBuilder = $itinerary_repository->createQueryBuilder('p');

              $timezone = new \DateTimeZone('Asia/Manila');
              $todayStart = new \DateTime($input['schedule'].'00:00:00', $timezone);
              $todayEnd = new \DateTime($input['schedule'].'23:59:59', $timezone);

              $queryBuilder
                ->where('p.schedule BETWEEN :start AND :end')
                ->andWhere('p.assigned_to = :assignedTo')
                ->setParameter('start', $todayStart)
                ->setParameter('end', $todayEnd)
                ->setParameter('assignedTo', $token['user_id']);

              $results = $queryBuilder->getQuery()->getResult();
              $itinerary_list = [];

              foreach ($results as $result) {

               $itinerary_type = $entityManager->find(configuration_process\itinerary_type::class,$result->getType());
               $user_store = $entityManager->find(configuration\user::class,$result->getStore());
               $path = $entityManager->find(configuration\path::class, $result->getPath());

                 array_push($itinerary_list,[
                           'id'=>$result->getId(),
                           'itinerary_type'=>$itinerary_type->getDescription(),
                           'store'=>$user_store->getStore()->getOutletname(),
                           'store_id'=>$result->getStore(),
                           'check_in'=>$result->getCheckintime()?$result->getCheckintime()->format('Y-m-d\TH:i'):null,
                           'check_out'=>$result->getCheckouttime()?$result->getCheckouttime()->format('Y-m-d\TH:i'):null,
                           'check_in_image'=>$result->getCheckinimage()?$origin->getOrigin($path->getDescription(),$result->getCheckinimage())
                                             :$origin->getOrigin($path->getDescription(),"no_image.png"),
                           'check_out_image'=>$result->getCheckoutimage()?$origin->getOrigin($path->getDescription(),$result->getCheckoutimage())
                                             :$origin->getOrigin($path->getDescription(),"no_image.png"),
                           ]);
              }


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
