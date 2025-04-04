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
        if(getBearerToken()){    
        $token = json_decode(getBearerToken(),true);
        $task = $entityManager->find(configuration_process\task::class,$input['task_id']);
        $task->setTitle($input['title']);
        $task->setDescription($input['description']);
        $user_types =  $input['user_type'];

        foreach($task->getTaskvalidation() as $validation){
            $task->removeTaskvalidation($task->getTaskvalidation(),$validation);
            $entityManager->flush();
        }
            foreach($user_types as $user_type){
            $validation = new configuration_process\validation;            
            $validation->setCreatedby($token['user_id']);
            $validation->setUsertype( $user_type);
            $validation->setValid(true);
            $entityManager->persist($validation);
            $entityManager->flush();
            $task->setTaskvalidation($validation);
           
        }    
        
         $entityManager->flush();
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Task updated"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
    
