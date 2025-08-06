<?php
// Enable error reporting for debugging

use Doctrine\DBAL\Configuration;

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
        $field = $entityManager->find(configuration_process\field::class,$input['field_id']);
        $userIds = $input['user_type_id'];

        foreach($field->getFieldusertype() as $user_type){
            $field->removeFieldusertype($field->getFieldusertype(),$user_type);
            $entityManager->flush();
        }
        foreach ($userIds as $userId) {
            $user_type = $entityManager->find(configuration_process\user_type::class, $userId);
            if ($user_type) {
                $field->setFieldfieldtype($user_type);
            }
        }
        $entityManager->flush();
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Linked Successfully"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
