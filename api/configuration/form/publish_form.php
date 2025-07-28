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
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

if ($_SERVER['REQUEST_METHOD'] === "POST") {


function isVersionValid($inputVersion, $formLinks) {
    foreach ($formLinks as $formLink) {
        $existingVersion = $formLink->getVersion();
        if (version_compare($inputVersion, $existingVersion, '<=')) {
            return false;
        }
    }
    return true;
}




        if(getBearerToken()){
            $token = json_decode(getBearerToken(),true);
            $repository = $entityManager->getRepository(process\user::class);
            $existingUser = $repository->findOneBy(['id' => $token['user_id']]);
            if($existingUser){

                $form = $entityManager->find(configuration_process\form::class,$input['form_id']);
                $form->setVersion($input['version']);
                if(count($form->getFormlink())){

                  if (isVersionValid($input['version'], $form->getFormlink())) {

                  $entityManager->flush();
                  $new_form = new configuration_process\automation_form_publishing;
                  $new_form->setVersion($input['version']);
                  $new_form->setForm($input['form_id']);
                  $new_form->setCreatedby($token['user_id']);
                  $new_form->setFormtype($input['type_id']);
                  $new_form->setRemark($input['remark']);

                  $timezone = new DateTimeZone('Asia/Manila');
                  $date = new DateTime('now', $timezone);
                  $new_form->setDatecreated($date);

                  $timezone = new DateTimeZone('Asia/Manila');
                  $date = new DateTime($input['publish_date'], $timezone);
                  $new_form->setDatepublish($date);
                  $entityManager->persist($new_form);
                  $entityManager->flush();

                  echo header("HTTP/1.1 200 OK");
                  echo json_encode(["Message"=>"Form created for publishing!"]);

                  } else {
                       echo header("HTTP/1.1 409 Conflict");
                       echo json_encode(["Message"=>"Version must be higher than existing one."]);
                  }

                }else{

                  $new_form = new configuration_process\automation_form_publishing;
                  $new_form->setVersion($input['version']);
                  $new_form->setForm($input['form_id']);
                  $new_form->setCreatedby($token['user_id']);
                  $new_form->setFormtype($input['type_id']);
                  $new_form->setRemark($input['remark']);

                  $timezone = new DateTimeZone('Asia/Manila');
                  $date = new DateTime('now', $timezone);
                  $new_form->setDatecreated($date);

                  $timezone = new DateTimeZone('Asia/Manila');
                  $date = new DateTime($input['publish_date'], $timezone);
                  $new_form->setDatepublish($date);

                  $entityManager->persist($new_form);
                  $entityManager->flush();

                  echo header("HTTP/1.1 200 OK");
                  echo json_encode(["Message"=>"Form created for publishing!"]);

                }

            }

        }

    }else{
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }


