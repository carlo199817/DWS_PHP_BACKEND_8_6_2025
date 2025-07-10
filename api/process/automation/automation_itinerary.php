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

        $automation_itinerary_repository = $processDb->getRepository(configuration_process\automation_itinerary::class);
        $queryBuilder = $automation_itinerary_repository->createQueryBuilder('p');
        $queryBuilder->Where('p.process IS NULL');
        $results = $queryBuilder->getQuery()->getResult();

        if(count($results)){
          $internal_flag = "on";
             if($internal_flag === "on"){
                 $internal_flag = "off";

                 $new_itinerary = new configuration_process\itinerary();
                 $new_itinerary->setPath(3);
                 $new_itinerary->setType($results[0]->getItinerarytype());
                 $new_itinerary->setSchedule($results[0]->getSchedule());
                 $new_itinerary->setDatecreated($results[0]->getDatecreated());
                 foreach(json_decode($results[0]->getForm()) as $form_id){
                   $create_form = new form_loop();
                   $new_form_id = $create_form->setFormloop($entityManager,$processDb,$form_id,false);
                   $new_form = $processDb->find(configuration_process\form::class,$new_form_id);
                   $new_itinerary->setItineraryform($new_form);
                 }
                 if($results[0]->getAssignedto()){
                  $new_itinerary->setAssignedto($results[0]->getAssignedto());
                 }
                 if($results[0]->getApprovedby()){
                  $new_itinerary->setApprovedby($results[0]->getApprovedby());
                 }
                 $new_itinerary->setCreatedby($results[0]->getCreatedby());
                 $new_itinerary->setStore($results[0]->getStore());
                 $processDb->persist($new_itinerary);
                 $processDb->flush();
                 if($results[0]->getPreventive()){
                     $preventive = $processDb->find(configuration_process\preventive::class,$results[0]->getPreventive());
                      if(!$preventive->getItinerary()){
                          $preventive->setItinerary($new_itinerary->getId());
                          $processDb->flush();
                     }
                 }

                 $automation_itinerary = $processDb->find(configuration_process\automation_itinerary::class,$results[0]->getId());
                 $automation_itinerary->setProcess(true);
                 $processDb->flush();
                 $internal_flag = "on";
                 $flag = "on";
                 echo "Itinerary created!";
             }
        }else{
          $flag = "on";
        }
    }
    sleep(2);
}

?>
