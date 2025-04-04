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
    $origin = new configuration\origin;
    $bearerToken = getBearerToken();
    if(getBearerToken()){

            $platform = $entityManager->find(configuration_process\platform::class,$input['platform_id']);
            $path = $entityManager->find(configuration\path::class, $platform->getPath());

            $sub_platform_list = [];
            foreach($platform->getPlatformlink() as $sub_platform){

            array_push($sub_platform_list,[
                'id'=>$sub_platform->getId(),
                'description'=>$sub_platform->getDescription(),
                'picture'=>$origin->getOrigin($path->getDescription(),$sub_platform->getIcon())
             ]);

            }

            function sortById($a, $b) {
                return $b['id'] - $a['id'];
               }
            usort($sub_platform_list, 'sortById');

            header('HTTP/1.1 200 OK');
            echo json_encode($sub_platform_list);

    }
}
else{
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
