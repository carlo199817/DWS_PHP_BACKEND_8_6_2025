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
    if (getBearerToken()) {

              $token = json_decode(getBearerToken(), true);
              $tagRepository = $entityManager->getRepository(configuration\tag::class);
              $existingTag = $tagRepository->findOneBy(['description' => $input['description']]);
           if (!$existingTag) {
            $user = $entityManager->find(configuration\user::class,$token['user_id']);
            $tag = new configuration\tag;
            $tag->setDescription($input['description']);
            $tag->setBrand($input['brand']);
            $tag->setModel($input['model']);
            $tag->setSerial($input['serial']);
            $tag->setStore($input['store_id']);
            $tag->setCreatedby($user);
            $timezone = new DateTimeZone('Asia/Manila');
            $date = new DateTime('now', $timezone);
            $tag->setDatecreated($date);
            $entityManager->persist($tag);
            $entityManager->flush();
            echo header("HTTP/1.1 201 Created");
            echo json_encode(['Message' => "Tag created"]);
        } else {
            header('HTTP/1.1 409 Conflict');
            echo json_encode(["Message" => "Tag already exist"]);
        }
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
