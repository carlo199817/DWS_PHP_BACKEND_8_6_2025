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
              $databaseName = $token['database'];
              $dbConnection = new DatabaseConnection($databaseName);
              $processDb = $dbConnection->getEntityManager();
        $tagRepository = $entityManager->getRepository(configuration\tag::class);
        $existingTag = $tagRepository->findOneBy(['description' => $input['description']]);
        if (!$existingTag) {

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
            $user = $entityManager->find(configuration\user::class,$token['user_id']);
            $user_store = $entityManager->find(configuration\user::class,$results[0]->getStore());
            $tag = new configuration\tag;
            $tag->setDescription($input['description']);
            $tag->setBrand($input['brand']);
            $tag->setModel($input['model']);
            $tag->setSerial($input['serial']);
            $timezone = new DateTimeZone('Asia/Manila');
            $date = new DateTime('now', $timezone);
            $tag->setDatecreated($date);
            $tag->setCreatedby($user);
            $tag->setStore($user_store->getStore()->getId());
            $entityManager->persist($tag);
            $entityManager->flush();
            echo header("HTTP/1.1 201 Created");
            echo json_encode(['Message' => "Tag created"]);
        } else {
            header('HTTP/1.1 409 Conflict');
            echo json_encode(["Message" => "Tag already exist"]);
        }
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
