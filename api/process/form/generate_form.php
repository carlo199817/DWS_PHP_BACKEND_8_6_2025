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

             $form_id = null;
             $form = $entityManager->find(configuration_process\form::class,$input['form_id']);
             $latest = getReachedFormlinkData($form->getFormlink());
             $form_id = $latest->getId();
              if($form_id){
                $new_form = new configuration_process\automation_form();
                $new_form->setForm(json_encode($form_id));
                $new_form->setCreatedby($token['user_id']);
                $processDb->persist($new_form);
                $processDb->flush();
              }

    header('HTTP/1.1 200 OK');
    echo json_encode(["Message" => "Form submitted for generation !"]);

    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
