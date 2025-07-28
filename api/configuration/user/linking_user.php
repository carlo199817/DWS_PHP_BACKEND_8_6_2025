<?php
ini_set('display_Errors', 1);
Error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PATCH");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();

$input = (array) json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
    if (getBearerToken()) {

        $token = json_decode(getBearerToken(),true);
        $identifier = $input['identifier'];
        $user = $entityManager->find(configuration\user::class, $input['user_id']);
        $iam_user = $entityManager->find(configuration\user::class, $token['user_id']);
        $link_user = $entityManager->find(configuration\user::class, $input['link_user_id']);

        if (!$user || !$link_user) {
            http_response_code(404);
            echo json_encode(["Message" => "User or link user not found"]);
            exit;
        }


        if (
            $user->getUserlink()->contains($link_user) ||
            $link_user->getUserlink()->contains($user)
        ) {
            http_response_code(409);
            echo json_encode(["Message" => "Already Existing"]);
            exit;
        }

       foreach($link_user->getBidirectional() as $link){
         $link->removeUserlink($link->getUserlink(),$link_user);
        }



        if ($identifier) {
	   $link_user->getUserlink()->add($user);
        } else {
           $user->getUserlink()->add($link_user);
        }

        $entityManager->flush();
        http_response_code(200);
        echo json_encode(['Message' => "Linked successfully"]);

    } else {
        http_response_code(401);
        echo json_encode(["Message" => "Authorization token not found."]);
    }

} else {
    http_response_code(405);
    echo json_encode(["Message" => "Method Not Allowed"]);
}
