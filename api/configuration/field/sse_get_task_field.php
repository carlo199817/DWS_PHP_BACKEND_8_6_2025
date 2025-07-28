<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/../../../database.php';
$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$processDb = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

$task_id = isset($_GET['task_id']) ? $_GET['task_id'] : null;
$identifier = isset($_GET['identifier']) ? $_GET['identifier'] : null;

if ($_SERVER['REQUEST_METHOD'] === "GET") {

    $origin = new configuration\origin;

        if(getBearerToken()){
            while (true) {

                if(json_decode($identifier,true)){
                   $database = json_decode(getBearerToken(), true)['database'];
                   $dbConnection = new DatabaseConnection($database);
                   $processDb = $dbConnection->getEntityManager();
                }

                $task = $processDb->find(configuration_process\task::class,$task_id);
                $field_list = [];
                foreach ($task->getTaskfield() as $field) {
                    $type = $entityManager->find(configuration_process\field_type::class,$field->getFieldtype());
                    $path = $entityManager->find(configuration\path::class,$type->getPath());
                    array_push($field_list,['id'=>$field->getId(),'field_type'=>$type->getDescription(),
                    'formula'=>$field->getFormula(),
                    'style'=>$field->getStyle(),
                    'row_occupied'=>$field->getRowoccupied(),
                    'col_occupied'=>$field->getColoccupied(),
                    'row_no'=>$field->getRowno(),
                    'col_no'=>$field->getColno(),
		    'radio'=>$field->getRadio(),
		    'status'=>"R",
		    "activate_style"=>$field->getActivatestyle(),
	            'answer'=>$field->getAnswer(),
                 ]);
                }
               echo "data: " .json_encode($field_list). "\n\n";
            }
        }

    }else{
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
