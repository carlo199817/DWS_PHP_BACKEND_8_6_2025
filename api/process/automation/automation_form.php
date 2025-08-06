<?php

set_time_limit(0);
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

$parent_directory = dirname(dirname(dirname(__DIR__)));
require $parent_directory . '/vendor/autoload.php';
require $parent_directory . '/database.php';

$timezone = new DateTimeZone('Asia/Manila');
$currentDateTime = new DateTime('now', $timezone);

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();

$databaseName = "dws_db_" . $currentDateTime->format('Y');
$dbConnection = new DatabaseConnection($databaseName);
$processDb = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
$flag = "on";

while(true)
{

    if($flag === "on") {

        $flag = "off";

        $automation_form_repository = $processDb->getRepository(configuration_process\automation_form::class);
        $queryBuilder = $automation_form_repository->createQueryBuilder('p');
        $queryBuilder->Where('p.process IS NULL');
        $results = $queryBuilder->getQuery()->getResult();

        if(count($results)){
          $internal_flag = "on";
             if($internal_flag === "on"){
                 $internal_flag = "off";

                   $create_form = new form_loop();
                   $new_form_id = $create_form->setFormloop($entityManager,$processDb,$results[0]->getForm(),false);
                   $new_form = $processDb->find(configuration_process\form::class,$new_form_id);
                   $new_form->setCreatedby($results[0]->getCreatedby());
                   $processDb->flush();

                   $user = $processDb->find(process\user::class,$results[0]->getCreatedby());

                  if($results[0]->getItinerary()){
                    $itinerary = $processDb->find(configuration_process\itinerary::class,$results[0]->getItinerary());
                    $itinerary->setItineraryform($new_form);
                  }else{
                     if($user){
                           $user->setUserformconnection($new_form);
                           $processDb->flush();
                     }else{
                           $user = new process\user();
                           $user->setId($results[0]->getCreatedby());
                           $user->setUserformconnection($new_form);
                           $processDb->persist($user);
                           $processDb->flush();
                     }
                   }
                 $automation_form = $processDb->find(configuration_process\automation_form::class,$results[0]->getId());
                 $automation_form->setProcess(true);
                 $processDb->flush();
                 $internal_flag = "on";
                 $flag = "on";
                 echo "Form created!";
             }
        }else{
          $flag = "on";
        }
    }
    sleep(2);
}

?>
