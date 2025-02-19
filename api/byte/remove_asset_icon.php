<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, DELETE");
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../../database.php'; 

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$parent_directory = dirname(__DIR__);
$main_pickle_directory = dirname($parent_directory);
$input = (array) json_decode(file_get_contents('php://input'), TRUE);
 
if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    if (getBearerToken()) {
        $response = array();
        $fileName = isset($_GET['file']) ? $_GET['file'] : '';

        if (empty($fileName)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(["Message" => "No file specified to delete."]);
            exit;
        }

        $filePath = $main_pickle_directory . "/file/asset/" . $fileName;

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                header('HTTP/1.1 200 OK');
                echo json_encode(["Message" => "File " . $fileName . " has been deleted successfully."]);
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(["Message" => "Error deleting the file."]);
            }
        } else {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(["Message" => "File not found."]);
        }

    } else {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(["Message" => "Unauthorized access. Invalid or missing token."]);
    }
} 
else {

    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
