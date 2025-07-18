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
        try {
            $token = json_decode(getBearerToken(), true);
            $database = $token['database'];
            $dbConnection = new DatabaseConnection($database);
            $processDb = $dbConnection->getEntityManager();

            $preventive_repository = $processDb->getRepository(configuration_process\preventive::class);
            $queryBuilder = $preventive_repository->createQueryBuilder('p');
            $startDate = $input['start_date'];
            $endDate = $input['end_date'];
            $userId = $input['user_id'] ?? null;
            $storeId = $input['store_id'] ?? null;


            $start = new \DateTime($startDate . ' 00:00:00');
            $end = new \DateTime($endDate . ' 23:59:59');

            $queryBuilder
               ->where('p.date_planned BETWEEN :start AND :end')
               ->andWhere('p.created_by = :createdBy')
               ->setParameter('start', $start)
               ->setParameter('end', $end)
               ->setParameter('createdBy', $token['user_id']);

            if (!empty($userId)) {
                $queryBuilder->andWhere('p.user_id = :userId')
                    ->setParameter('userId', $userId);
            }

            if (!empty($storeId)) {
                $queryBuilder->andWhere('p.store_id = :storeId')
                    ->setParameter('storeId', $storeId);
            }

            $queryBuilder->andWhere('p.remove IS NULL OR p.remove = false');

            $results = $queryBuilder->getQuery()->getResult();
            $preemptive_list = [];
            $itinerary = null;

            foreach ($results as $result) {

              $status_color = 'gray';

                if ($result->getItinerary()) {

                     $itinerary = $processDb->find(configuration_process\itinerary::class, $result->getItinerary());
                     if($itinerary){
                     $status_color = 'blue';
                     $timezone = new DateTimeZone('Asia/Manila');
                     $now = new DateTime('now');
                     $schedule = $itinerary->getSchedule();
                     $schedule->setTimezone($timezone);

                     $checkinTime = $itinerary->getCheckintime();
                     $checkoutTime = $itinerary->getCheckouttime();

                     if ( $schedule->format('Y-m-d') !== $now->format('Y-m-d') && $schedule < $now && !$checkinTime && !$checkoutTime ) {
                         $status_color = 'red';
                     }

                     if ($checkinTime) {
                       if ($checkinTime < $schedule) {
                           if ($checkoutTime) {
                             $status_color = 'purple';
                           }
                       }
                     }

                  if ($checkinTime && $checkoutTime && $checkinTime->format('Y-m-d') > $schedule->format('Y-m-d')) {
                     $status_color = 'teal';
                    }

                    if($checkinTime &&  $checkoutTime){
                       if ($checkinTime->format('Y-m-d') === $schedule->format('Y-m-d') && $checkoutTime->format('Y-m-d') === $schedule->format('Y-m-d')){
                        $status_color = 'green';
                       }
                     }

                    if ($checkinTime && !$checkoutTime && $schedule->format('Y-m-d') === $now->format('Y-m-d')) {
                      $status_color = 'orange';
                    }
                    if ($checkinTime && !$checkoutTime && $schedule < $now && $schedule->format('Y-m-d') < $now->format('Y-m-d')) {
                      $status_color = 'brown';
                    }

                   }

                 }

                $user = $entityManager->find(configuration\user::class, $result->getUser());
                $created_by = $entityManager->find(configuration\user::class, $result->getCreatedby());
                $store = $entityManager->find(configuration\user::class, $result->getStore());

                $preemptive_list[] = [
                    'id' => $result->getId(),
                    'user' => $user->getFirstname() . ' ' . $user->getLastname(),
                    'store' => $store->getStore()->getOutletname(),
                    'itinerary_id' =>  $itinerary ? $result->getItinerary() :  $itinerary,
                    'date_created' => $result->getDatecreated()->format('Y-m-d'),
                    'date_planned' => $result->getDateplanned()->format('Y-m-d'),
                    'remark' => $result->getRemark(),
                    'created_by' => $created_by->getFirstname() . ' ' . $created_by->getLastname(),
                    'status_color' => $status_color
                ];
            }

              header('HTTP/1.1 200 OK');
              echo json_encode($preemptive_list);

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
