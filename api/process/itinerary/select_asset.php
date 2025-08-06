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
    if (getBearerToken()) {
        $token = json_decode(getBearerToken(), true);
        $database = json_decode(getBearerToken(), true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();

        $user = $entityManager->find(configuration\user::class,$token['user_id']);
        $itinerary = $processDb->find(configuration_process\itinerary::class, $input['itinerary_id']);
        $disable = true;

if($user->getUsertype()->getId()==9){
        foreach ($itinerary->getItineraryvalidation() as $validator) {
            if ($user->getUsertype()->getId() == $validator->getUsertype()) {
              if(!$validator->getValid()){
                  $disable = false;
  break;
                }
            }
        }
}

        foreach ($itinerary->getItineraryasset() as $asset) {
            $selected_asset = $processDb->find(configuration_process\asset::class, $input['asset_id']);
            if($asset->getId() === $selected_asset->getId()) {
                $asset->setSelected(true);
            } else {
                $asset->setSelected(null);
            }

                 $processDb->flush();
        }

        $selected_asset = $processDb->find(configuration_process\asset::class, $input['asset_id']);
        $equipment_list = [];
         foreach($selected_asset->getAssetequipment() as $equipment){
              $part_list = [];
                foreach($equipment->getEquipmentpart() as $part){
                 array_push($part_list,[
                     'id'=>$part->getId(),
                     'description'=>$part->getDescription(),
                     'question'=>$part->getQuestion(),
                     'answer'=>$part->getAnswer(),
                     'disable'=>$disable
                     ]);
                }
             $tag = null;
             if($equipment->getTag()){
                $tag = $entityManager->find(configuration\tag::class,$equipment->getTag());
             }
                 array_push($equipment_list,[
                     'id'=>$equipment->getId(),
                     'description'=>$equipment->getDescription(),
                     'tag'=>$tag?$tag->getDescription():$tag,
                     'tag_id'=>$equipment->getTag(),
                     'brand'=>$tag?$tag->getBrand():$tag,
                     'model'=>$tag?$tag->getModel():$tag,
                     'serial'=>$tag?$tag->getSerial():$tag,
		     'series'=>$equipment->getSeries(),
                     'parts'=>$part_list,
                     'disable'=>$disable
                     ]);
         }

	function sortById($a, $b) {
                return $a['series'] - $b['series'];
            }
         usort($equipment_list, 'sortById');
	echo header("HTTP/1.1 200 OK");
        echo json_encode($equipment_list);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
