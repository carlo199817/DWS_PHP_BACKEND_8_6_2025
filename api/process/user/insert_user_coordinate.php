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
            $user = $entityManager->find(configuration\user::class,$token['user_id']);
            $timezone = new DateTimeZone('Asia/Manila');
            $date = new DateTime('now', $timezone);
            $user->setLocation($input['latitude'].",".$input['longitude']);
            $user->setTimelocation($date);
            $entityManager->flush();

            if($user->getStart()){
              $database = json_decode(getBearerToken(),true)['database'];
              $dbConnection = new DatabaseConnection($database);
              $entityManager = $dbConnection->getEntityManager();
              $new_user_tracker = new process\user_tracker;
              $new_user_tracker->setLatitude($input['latitude']);
              $new_user_tracker->setLongitude($input['longitude']);
              $new_user_tracker->setDatecreated($date);
              $new_user_tracker->setCreatedby($token['user_id']);
              $entityManager->persist($new_user_tracker);
              $entityManager->flush();
              echo header("HTTP/1.1 200 OK");
              echo json_encode(["Message"=>"User coordinates recorded!"]);
           }else{
            echo header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Start time is not yet active"]);
          }
        }

    }else{
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
