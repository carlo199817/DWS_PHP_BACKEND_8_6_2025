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

$input = (array)json_decode(file_get_contents('php://input'), true);
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    if (getBearerToken()) {

              $token = json_decode(getBearerToken(), true);
              $databaseName = $token['database'];
              $dbConnection = new DatabaseConnection($databaseName);
              $processDb = $dbConnection->getEntityManager();

              $itinerary_repository = $processDb->getRepository(configuration_process\itinerary::class);
              $queryBuilder = $itinerary_repository->createQueryBuilder('p');

              $timezone = new \DateTimeZone('Asia/Manila');
              $todayStart = new \DateTime('now'.'00:00:00', $timezone);
              $todayEnd = new \DateTime('now'.'23:59:59', $timezone);

              $queryBuilder
                ->where('p.schedule BETWEEN :start AND :end')
                ->andWhere('p.assigned_to = :assignedTo')
                ->andWhere('p.check_in_time IS NOT NULL')
                ->andWhere('p.check_out_time IS NULL')
                ->setParameter('start', $todayStart)
                ->setParameter('end', $todayEnd)
                ->setParameter('assignedTo', $token['user_id']);

              $results = $queryBuilder->getQuery()->getResult();

              $user_store = $entityManager->find(configuration\user::class,$results[0]->getStore());
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('t')
         ->from(configuration\tag::class, 't')
         ->where($queryBuilder->expr()->eq('t.store_id', ':store_id'))
         ->setParameter('store_id',$user_store->getStore()->getId());

        $tags = $queryBuilder->getQuery()->getResult();
        $tag_list = [];

            foreach ($tags as $tag) {

                $tag_list[] = [
                    'value' => $tag->getId(),
                    'label' => $tag->getDescription(), 

                ];
            }
            header('HTTP/1.1 200 OK');
            echo json_encode($tag_list);


    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}



?>
