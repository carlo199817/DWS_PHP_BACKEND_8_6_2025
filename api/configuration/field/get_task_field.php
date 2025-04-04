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
$origin = new configuration\origin;

if ($_SERVER['REQUEST_METHOD'] === "POST") {

     $field_list = [];
     $final_field_list = [];

        if(getBearerToken()){

        $task = $entityManager->find(configuration_process\task::class,$input['task_id']);
        $search_term = isset($input['search']) ? trim($input['search']) : '';

        if($input['search']!=''){

            $final_field_list = search_loop_question($field_list,$task,[],true,$search_term,$entityManager,$origin);

        }else{

             foreach($task->getTaskfield() as $field){
                    $type = $entityManager->find(configuration_process\field_type::class,$field->getFieldtype());
                    $path = $entityManager->find(configuration\path::class,$type->getPath());
                    array_push($final_field_list,['id'=>$field->getId(),'type'=>$type->getDescription(),
                    'picture'=>$origin->getOrigin($path->getDescription(),$type->getIcon()),'answer'=>$field->getAnswer(),
                    "series"=>$field->getSeries(),'formula'=>$field->getFormula(),'question'=>$field->getQuestion()
                ]);
             }
        }



                header('HTTP/1.1 200 OK');
                echo json_encode($final_field_list);

        }
    }else{
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(["Message" => "Method Not Allowed"]);
    }

function search_loop_question($field_list,$task_field,$field,$first_layer,$search_term,$entityManager,$origin){

$field_ids = [];
if($first_layer){
  foreach($task_field->getTaskfield() as $field){
   array_push($field_ids,$field->getId());
  }

                $queryBuilder = $entityManager->createQueryBuilder();
                $queryBuilder->select('f')
                    ->from(configuration_process\field::class, 'f')
                    ->join(configuration_process\field_type::class, 'ft', 'WITH', 'f.type_id = ft.id')
                    ->where($queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('LOWER(f.answer)', ':search'),
                        $queryBuilder->expr()->like('LOWER(f.formula)', ':search'),
                        $queryBuilder->expr()->like('LOWER(ft.description)', ':search')
                    ))
                    ->andWhere($queryBuilder->expr()->in('f.id', ':field_ids'))
                    ->setParameter('field_ids', $field_ids)
                    ->setParameter('search', '%' . strtolower($search_term) . '%');
                $fields = $queryBuilder->getQuery()->getResult();

                foreach ($fields as $field) {
                    $type = $entityManager->find(configuration_process\field_type::class,$field->getFieldtype());
                    $path = $entityManager->find(configuration\path::class,$type->getPath());
                    array_push($field_list,['id'=>$field->getId(),'type'=>$type->getDescription(),
                    'picture'=>$origin->getOrigin($path->getDescription(),$type->getIcon()),'answer'=>$field->getAnswer(),
                    "series"=>$field->getSeries(),'formula'=>$field->getFormula(),'question'=>$field->getQuestion()
                ]);

             }


  foreach($task_field->getTaskfield() as $field){
      return search_loop_question($field_list,[],$field,false,$search_term,$entityManager,$origin);
  }

}else{


  foreach($field->getFieldlink() as $field){
     array_push($field_ids,$field->getId());
  }

                $queryBuilder = $entityManager->createQueryBuilder();
                $queryBuilder->select('f')
                    ->from(configuration_process\field::class, 'f')
                    ->join(configuration_process\field_type::class, 'ft', 'WITH', 'f.type_id = ft.id')
                    ->where($queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('LOWER(f.answer)', ':search'),
                        $queryBuilder->expr()->like('LOWER(f.formula)', ':search'),
                        $queryBuilder->expr()->like('LOWER(ft.description)', ':search')
                    ))
                    ->andWhere($queryBuilder->expr()->in('f.id', ':field_ids'))
                    ->setParameter('field_ids', $field_ids)
                    ->setParameter('search', '%' . strtolower($search_term) . '%');
                $fields = $queryBuilder->getQuery()->getResult();

                foreach ($fields as $field) {
                    $type = $entityManager->find(configuration_process\field_type::class,$field->getFieldtype());
                    $path = $entityManager->find(configuration\path::class,$type->getPath());
                    array_push($field_list,['id'=>$field->getId(),'type'=>$type->getDescription(),
                    'picture'=>$origin->getOrigin($path->getDescription(),$type->getIcon()),'answer'=>$field->getAnswer(),
                    "series"=>$field->getSeries(),'formula'=>$field->getFormula(),'question'=>$field->getQuestion()
                ]);

             }

  foreach($field->getFieldlink() as $field_field){
      return search_loop_question($field_list,[],$field,false,$search_term,$entityManager,$origin);
  }

}

return $field_list;
}


