<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PATCH");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php';

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

if ($_SERVER['REQUEST_METHOD'] === "PATCH") {

        if(getBearerToken()){

            $input_field = $entityManager->find(configuration_process\field::class,$input['field_id']);
            $formula = $input_field->getFormula();
            $new_formula = json_decode($formula, true);

            if(count($new_formula['choices'])!=1){

                $task = $entityManager->find(configuration_process\task::class,end($new_formula['choices'])['value']);
                foreach($task->getTaskfield() as $field){

                $qb = $entityManager->createQueryBuilder();
                $qb->select('f')
                 ->from(configuration_process\field::class, 'f')
                 ->where($qb->expr()->like('f.formula', ':search'))
                 ->setParameter('search', '%' . $field->getId() . '%');
                 $results = $qb->getQuery()->getResult();

                 $idToRemove = $field->getId();

                 foreach ($results as $extract_field) {
                    $formula = json_decode($extract_field->getFormula(), true);
                    $modified = false;

                      foreach ($formula['choices'] as &$choice) {
                        $ids = explode(',', $choice['value']);

                        $filtered_ids = array_filter($ids, function ($id) use ($idToRemove) {
                          return trim($id) != $idToRemove;
                         });

                        $newValue = implode(',', $filtered_ids);

                      if ($newValue !== $choice['value']) {
                        $choice['value'] = $newValue;
                        $modified = true;
                       }
                    }

                   if ($modified) {
                     $extract_field->setFormula(json_encode($formula, JSON_UNESCAPED_UNICODE));
                    }
                  }
                }

               array_pop($new_formula['choices']);
               $input_field->setFormula(json_encode($new_formula));
               $entityManager->flush();
             }

            echo header("HTTP/1.1 200 OK");
            echo json_encode(['Message' => "Changed Successfully"]);

        }

    }else{
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }

