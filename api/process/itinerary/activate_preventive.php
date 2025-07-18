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

function forceTimezoneLabel(DateTime $date, string $targetTimezone): DateTime {
    return DateTime::createFromFormat(
        'Y-m-d H:i:s.u',
        $date->format('Y-m-d H:i:s.u'),
        new DateTimeZone($targetTimezone)
    );
}


             function getReachedFormlinkData($formlinks)
{
    if ($formlinks === null || $formlinks->isEmpty()) {
        return null;
    }

    $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $now->modify('+1 minute');
    $now = forceTimezoneLabel($now, 'UTC');

    $latestReached = null;

    foreach ($formlinks as $link) {
        $date = $link->getDateeffective();

        if ($date === null) {
            continue;
        }

        $localDate = clone $date;
        $localDate->setTimezone(new DateTimeZone('UTC'));

        if ($localDate <= $now && ($latestReached === null || $localDate > $latestReached->getDateeffective())) {
            $latestReached = $link;
        }
    }

    return $latestReached;
}



            $token = json_decode(getBearerToken(), true);
            $database = $token['database'];
            $dbConnection = new DatabaseConnection($database);
            $processDb = $dbConnection->getEntityManager();

            $preventive_repository = $processDb->getRepository(configuration_process\preventive::class);
            $queryBuilder = $preventive_repository->createQueryBuilder('p');
            $startDate = $input['start_date'];
            $endDate = $input['end_date'];

            $start = new \DateTime($startDate . ' 00:00:00');
            $end = new \DateTime($endDate . ' 23:59:59');

            $queryBuilder
               ->where('p.date_planned BETWEEN :start AND :end')
               ->andWhere('p.created_by = :createdBy')
               ->andWhere('p.itinerary_id IS NULL')
               ->setParameter('start', $start)
               ->setParameter('end', $end)
               ->setParameter('createdBy', $token['user_id']);

            $queryBuilder->andWhere('p.remove IS NULL OR p.remove = false');
            $results = $queryBuilder->getQuery()->getResult();

            foreach ($results as $result) {
             $form_id_list = [];
             $itinerary_type = $entityManager->find(configuration_process\itinerary_type::class,$result->getItinerarytype());
                foreach($itinerary_type->getItinerarytypeform() as $form){
                    $latest = getReachedFormlinkData($form->getFormlink());
                    array_push($form_id_list,$latest->getId());
                }
              $new_itinerary = new configuration_process\automation_itinerary();
              $new_itinerary->setPreventive($result->getId());
              $new_itinerary->setItinerarytype($result->getItinerarytype());
              $new_itinerary->setStore($result->getStore());
              $new_itinerary->setJustification($result->getRemark());
              $new_itinerary->setSchedule($result->getDateplanned());
              $timezone = new DateTimeZone('Asia/Manila');
              $date = new DateTime('now', $timezone);
              $new_itinerary->setDateCreated($date);
              $new_itinerary->setAssignedto($result->getUser());
              $new_itinerary->setCreatedby($token['user_id']);
              $new_itinerary->setApprovedby($token['user_id']);
              $new_itinerary->setForm(json_encode($form_id_list));
              $processDb->persist($new_itinerary);
              $processDb->flush();

            }

    header('HTTP/1.1 200 OK');
    echo json_encode(["Message" => "Planned distributed !"]);

    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
