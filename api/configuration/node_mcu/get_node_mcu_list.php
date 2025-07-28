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
        if (empty($searchTerm)) {
           $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('s')->from(configuration\node_mcu::class, 's');
            $node_mcus = $queryBuilder->getQuery()->getResult();
            $node_mcu_list = [];
            foreach ($node_mcus as $node_mcu) {
            $store = null;
              if($node_mcu->getStore()){
               $store = $entityManager->find(configuration\store::class,$node_mcu->getStore());
              }
                $node_mcu_list[] = [
                    'id' => $node_mcu->getId(),
                    'description' => $node_mcu->getDescription(),
                    'store'=>$store?$store->getOutletname():$store
                ];
            }
            header('HTTP/1.1 200 OK');
            echo json_encode($node_mcu_list);
            return;
        }
        try {
            $queryBuilder = $entityManager->createQueryBuilder();
            $queryBuilder->select('n')
                ->from(configuration\node_mcu::class, 'n')
		->leftJoin(configuration\store::class,'s','WITH','n.store_id = s.id')
                ->where($queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('LOWER(n.description)', ':search'),
                    $queryBuilder->expr()->like('LOWER(n.store_id)', ':search'),
	             $queryBuilder->expr()->like('LOWER(s.outlet_name)', ':search'),
                ))
                ->setParameter('search', '%' . strtolower($searchTerm) . '%');
            $node_mcus = $queryBuilder->getQuery()->getResult();
            $node_mcu_list = [];
            foreach ($node_mcus as $node_mcu) {
            $store = null;
            if($node_mcu->getStore()){
              $store = $entityManager->find(configuration\store::class,$node_mcu->getStore());
              }

                $node_mcu_list[] = [
                    'id' => $node_mcu->getId(),
                   'description' => $node_mcu->getDescription(),
                   'store'=>$store?$store->getOutletname():$store
                ];
            }

            header('HTTP/1.1 200 OK');
            echo json_encode($node_mcu_list);
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

