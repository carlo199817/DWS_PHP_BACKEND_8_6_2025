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


