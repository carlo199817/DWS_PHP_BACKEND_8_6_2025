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


$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);


while(true)
{
    $flag = "on";
    if($flag === "on") {

        $flag = "off";

        $automation_form_publishing_repository = $entityManager->getRepository(configuration_process\automation_form_publishing::class);
        $queryBuilder = $automation_form_publishing_repository->createQueryBuilder('p');
        $queryBuilder->Where('p.process IS NULL');
        $results = $queryBuilder->getQuery()->getResult();

          if(count($results)){
                $form = $entityManager->find(configuration_process\form::class, $results[0]->getForm());
                $create_form = new form_loop();
                $new_form_id = $create_form->setFormloop($entityManager,$entityManager,$results[0]->getForm(),true);
                $new_form = $entityManager->find(configuration_process\form::class,$new_form_id);
                $new_form->setVersion($results[0]->getVersion());
                $new_form->setParentform($form->getParentform());
                $new_form->setCreatedby($results[0]->getCreatedby());
                $new_form->setFormtype($results[0]->getFormtype());
                $new_form->setRemark($results[0]->getRemark());
                $new_form->setTitle($form->getTitle());
                $new_form->setDateeffective($results[0]->getDatepublish());
                $new_form->setDatecreated($results[0]->getDatecreated());
                $entityManager->persist($new_form);
                $entityManager->flush();
                $form->setFormlink($new_form);
                $entityManager->flush();
                $results[0]->setProcess(true);
                $entityManager->flush();
                $flag = "on";
                echo "Form Publish!\n";
                exit;
          }

    }
    sleep(2);
}

?>
