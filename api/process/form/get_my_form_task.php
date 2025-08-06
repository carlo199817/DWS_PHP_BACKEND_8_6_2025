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

         if($input['database'] === 'process'){
          $database = json_decode(getBearerToken(), true)['database'];
          $dbConnection = new DatabaseConnection($database);
          $processDb = $dbConnection->getEntityManager();
         }

         function unique(array $array, string $key): array {
          $unique = [];
          $seen = [];

         foreach ($array as $item) {
           if (!in_array($item[$key], $seen)) {
            $seen[] = $item[$key];
            $unique[] = $item;
           }
         }

         return $unique;
       }


        $form = $processDb->find(configuration_process\form::class, $input['form_id']);
        $task_list = [];
        foreach ($form->getFormtask() as $task) {

            foreach ($task->getTaskassign() as $assign) {
                $user = $entityManager->find(configuration\user::class, $token['user_id']);

               if($input['database']==="process"){

                if ($user->getUsertype()->getId() === $assign->getUsertype()) {
                    $task = $processDb->find(configuration_process\task::class, $task);
                    $task_list[] = [
                        "id" => $task->getId(),
                        "title" => $task->getTitle(),
			"valid"=>$assign->getValid(),
                        "task_type"=>"Task"
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

            foreach ($task->getTaskvalidation() as $validator) {
                $user = $entityManager->find(configuration\user::class, $token['user_id']);

               if($input['database']==="process"){

                if ($user->getUsertype()->getId() === $validator->getUsertype()) {
                    $task = $processDb->find(configuration_process\task::class, $task);
                    $task_list[] = [
                        "id" => $task->getId(),
                        "title" => $task->getTitle(),
                        "valid"=>$validator->getValid(),
                        "task_type"=>"Validation"
                    ];
                }

              }

            }



        }
        $uniqueById = unique($task_list, 'id');
        echo header("HTTP/1.1 200 OK");
        echo json_encode($uniqueById);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
