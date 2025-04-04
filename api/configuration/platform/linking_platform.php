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
        $platform = $entityManager->find(configuration_process\platform::class,$input['platform_id']);
        $link_platform = $entityManager->find(configuration_process\platform::class,$input['link_platform_id']);
        $new_link_platform = new configuration_process\platform;
        $new_link_platform->setDescription($link_platform->getDescription());
        $new_link_platform->setIcon($link_platform->getIcon());
        $new_link_platform->setPath($link_platform->getPath());
        $new_link_platform->setParentplatform($link_platform);
        $entityManager->persist($new_link_platform);
        $entityManager->flush();
        $platform->setPlatformlink($new_link_platform);
        $entityManager->flush();
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Linked Successfully"]);
        }
    }
    else{ 
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }
