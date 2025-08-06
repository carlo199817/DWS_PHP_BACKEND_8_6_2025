<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php'; 
$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();

$input = (array)json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    if (getBearerToken()) {

   $token = json_decode(getBearerToken(),true);
        $user_id = $token['user_id'];
        $user = $entityManager->find(configuration\user::class,$user_id);
          $user_store_list = [];

          if($user->getUsertype()->getId()==14){
           $queryBuilder = $entityManager->createQueryBuilder();
           $queryBuilder->select('u', 'ut')
             ->from(configuration\user::class, 'u')
             ->leftJoin('u.type_id', 'ut')
             ->where('u.type_id = :typeId')
             ->setParameter('typeId', 2);
           $results = $queryBuilder->getQuery()->getResult();

           foreach($results as $result){
            $user_store_list[] = [
                  'value' => $result->getId(),
                  'label' => $result->getStore() ? $result->getStore()->getOutletname() : ($result->getFirstname() ?: ''),
                                ];
           }
          }

        http_response_code(200);
        echo json_encode($user_store_list);

    } else {
        http_response_code(401);
        echo json_encode(["Message" => "Authorization token not found."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["Message" => "Method Not Allowed"]);
}
