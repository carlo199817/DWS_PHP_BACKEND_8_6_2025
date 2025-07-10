<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php';  
$databaseName = "main_db"; 
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager(); 

$input = (array) json_decode(file_get_contents('php://input'), TRUE);

if ($_SERVER['REQUEST_METHOD'] === "GET") {

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




        try {
            $token = json_decode(getBearerToken(), true);
            $database = $token['database'];
            $dbConnection = new DatabaseConnection($database);
            $processDb = $dbConnection->getEntityManager();

            $schedules = $processDb->getRepository(process\schedule::class)->findBy([
                'user_id' => $token['user_id']
            ]);

            $latestDate = getReachedScheduleData($schedules);
            if (empty($latestDate)) {
                http_response_code(200);
                echo json_encode([]);
                exit;
            }

            $assign_list = [];
            foreach ($latestDate->getScheduleuserassign() as $user_assign) {
                $user = $entityManager->find(configuration\user::class, $user_assign->getUser());
                if (!$user) continue;

                $assign_list[] = [
                    'value' => $user->getId(),
                    'label' => $user->getStore()
                        ? $user->getStore()->getOutletname()
                        : ($user->getFirstname() ?: '')
                ];
            }


            http_response_code(200);
            echo json_encode($assign_list);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["Message" => $e->getMessage()]);
        }

    } else {
        http_response_code(401);
        echo json_encode(["Message" => "Unauthorized"]);
    }

} else {
    http_response_code(405);
    echo json_encode(["Message" => "Method Not Allowed"]);
}
