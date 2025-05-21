<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
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
        $sql = "
        SELECT 
        c.id AS id, 
        c.description AS description, 
        ct.description AS category_type
        FROM 
            category c
        LEFT JOIN 
            category_type ct ON c.type_id = ct.id
        WHERE 
            c.description LIKE CONCAT('%', :search, '%')
            OR ct.description LIKE CONCAT('%', :search, '%');
        ";
            $query = $entityManager->getConnection()->prepare($sql);
            $query->bindValue(':search', '%' . strtolower($searchTerm) . '%'); 
            $categories = $query->executeQuery()->fetchAllAssociative();
            $category_list = [];
            foreach ($categories as $category) {
                $category_list[] = [
                    'id' => $category['id'],
                    'description' => $category['description'],
                    'type' => $category['category_type'],
                ];
            }
            header('HTTP/1.1 200 OK');
            echo json_encode($category_list);

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
