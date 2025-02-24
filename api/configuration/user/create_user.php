<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php'; 


$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    
        if(getBearerToken()){
            $userRepository = $entityManager->getRepository(MainDb\Configuration\user::class);
            $existingUser = $userRepository->findOneBy(['username' => $input['username']]);
             if(!$existingUser){ 
                $new_user = new MainDb\Configuration\user;
                $new_user->setUsername($input['username']);
                $new_user->setPassword($input['password']);
                $new_user->setFirstname($input['firstname']);
                $new_user->setMiddlename($input['middlename']);
                $new_user->setLastname($input['lastname']);
                $new_user->setSuffix($input['suffix']);
                $new_user->setEmail($input['email']);
                $new_user->setEmployeenumber($input['employee_number']);
                $user_type = $entityManager->find(clientDB\Process\user_type::class,$input['user_type']);
                $new_user->setUsertype($user_type);
                $new_user->setActivate(true);
                $new_user->setPicture($input['picture'] ? $input['picture'] : "profile.png");
                $entityManager->persist($new_user);
                $entityManager->flush();

                header('HTTP/1.1 201 OK');
                echo json_encode(["Message"=>"Successfully Created!"]);
                
            }else{
                header('HTTP/1.1 409 Conflict'); 
                echo json_encode(["Message"=>"Username already exist"]);
            }
            
        }



}
?>

