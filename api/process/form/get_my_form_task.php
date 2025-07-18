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
$processDb = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getBearerToken()) {
        $token = json_decode(getBearerToken(), true);

        if($input['identifier']){
          $database = json_decode(getBearerToken(), true)['database'];
          $dbConnection = new DatabaseConnection($database);
          $processDb = $dbConnection->getEntityManager();
         }
        $form = $processDb->find(configuration_process\form::class, $input['form_id']);
        $task_list = [];
        foreach ($form->getFormtask() as $task) {
            foreach ($task->getTaskassign() as $assign) {
                $user = $entityManager->find(configuration\user::class, $token['user_id']);

               if($input['identifier']){
                if ($user->getUsertype()->getId() === $assign->getUsertype()) {
                    $task = $processDb->find(configuration_process\task::class, $task);
                    $task_list[] = [
                        "id" => $task->getId(),
                        "title" => $task->getTitle(),
			"valid"=>$assign->getValid()
                    ];
                }
              }else{
                    $task = $processDb->find(configuration_process\task::class, $task);
                    $task_list[] = [
                        "id" => $task->getId(),
                        "title" => $task->getTitle(),
                    ];
               }
            }
        }
        echo header("HTTP/1.1 200 OK");
        echo json_encode($task_list);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
