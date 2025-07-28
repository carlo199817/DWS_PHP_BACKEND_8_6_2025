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
        $user = $processDb->find(process\user::class, $token['user_id']);
        $form_list = [];

        $date_input = $input['date'];
        if ($date_input === null) {
            $date_input = new DateTime('now', new DateTimeZone('Asia/Manila'));
        } else {
            $date_input = new DateTime($date_input, new DateTimeZone('Asia/Manila'));
        }

        foreach ($user?->getUserformgenerator() ?? [] as $form) {
            if ($date_input->format('Y-m-d') === $form->getDatecreated()->format('Y-m-d')) {
                $form_list[] = [
                    "id" => $form->getId(),
                    'title' => $form->getTitle(),
                    'distributed'=>$form->getDistributed()
                ];
            }
        }

        echo header("HTTP/1.1 200 OK");
        echo json_encode($form_list);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
