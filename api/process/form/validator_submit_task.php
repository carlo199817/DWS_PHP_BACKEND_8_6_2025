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
        $token = json_decode(getBearerToken(), true);
        $database = json_decode(getBearerToken(), true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();
        $task = $processDb->find(configuration_process\task::class, $input['task_id']);
        $task_list = [];
        $form = $task->getForm()->first();

        if (count($task->getTasklink())) {
            foreach ($task->getTasklink() as $link) {
                setTask($link, $entityManager, $processDb, $token, $form);
            }
        } else {
            setTask($task, $entityManager, $processDb, $token, $form);
        }

        echo header("HTTP/1.1 200 OK");
        echo json_encode(["Message" => "Validate Successfully"]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}

function setTask($task, $entityManager, $processDb, $token, $form)
{
    $user = $entityManager->find(configuration\user::class, $token['user_id']);



    if ($task->getTaskvalidation()) {
        foreach ($task->getTaskvalidation() as $validation) {
            if ($user->getUsertype()->getId() === $validation->getUsertype()) {
                $update_validation = $processDb->find(configuration_process\validation::class, $validation->getId());
                $update_validation->setValid(true);
                $update_validation->setCreatedby($token['user_id']);
                $timezone = new DateTimeZone('Asia/Manila');
                $date = new DateTime('now', $timezone);
                $update_validation->setDateCreated($date);
                $processDb->flush();
            }
        }
    }

    $all_valid = true;

    if ($task->getTaskassign()) {
        foreach ($task->getTaskassign() as $assign) {
            if (!$assign->getValid()) {
                $all_valid = false;
                break;
            }
        }
    }

    if ($task->getTaskvalidation() && $all_valid) {
        foreach ($task->getTaskvalidation() as $validation) {
            if (!$validation->getValid()) {
                $all_valid = false;
                break;
            }
        }
    }

    if ($all_valid) {
        $form->setDone(true);
        $processDb->flush();
    }
}
