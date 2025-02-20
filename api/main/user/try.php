<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php'; 


$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $bearerToken = getBearerToken(); 

    if ($bearerToken) {
        $tokens = new MainDb\Configuration\tokens();


        if(getBearerToken()){
            $token = json_decode(getBearerToken(),true);
            echo json_encode(["user_id" => $token['user_id']]);


    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
}


}
?>
