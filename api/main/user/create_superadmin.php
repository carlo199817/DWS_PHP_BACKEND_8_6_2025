<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php'; 

$databaseName = "main_db";
$databaseName2 = "dws_db_2025";
$dbConnection = new DatabaseConnection($databaseName);
$dbConnection2 = new DatabaseConnection($databaseName2);
$entityManager = $dbConnection->getEntityManager();
$entityManager2 = $dbConnection2->getEntityManager();


$input = (array) json_decode(file_get_contents('php://input'), TRUE);
$allow = true;


if($_SERVER['REQUEST_METHOD']==="POST"){
    if($allow===true){

        $user_repository = $entityManager->getRepository(MainDb\Configuration\user::class);
        $user_repository2 = $entityManager2->getRepository(ClientDb\Process\user::class);
        $existing_user = $user_repository->findOneBy(['email' => $input['email']]);
        if (!$existing_user) {
            $new_super_admin = new MainDb\Configuration\user;  
            $new_super_admin->setEmail($input["email"]);
            $new_super_admin->setUsername($input["username"]);
            $new_super_admin->setPassword($input["password"]); 
            $user = new ClientDb\Process\user;
       
           
           $entityManager->persist($new_super_admin); 
           $entityManager->flush();  
    
    
           echo json_encode($new_super_admin->getId());
    
           $user->setId($new_super_admin->getId()); 
           $entityManager2->persist($user); 
           $entityManager2->flush();   
           header('HTTP/1.1 201 Created'); 
           echo json_encode(["Message"=>"New superadmin ".$input['email']." created!"]);
    
            
        } else {

            
            header('HTTP/1.1 409 Conflict'); 
            echo json_encode(["Message"=>"Username already exists"]);
           
        }
    }

    

}
else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}






 