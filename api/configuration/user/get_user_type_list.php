<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "GET") {
        if(getBearerToken()){ 
        
        $user_type = $entityManager->getRepository(configuration_process\user_type::class)->findAll();
        $user_list = [];
        foreach($user_type as $type){
            array_push($user_list,['value'=>$type->getId(),
            'label'=>$type->getDescription(),
		        'valid'=>false
        ]);
        }
        echo header("HTTP/1.1 200 OK");
        echo json_encode($user_list);
        } 
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
