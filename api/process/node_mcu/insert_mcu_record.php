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

            $token = json_decode(getBearerToken(),true);
            $databaseName = $token['database'];
            $dbConnection = new DatabaseConnection($databaseName);
            $processDb = $dbConnection->getEntityManager();
            $timezone = new DateTimeZone('Asia/Manila');
            $date = new DateTime('now', $timezone);

            $node_mcu_repository = $entityManager->getRepository(configuration\node_mcu::class);
            $existing_node_mcu = $node_mcu_repository->findOneBy(['description' => $input['node_mcu']]);

            if($existing_node_mcu){

              $new_node_mcu_record = new process\node_mcu_record;
              $new_node_mcu_record->setNodemcu($existing_node_mcu->getId());
              $new_node_mcu_record->setDescription($input['description']);
              $new_node_mcu_record->setDatecreated($date);
              $processDb->persist($new_node_mcu_record);
              $processDb->flush();

              echo header("HTTP/1.1 201 OK");
              echo json_encode(["Message"=>"Oven temperature recorded !"]);

            }else{

              $new_node_mcu = new configuration\node_mcu;
              $new_node_mcu->setDescription($input['node_mcu']);
              $new_node_mcu->setDatecreated($date);
              $new_node_mcu->setActive(true);
              $entityManager->persist($new_node_mcu);
              $entityManager->flush();

              echo header("HTTP/1.1 201 OK");
              echo json_encode(["Message"=>"New node mcu registered !"]);

            }


        }

    }else{
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
