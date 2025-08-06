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
$input = (array) json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (getBearerToken()) {

        $token = json_decode(getBearerToken(), true);
        $database = $token['database'];
        $dbConnection = new DatabaseConnection($database);
        $processDb = $dbConnection->getEntityManager();
        $timezone = new DateTimeZone('Asia/Manila');
        $start_date = isset($input['start_date']) ? new DateTime($input['start_date'], $timezone) : null;
        $end_date   = isset($input['end_date']) ? new DateTime($input['end_date'], $timezone) : null;
        $user = $entityManager->find(configuration\user::class, $input['user_id']);
        $report_list = [];

        function getAllLinkedUserIds($user, &$collected = [])
        {
            if (
                $user->getUserType() &&
                (int) $user->getUserType()->getId() === 2
            ) {
                return;
            }

            $userId = $user->getId();
            if (in_array($userId, $collected)) {
                return;
            }
            $collected[] = $userId;
            foreach ($user->getUserlink() as $linkedUser) {
                getAllLinkedUserIds($linkedUser, $collected);
            }
        }

        $validUserIds = [];
        getAllLinkedUserIds($user, $validUserIds);
        $itineraryRepository = $processDb->getRepository(configuration_process\itinerary::class);
        $report_list = [];
        foreach ($validUserIds as $userId) {
            $linkedUser = $entityManager->find(configuration\user::class, $userId);
            if (!$linkedUser) continue;

            $userItineraries = $itineraryRepository->findBy(['assigned_to' => $userId]);

            $daily_hours = [];

               foreach ($userItineraries as $itinerary) {
                $schedule = $itinerary->getSchedule();
                $schedule->setTimezone($timezone);

                $scheduleDate = $schedule->format('Y-m-d');
                $startDateStr = $start_date ? $start_date->format('Y-m-d') : null;
                $endDateStr   = $end_date ? $end_date->format('Y-m-d') : null;

                if ($startDateStr && $scheduleDate < $startDateStr) continue;
                if ($endDateStr && $scheduleDate > $endDateStr) continue;

                $checkinTime = $itinerary->getCheckintime();
                $checkoutTime = $itinerary->getCheckouttime();

                if ($checkinTime) {
                    $checkinTime->setTimezone(new DateTimeZone('Asia/Manila'));
                    if ($checkinTime < $schedule) {
                        if ($checkoutTime) {

                            $interval = $checkinTime->diff($checkoutTime);

                            if (!isset($daily_hours[$scheduleDate])) {
                                $daily_hours[$scheduleDate] = ['h' => 0, 'm' => 0];
                            }
                            $daily_hours[$scheduleDate]['h'] += $interval->h;
                            $daily_hours[$scheduleDate]['m'] += $interval->i;

                            if ($daily_hours[$scheduleDate]['m'] >= 60) {
                                $extra = floor($daily_hours[$scheduleDate]['m'] / 60);
                                $daily_hours[$scheduleDate]['h'] += $extra;
                                $daily_hours[$scheduleDate]['m'] %= 60;
                            }
                        }
                    }
                }

                if ($checkinTime && $checkoutTime) {

                    if (
                        $checkinTime->format('Y-m-d') === $schedule->format('Y-m-d') &&
                        $checkoutTime->format('Y-m-d') === $schedule->format('Y-m-d')
                    ) {
                        $interval = $checkinTime->diff($checkoutTime);

                        if (!isset($daily_hours[$scheduleDate])) {
                            $daily_hours[$scheduleDate] = ['h' => 0, 'm' => 0, 's' => 0];
                        }
                        $daily_hours[$scheduleDate]['h'] += $interval->h;
                        $daily_hours[$scheduleDate]['m'] += $interval->i;
                        $daily_hours[$scheduleDate]['s'] += $interval->s;

                        if ($daily_hours[$scheduleDate]['s'] >= 60) {
                            $extraMin = floor($daily_hours[$scheduleDate]['s'] / 60);
                            $daily_hours[$scheduleDate]['m'] += $extraMin;
                            $daily_hours[$scheduleDate]['s'] %= 60;
                        }

                        if ($daily_hours[$scheduleDate]['m'] >= 60) {
                            $extraHr = floor($daily_hours[$scheduleDate]['m'] / 60);
                            $daily_hours[$scheduleDate]['h'] += $extraHr;
                            $daily_hours[$scheduleDate]['m'] %= 60;
                        }
                    }
                }
            }

            $formatted_daily_hours = []; 

                foreach ($daily_hours as $date => $hm) {
                $hours   = isset($hm['h']) ? (int)$hm['h'] : 0;
                $minutes = isset($hm['m']) ? (int)$hm['m'] : 0;
                $seconds = isset($hm['s']) ? (int)$hm['s'] : 0;

                $parts = [];
                if ($hours > 0)   $parts[] = $hours . 'h';
                if ($minutes > 0) $parts[] = $minutes . 'm';
                if ($seconds > 0) $parts[] = $seconds . 's';

                if (empty($parts)) $parts[] = '0s';

                $formatted_daily_hours[$date] = implode(' ', $parts);
            }

            $report_list[] = [
                'name' => $linkedUser->getFirstname(). ' ' . $linkedUser->getLastname(),
                'daily_hours' => $formatted_daily_hours
            ];
        }
        http_response_code(200);
        echo json_encode($report_list);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Authorization token not found."]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(["Message" => "Method Not Allowed"]);
}
