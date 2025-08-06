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
$processDb = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
    if (getBearerToken()) {

        $token = json_decode(getBearerToken(), true);
        $database = json_decode(getBearerToken(), true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();

        $form = $processDb->find(configuration_process\form::class, $input['form_id']);
        $form->setStore($input['store_id']);
        $user_store = $entityManager->find(configuration\store::class, $input['store_id']);

        $existing_user = $processDb->find(process\user::class, $input['store_id']);
        if($existing_user){
           $existing_user->setUserformconnection($form);
           $processDb->flush();
        }else{
           $user = new process\user;
           $user->setId( $input['store_id']);
           $processDb->persist($user);
           $user->setUserformconnection($form);
           $processDb->flush();
        }


        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message'=>"Form forwarded to store !"]);
    }

} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
