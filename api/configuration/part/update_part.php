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
    if (getBearerToken()) {
        $partRepository = $entityManager->getRepository(configuration_process\part::class);
        $existingPart = $partRepository->findOneBy(['description' => $input['description']]);
        if (!$existingPart) {

            $part = $entityManager->find(configuration_process\part::class, $input['part_id']);
            $part->setDescription($input['description']);
            $part->setQuestion($input['question']);
            $entityManager->flush();
            echo header("HTTP/1.1 200 OK");
            echo json_encode(['Message' => "Part Successfully updated"]);
           }else{
        if(json_encode($existingPart->getId())===$input['part_id']){
            $part = $entityManager->find(configuration_process\part::class, $input['part_id']);
            $part->setDescription($input['description']);
            $part->setQuestion($input['question']);
            $entityManager->flush();
            echo header("HTTP/1.1 200 OK");
            echo json_encode(['Message' => "Part Successfully updated"]);
        }else{

            header('HTTP/1.1 409 Conflict');
            echo json_encode(["Message" => "Part already exist"]);
         }
       }
    }

} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
