<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../database.php';
$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();

$input = (array)json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

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

        $token = json_decode(getBearerToken(),true);
        $database = json_decode(getBearerToken(),true)['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();
        $user = $entityManager->find(configuration\user::class, $input['user_id']);
        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "User not found"]);
            exit;
        }
        $visited = [];

        function buildUserTree($user, &$visited,$entityManager,$processDb) {
            $userId = $user->getId();
            if (in_array($userId, $visited)) {
                return null;
            }
            $visited[] = $userId;

            $children = [];
            foreach ($user->getUserlink() as $child) {
                $childData = buildUserTree($child, $visited,$entityManager,$processDb);
                if ($childData !== null) {
                    $children[] = $childData;
                 }
            }


            $schedules = $processDb->getRepository(process\schedule::class)->findBy(['user_id' => $user->getId()]);
            $latestDate = getReachedScheduleData($schedules);
            if($latestDate){
               foreach ($latestDate->getScheduleuserassign() as $user_assign) {
                 $user_store = $entityManager->find(configuration\user::class, $user_assign->getUser());
                 $children[]= [
                  'id' => $user_store->getId(),
                  'first_name' => $user_store->getStore()
                      ? $user_store->getStore()->getOutletname()
                      : ($user_store->getFirstname() ?: ''),
                  'last_name' => $user_store->getLastname(),
                  'user_type' => $user_store->getUsertype()
                      ? $user_store->getUsertype()->getDescription()
                      : null
                 ];
               }
            }
        if($user->getUsertype()->getId() !== 2){
            return [
                'id' => $user->getId(),
                'first_name' => $user->getStore()
                    ? $user->getStore()->getOutletname()
                    : ($user->getFirstname() ?: ''),
                'last_name' => $user->getLastname(),
                'user_type' => $user->getUsertype()
                    ? $user->getUsertype()->getDescription()
                    : null,
                'children' => $children
            ];
          }

        }

        $finalOutput = [];
        foreach ($user->getUserlink() as $linkedUser) {
            $tree = buildUserTree($linkedUser, $visited,$entityManager,$processDb);
            if ($tree !== null) {
                $finalOutput[] = $tree;
            }
        }

        http_response_code(200);
        echo json_encode([[
                         'id' => $user->getId(),
                         'first_name' => $user->getStore()
                         ? $user->getStore()->getOutletname()
                         : ($user->getFirstname() ?: ''),
                         'last_name' => $user->getLastname(),
                         'user_type' => $user->getUsertype()
                         ? $user->getUsertype()->getDescription()
                         : null,
                         'children'=>$finalOutput
                         ]]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
