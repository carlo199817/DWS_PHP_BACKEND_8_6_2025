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

        if(getBearerToken()){

            if($input['new_task_id']){
            $input_field = $entityManager->find(configuration_process\field::class,$input['field_id']);
            $formula = $input_field->getFormula();
            $new_formula = json_decode($formula, true);
            $new_formula['choices'][] = ['value' => $input['new_task_id'],'label' => $input['new_task_id'],'result'=>true];
            $input_field->setFormula(json_encode($new_formula));
            $entityManager->flush();
            }else{

            $input_field = $entityManager->find(configuration_process\field::class,$input['field_id']);
            $formula = $input_field->getFormula();
            $new_formula = json_decode($formula, true);

            if(count($new_formula['choices'])!=1){

               array_pop($new_formula['choices']);
               $input_field->setFormula(json_encode($new_formula));
               $entityManager->flush();
             }

            }
            echo header("HTTP/1.1 200 OK");
            echo json_encode(['Message' => "Changed Successfully"]);

        }

    }else{
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }

