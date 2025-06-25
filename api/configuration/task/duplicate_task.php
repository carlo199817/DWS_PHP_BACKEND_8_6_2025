<?php
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
        if(getBearerToken()){

            function sortById($a, $b) {
                return  $a['series'] - $b['series'];
            }
    	$database = json_decode(getBearerToken(),true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();
        $form = $entityManager->find(configuration_process\form::class,$input['form_id']);
        $task_loop = new task_loop();
        $new_task_id = $task_loop->setLooptask($input['task_id'],$entityManager,$processDb);
        $new_task = $entityManager->find(configuration_process\task::class,$new_task_id);
        $form->setFormtask($new_task);
        $entityManager->flush();
        echo header("HTTP/1.1 200 OK");
        echo json_encode([ "Message"=>"Task Duplicate Complete!"]);
        }
    }
    else{
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
