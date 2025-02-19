<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Headers:Content-Type, Authorization");

require_once __DIR__ . '/../../../database.php'; 

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName); 
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];  
$full_domain = $protocol."://" .$host."/main_pickle/api/byte/get_client_icon.php?file=";

if($_SERVER['REQUEST_METHOD']==="GET"){ 
 

    if(getBearerToken()){

        $client_repository = $entityManager->getRepository(MainDb\Configuration\client::class);
        $existing_domain = $client_repository->findOneBy(['domain' => $_SERVER['HTTP_HOST']]);
        if ($existing_domain) {
 
            header('HTTP/1.1 200 OK');
            echo json_encode([  
                'logo'=>$full_domain.$existing_domain->getLogo(),
                'favicon'=>$full_domain.$existing_domain->getFavicon(),
                'title'=>$existing_domain->getCourtname(),
                'description'=>$existing_domain->getDescription(),
                'theme_color'=>$existing_domain->getThemeColor()
            ]); 
  
        }else{
            header('HTTP/1.1 404 Not Found');
            echo json_encode(["Message" => "The domain is not registered yet."]);            
        }
    }


}else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}


 