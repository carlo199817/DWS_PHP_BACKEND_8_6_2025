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

        $assetRepository = $entityManager->getRepository(configuration_process\asset::class);
        $existingAsset = $assetRepository->findOneBy(['description' => $input['description']]);
        if (!$existingAsset) {
            $asset = new configuration_process\asset;
            $asset->setDescription($input['description']);
            $entityManager->persist($asset);
            $entityManager->flush();
            echo header("HTTP/1.1 201 Created");
            echo json_encode(['Message' => "Asset created"]);
        } else {
            header('HTTP/1.1 409 Conflict');
            echo json_encode(["Message" => "Asset already exist"]);
        }
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
