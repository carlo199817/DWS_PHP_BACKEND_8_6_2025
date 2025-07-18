<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
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
        $asset_repository = $entityManager->getRepository(configuration_process\asset::class)->findAll();
        $asset_list = [];
        foreach ($asset_repository as $asset) {
            $equipment_list = [];
            foreach ($asset->getAssetequipment() as $equipment) {
                $part_list = [];
                foreach ($equipment->getEquipmentpart() as $part) {
                    array_push($part_list, [
                        'id' => $part->getId(),
                        'description' => $part->getDescription(),
                        'answer' => $part->getAnswer(),
                        'question' => $part->getQuestion(),
			'remove'=>$part->getRemove()
                    ]);
                }
                array_push($equipment_list, ['id' => $equipment->getId(), 'description' => $equipment->getDescription(), 'tag' => $equipment->getTag(), 'remove'=>$equipment->getRemove(),'part' => $part_list]);
            }
            array_push($asset_list, [
                'id' => $asset->getId(),
                'description' => $asset->getDescription(),
                 'remove'=>$asset->getRemove(),
                'equipment' => $equipment_list
            ]);
        }
        echo header("HTTP/1.1 200 OK");
        echo json_encode($asset_list);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
