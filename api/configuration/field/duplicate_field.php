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
        if(getBearerToken()){
          $task = $entityManager->find(configuration_process\task::class,$input['task_id']);
          $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
           $new_field = new configuration_process\field;
           $new_field->setFieldtype($field->getFieldtype());
           $new_field->setFormula($field->getFormula());
           $new_field->setUsertype($field->getUsertype());
           $new_field->setRowoccupied($field->getRowoccupied());
           $new_field->setColoccupied($field->getColoccupied());
           $new_field->setRowno($input['row_no']);
           $new_field->setColno($input['col_no']);
           $new_field->setAnswer($field->getAnswer());
           $new_field->setActivatestyle($field->getActivatestyle());
	   $new_field->setStyle($field->getStyle());
           $entityManager->persist($new_field);
           $entityManager->flush();
           $task->setTaskfield($new_field);
           $entityManager->flush();
           echo header("HTTP/1.1 201 Created");
           echo json_encode(['Message' => "Field duplicate"]);
        }
    }else{
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
