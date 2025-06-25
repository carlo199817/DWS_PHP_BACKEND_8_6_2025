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
        $automation_form = $entityManager->getRepository(configuration_process\automation_form_publishing::class);
        $firstRecord = $automation_form->findOneBy([], ['id' => 'ASC']);
        if ($firstRecord) {

                $form = $entityManager->find(configuration_process\form::class, $firstRecord->getForm());
                $create_form = new form_loop();
                $new_form_id = $create_form->setFormloop($entityManager,$entityManager,$firstRecord->getForm(),true);
                $new_form = $entityManager->find(configuration_process\form::class,$new_form_id);

                $new_form->setVersion($firstRecord->getVersion());
                $new_form->setParentform($firstRecord->getForm());
                $new_form->setCreatedby($firstRecord->getCreatedby());
                $new_form->setFormtype($firstRecord->getFormtype());
                $new_form->setRemark($firstRecord->getRemark());
                $new_form->setTitle($form->getTitle());
                $new_form->setDateeffective($firstRecord->getDatepublish());
                $new_form->setDatecreated($firstRecord->getDatecreated());
                $entityManager->persist($new_form);
                $entityManager->flush();
                $form->setFormlink($new_form);
                $entityManager->flush();
                $entityManager->remove($firstRecord);
                $entityManager->flush();
        }

        $flag = "on";
        echo "Form Publish!\n";
    }
    sleep(2);
}

?>
