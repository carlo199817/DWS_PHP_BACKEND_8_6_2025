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
        $token = json_decode(getBearerToken(), true);
        $user = $entityManager->find(configuration\user::class, $token['user_id']);
        $user->setChangepassword(true);
        $user->setPassword($input['new_password']);
        $entityManager->flush();
        header('HTTP/1.1 200 OK');  
        echo json_encode(["Message" => "Password Changed Successfully"]);
    }

}
else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
