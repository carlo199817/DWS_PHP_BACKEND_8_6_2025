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
$input = (array) json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (getBearerToken()) {

     function implementation($entityManager,$processDb,$user,$form){

            foreach($user->getUserlink() as $link_user){
              echo json_encode($link_user->getUsertype()->getDescription());
          /* $existing_user = $processDb->find(process\user::class,$user->getId());
           if($existing_user){
                $existing_user->setUserformtask($form);
                $processDb->flush();
           }else{
                $user = new process\user;
                $user->setId($user->getId());
                $processDb->persist($user);
                $processDb->flush();
                $user->setUserformtask($form);
                $processDb->flush();
              }*/
               }
            }

             $token = json_decode(getBearerToken(), true);
             $database = $token['database'];
             $dbConnection = new DatabaseConnection($database);
             $processDb = $dbConnection->getEntityManager();

             $form = $processDb->find(configuration_process\form::class,$input['form_id']);
             $start_user = $entityManager->find(configuration\user::class,$input['user_id']);

             $existing_user = $processDb->find(process\user::class,$start_user->getId());

              if($existing_user){
                 $existing_user->setUserformtask($form);
               //  $processDb->flush();
                 implementation($entityManager,$processDb,$start_user,$form);
              }else{
                 $user = new process\user;
                 $user->setId($user->getId());
                 $processDb->persist($user);
                 $processDb->flush();
                 $user->setUserformtask($form);
              //   $processDb->flush();
                 implementation($entityManager,$processDb,$start_user,$form);
              }


             /* if($form_id){
                $new_form = new configuration_process\automation_form();
                $new_form->setForm(json_encode($form_id));
                $new_form->setCreatedby($token['user_id']);
                $processDb->persist($new_form);
                $processDb->flush();
              }*/

      header('HTTP/1.1 200 OK');
      echo json_encode(["Message" => "Project implementation completed !"]);
    }

} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
