<?php
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
        $limit = !empty($input['limit']) ? (int) $input['limit'] : 1;
        $task_list = [];
        $form = $task->getForm()->first();
        $count_task = $task->getTasklink();
        $user = $entityManager->find(configuration\user::class, $token['user_id']);
        if (count($count_task)) {
            foreach ($task->getTasklink() as $link) {
                $form_id = setTask($link, $entityManager, $processDb, $token, $form);
            }
        } else {
            $form_id = setTask($task, $entityManager, $processDb, $token, $form);
        }
        $get_ahead = getAhead($user, $limit);
        foreach ($get_ahead as $head) {
            $user_process = $processDb->find(process\user::class, $head->getId());
            if (!$user_process) {
                $user_process = new process\user();
                $user_process->setId($head->getId());
                $processDb->persist($user_process);
            }

            foreach ($user_process->getUserformconnection() as $form) {
                $user_process->removeUserformconnection($user_process->getUserformconnection(), $form);
                $processDb->flush();
            }


           $user_process->setUserformconnection($form_id);
            $processDb->flush();
        }

        echo header("HTTP/1.1 200 OK");
        echo json_encode(["Message" => "Submit Successfully"]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}

function setTask($task, $entityManager, $processDb, $token, $form)
{
    $form_status = null;
    if ($task->getTaskassign()) {
        foreach ($task->getTaskassign() as $assign) {
            $user = $entityManager->find(configuration\user::class, $token['user_id']);
            if ($user->getUsertype()->getId() === $assign->getUsertype()) {
                $update_assign = $processDb->find(configuration_process\assign::class, $assign->getId());
                $update_assign->setValid(true);
                $processDb->flush();
            }
            if ($update_assign->getValid()) {
                $form_status = "valid";
            }
        }
    }

    if ($task->getTaskvalidation()) {
        foreach ($task->getTaskvalidation() as $validation) {
            $user = $entityManager->find(configuration\user::class, $token['user_id']);
            if ($user->getUsertype()->getId() === $assign->getUsertype()) {
                $update_validation = $processDb->find(configuration_process\validation::class, $validation->getId());
                $update_validation->setValid(true);
                $processDb->flush();
            }
            if ($update_validation->getValid()) {
                $form_status = "valid";
            }
        }
    }

    if ($form_status === "valid") {
        $form->setDone(true);
        $processDb->flush();
    }

    return $form;
}


function getAhead($user, $limit)
{
    $result = [];
    traverseAhead($user, $limit, $result);
    return $result;
}

function traverseAhead($user, $remaining, &$result)
{
    if ($remaining <= 0 || !$user) return;

    $bidirectionals = $user->getBidirectional();

    foreach ($bidirectionals as $head) {
        $result[] = $head;
        traverseAhead($head, $remaining - 1, $result);
        break;
    }
}
