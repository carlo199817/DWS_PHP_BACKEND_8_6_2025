<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PATCH");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php';

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "PATCH") {

    if (getBearerToken()) {


       function getReachedScheduleData(array $schedules)
        {
            if (empty($schedules)) {
             return null;
            }

          $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
          $now->modify('+1 minute');

          $latestReached = null;

         foreach ($schedules as $schedule) {
          $date = $schedule->getDateeffective();
          $date->setTimezone(new DateTimeZone('Asia/Manila'));

          if ($date <= $now && ($latestReached === null || $date > $latestReached->getDateeffective())) {
            $latestReached = $schedule;
          }
        }

         return $latestReached ?? null;
       }


     function implementation($entityManager,$processDb,$user,$form){

            foreach($user->getUserlink() as $link_user){

                if($link_user->getUsertype()->getId()!=2){
                  $existing_user = $processDb->find(process\user::class,$link_user->getId());
                  if($existing_user){
                   $existing_user->setUserformtask($form);
                   $processDb->flush();

                  $schedules = $processDb->getRepository(process\schedule::class)->findBy(['user_id' => $existing_user->getId()]);
                  $latestDate = getReachedScheduleData($schedules);
                  if($latestDate){
                    foreach ($latestDate->getScheduleuserassign() as $user_assign) {
                       $existing_user = $processDb->find(process\user::class,$user_assign->getUser());
                        if($existing_user){
                         $existing_user->setUserformtask($form);
                         $processDb->flush();
                        }else{
                         $user = new process\user;
                         $user->setId($user_assign->getUser());
                         $processDb->persist($user);
                         $user->setUserformtask($form);
                         $processDb->flush();

                       }
                     }
                   }

                  }else{
                   $user = new process\user;
                   $user->setId($link_user->getId());
                   $processDb->persist($user);
                   $processDb->flush();
                   $user->setUserformtask($form);
                   $processDb->flush();

                  $schedules = $processDb->getRepository(process\schedule::class)->findBy(['user_id' => $user->getId()]);
                  $latestDate = getReachedScheduleData($schedules);
                  if($latestDate){
                    foreach ($latestDate->getScheduleuserassign() as $user_assign) {
                       $existing_user = $processDb->find(process\user::class,$user_assign->getUser());
                        if($existing_user){
                         $existing_user->setUserformtask($form);
                         $processDb->flush();
                        }else{
                         $user = new process\user;
                         $user->setId($user_assign->getUser());
                         $processDb->persist($user);
                         $user->setUserformtask($form);
                         $processDb->flush();

                       }
                     }
                   }

                  }
                  implementation($entityManager,$processDb,$link_user,$form);
                }
              }
            }

             $token = json_decode(getBearerToken(), true);
             $database = $token['database'];
             $dbConnection = new DatabaseConnection($database);
             $processDb = $dbConnection->getEntityManager();

             $form = $processDb->find(configuration_process\form::class,$input['form_id']);

             if(!$form->getDistributed()){

             $start_user = $entityManager->find(configuration\user::class,$input['user_id']);
             $existing_user = $processDb->find(process\user::class,$start_user->getId());

              if($existing_user){
                 $existing_user->setUserformtask($form);
                 $schedules = $processDb->getRepository(process\schedule::class)->findBy(['user_id' => $existing_user->getId()]);
                 $latestDate = getReachedScheduleData($schedules);
                 if($latestDate){
                   foreach ($latestDate->getScheduleuserassign() as $user_assign) {
                       $existing_user = $processDb->find(process\user::class,$user_assign->getUser());
                        if($existing_user){
                         $existing_user->setUserformtask($form);
                         $processDb->flush();
                        }else{
                         $user = new process\user;
                         $user->setId($user_assign->getUser());
                         $processDb->persist($user);
                         $processDb->flush();
                         $user->setUserformtask($form);
                        }
                  }
               }
               implementation($entityManager,$processDb,$start_user,$form);
              }else{
                  $user = new process\user;
                  $user->setId($start_user->getId());
                  $processDb->persist($user);
                  $processDb->flush();
                  $user->setUserformtask($form);
                  $processDb->flush();

                  $schedules = $processDb->getRepository(process\schedule::class)->findBy(['user_id' => $user->getId()]);
                  $latestDate = getReachedScheduleData($schedules);
                  if($latestDate){
                    foreach ($latestDate->getScheduleuserassign() as $user_assign) {
                       $existing_user = $processDb->find(process\user::class,$user_assign->getUser());
                        if($existing_user){
                         $existing_user->setUserformtask($form);
                         $processDb->flush();
                        }else{
                         $user = new process\user;
                         $user->setId($user_assign->getUser());
                         $processDb->persist($user);
                         $user->setUserformtask($form);
                         $processDb->flush();

                       }
                     }
                   }

                 implementation($entityManager,$processDb,$start_user,$form);
              }

        $form->setDistributed(true);
        $processDb->flush();
        header('HTTP/1.1 200 OK');
        echo json_encode(["Message" => "Project implementation completed !"]);
      }else{
        header('HTTP/1.1 409 Conflict');
        echo json_encode(["Message" => "Project has already been implemented !"]);
      }
    }

} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
