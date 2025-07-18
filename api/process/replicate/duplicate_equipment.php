<?php
// Enable error reporting for debugging

use configuration_process\equipment;

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
    if (getBearerToken()) {
        $token = json_decode(getBearerToken(), true);
        $database = json_decode(getBearerToken(), true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();
        $equipment = $processDb->find(configuration_process\equipment::class, $input['equipment_id']);
        $asset = $equipment->getBidirectional()->first();
        $new_equipment = new configuration_process\equipment();
        $new_equipment->setDescription($equipment->getDescription());
        $new_equipment->setTag($equipment->getTag());
        $new_equipment->setSeries($equipment->getSeries()-1);
        $processDb->persist($new_equipment);
        foreach ($equipment->getEquipmentpart() as $part) {
            $new_part = new configuration_process\part();
            $new_part->setDescription($part->getDescription());
            $new_part->setAnswer($part->getAnswer());
            $new_part->setQuestion($part->getQuestion());
            $processDb->persist($new_part);
            $new_equipment->setEquipmentpart($new_part);
        }
        $asset->setAssetequipment($new_equipment);

        $equipment_list = [];
        foreach ($asset->getAssetequipment() as $equipment_series) {
            $equipment_list[] = [
                'series' => $equipment_series->getSeries()?$equipment_series->getSeries():0,
                'id' => $equipment_series->getId()
            ];
        }

        function sortById($a, $b)
        {
            return  $a['series'] - $b['series'];
        }
        usort($equipment_list, 'sortById');
        foreach ($equipment_list as $index => $series) {
            if ($series['id'] > 0) {
                $change_series = $processDb->find(configuration_process\equipment::class, $series['id']);
                if ($change_series) {
                    $change_series->setSeries($index);
                }
            }
        }


        $processDb->flush();
        echo header("HTTP/1.1 200 OK");
        echo json_encode(['Message' => "Equipment Successfully Duplicated"]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
