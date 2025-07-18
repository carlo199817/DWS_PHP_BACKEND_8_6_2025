<?php
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

$input = (array)json_decode(file_get_contents('php://input'), true);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (getBearerToken()) {
 
        $searchTerm = isset($input['search']) ? trim($input['search']) : '';
        try {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('t')  
                ->from(configuration\tag::class, 't')  
                ->where($queryBuilder->expr()->orX(
     		    $queryBuilder->expr()->like('LOWER(t.description)', ':search'),
                ))
                ->setParameter('search', '%' . strtolower($searchTerm) . '%');
            $tags = $queryBuilder->getQuery()->getResult(); 
            $tag_list = [];
            foreach ($tags as $tag) {

                $tag_list[] = [
                    'value' => $tag->getId(),
                    'label' => $tag->getDescription(), 

                ];
            }
            header('HTTP/1.1 200 OK');
            echo json_encode($tag_list);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}



?>
