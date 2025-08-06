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
        $user = $processDb->find(process\user::class, $token['user_id']);
        $form_list = [];
        $date_input = $input['date'];

        if($date_input === null) {
            $date_input = new DateTime('now', new DateTimeZone('Asia/Manila'));
        } else {
            $date_input = new DateTime($date_input, new DateTimeZone('Asia/Manila'));
        }

        foreach ($user?->getUserformconnection() ??[] as $form) {

            if($date_input->format('Y-m-d') === $form->getDatecreated()->format('Y-m-d')) {
             $store = null;
              if($form->getStore()){
                 $user_store = $entityManager->find(configuration\user::class,$form->getStore());
                 $store = ['value' =>$user_store->getId(), 'label'=>$user_store->getStore()->getOutletname()];
              }
              $form_list[] = [
                "id" => $form->getId(),
                'title' => $form->getTitle(),
                'distributed' => $form->getDistributed(),
                'form_type_id'=>$form->getFormtype(),
                'store'=>$store,
                'type'=>"form",
                'owned'=>$token['user_id']===$form->getCreatedby()?true:false,
                "description" => $token['user_id']===$form->getCreatedby()?"You generated this task":"Task assigned to you",
             ];

            }
        }

        foreach ($user?->getUserformtask() ??[] as $form) {

            if($date_input->format('Y-m-d') === $form->getDatecreated()->format('Y-m-d')) {
             $store = null;
              if($form->getStore()){
                 $user_store = $entityManager->find(configuration\user::class,$form->getStore());
                 $store = ['value' =>$user_store->getId(), 'label'=>$user_store->getStore()->getOutletname()];
              }
              $form_list[] = [
                "id" => $form->getId(),
                'title' => $form->getTitle(),
                'distributed' => $form->getDistributed(),
                'form_type_id'=>$form->getFormtype(),
                'store'=>$store,
                'type'=>"task",
                'owned'=>$token['user_id']===$form->getCreatedby()?true:false,
                "description" => "Special task assigned to you",
             ];

            }
        }

        foreach ($user?->getUseritineraryconnection() ??[] as $itinerary) {

            if($date_input->format('Y-m-d') === $itinerary->getDatecreated()->format('Y-m-d')) {
              $itinerary_type = $entityManager->find(configuration_process\itinerary_type::class,$itinerary->getType());
              $form_list[] = [
                "id" => $itinerary->getId(),
                "title"=>$itinerary_type->getDescription(),
                "description" => "Your signature is required for validation",
                'type'=>"itinerary",
                'owned'=>$token['user_id']===$itinerary->getCreatedby()?true:false,
                'distributed' => null,
                'form_type_id'=>null,
                'store'=>null
             ];

            }
        }

        echo header("HTTP/1.1 200 OK");
        echo json_encode($form_list);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
