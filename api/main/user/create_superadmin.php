<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php'; 
require_once __DIR__ . '/../../../src/configuration/user.php';

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
$allow = false;

if($allow===true){
 
    $user_repository = $entityManager->getRepository(MainDb\Configuration\user::class);
    $existing_user = $user_repository->findOneBy(['email' => $input['email']]);
 
    if ($existing_user) {

        header('HTTP/1.1 409 Conflict'); 
        echo json_encode(["Message"=>"Username already exists"]);

    } else {
 
        $new_super_admin = new MainDb\Configuration\user;  
        $new_super_admin->setEmail($input['email']);
        $new_super_admin->setPassword($input['password']);
        $new_super_admin->setFirstname($input['firstname']); 
        $new_super_admin->setLastname($input['lastname']); 
        $new_super_admin->setMobile($input['mobile']);
        $new_super_admin->setMember($input['member']);
        $new_super_admin->setWallet($input['wallet']); 
        $mirror_position = $entityManager->find(MainDb\Configuration\mirror_position::class,1);
        $new_super_admin->setPosition($mirror_position);
        
        $entityManager->persist($new_super_admin); 
        $entityManager->flush();   
        
        header('HTTP/1.1 201 Created'); 
        echo json_encode(["Message"=>"New superadmin ".$input['email']." created!"]);

    }

}else{ 
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}


 