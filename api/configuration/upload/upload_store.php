<?php

set_time_limit(0);

ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json; charset=utf-8');

$parent_directory = dirname(dirname(dirname(__DIR__)));
require $parent_directory . '/vendor/autoload.php';
require $parent_directory . '/database.php';

$file_directory = dirname(dirname(dirname(dirname(__DIR__))));
$fileName = $_GET['file'] ?? null; 
$filePathParam = $_GET['path'] ?? ''; 

use PhpOffice\PhpSpreadsheet\IOFactory;
$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

function cleanHeaders($headers) {
    return array_values(array_filter($headers, function($header) {
        return !is_null($header) && $header !== '';
    }));
}

function cleanHeadersWithTrim($headers) {
    return array_map(function($header) {
        return trim(str_replace(["\n", "\r"], '', $header)); 
    }, cleanHeaders($headers));
}

while(true)
{
    $flag = "on";
    if($flag === "on") {
        $flag = "off";
        $automation_store = $entityManager->getRepository(configuration\automation_store::class);
        $firstRecord = $automation_store->findOneBy([], ['id' => 'ASC']);
        if ($firstRecord) {
            $targetDirectory = $file_directory . "/digital_workspace_file/file/" . $firstRecord->getPath()->getDescription() . '/' . $firstRecord->getFile();
            if (file_exists($targetDirectory)) {
                try {
                    $spreadsheet = IOFactory::load($targetDirectory);
                    $sheet = $spreadsheet->getActiveSheet();
                    $data = $sheet->toArray();
                    $cleanHeaders = cleanHeaders(array_shift($data));
                    $headers = cleanHeadersWithTrim($cleanHeaders);

                    foreach ($data as $row) {
                        $row = array_map(function($value) {
                            return $value === null ? '' : $value; 
                        }, $row);
                        $row = array_pad($row, count($headers), '');
                        $row = array_slice($row, 0, count($headers));

                        if (count($row) === count($headers)) {
                            $combinedRow = array_combine($headers, $row);
                            $coordinatesArray = explode(',', $combinedRow['Coordinates'] ?? '');
                            $longitude = isset($coordinatesArray[0]) ? trim($coordinatesArray[0]) : '';
                            $latitude = isset($coordinatesArray[1]) ? trim($coordinatesArray[1]) : '';

                            if (!is_numeric($latitude) || !is_numeric($longitude) || $latitude == '#N/A' || $longitude == '#N/A' || empty($latitude) || empty($longitude)) {
                                $latitude = 0.0;
                                $longitude = 0.0;
                            } else {
                                $latitude = (float) $latitude;
                                $longitude = (float) $longitude;
                            }
                            $categoryRepository = $entityManager->getRepository(configuration\category::class);
                            if (!empty($combinedRow['REGION']) && !empty($combinedRow['BRANCH/ BUSINESS CENTER'])) {
                                $region = $categoryRepository->findOneBy(['description' => $combinedRow['REGION']]);
                                if ($region) {
                                    $business_center = $categoryRepository->findOneBy(['description' => $combinedRow['BRANCH/ BUSINESS CENTER']]);
                                    if (!$business_center) {
                                        $new_business_center = new configuration\category;
                                        $table_category = new configuration\table_category;
                                        $new_business_center->setDescription($combinedRow['BRANCH/ BUSINESS CENTER']);
                                        $type = $entityManager->find(configuration\category_type::class, 2);
                                        $new_business_center->setCategorytype($type);
                                        $entityManager->persist($new_business_center);
                                        $table_category->setCategory($new_business_center);
                                        $entityManager->persist($table_category);
                                        $entityManager->flush();
                                        $region->setCategorylink($new_business_center);
                                        $business_center = $new_business_center;
                                    }
                                } else {
                                    $new_region = new configuration\category;
                                    $table_category = new configuration\table_category;
                                    $new_region->setDescription($combinedRow['REGION']);
                                    $type = $entityManager->find(configuration\category_type::class, 1);
                                    $new_region->setCategorytype($type);
                                    $entityManager->persist($new_region);
                                    $table_category->setCategory($new_region);
                                    $entityManager->persist($table_category);
                                    $new_business_center = new configuration\category;
                                    $bc_table_category = new configuration\table_category;
                                    $new_business_center->setDescription($combinedRow['BRANCH/ BUSINESS CENTER']);
                                    $type = $entityManager->find(configuration\category_type::class, 2);
                                    $new_business_center->setCategorytype($type);
                                    $entityManager->persist($new_business_center);
                                    $new_region->setCategorylink($new_business_center);
                                    $bc_table_category->setCategory($new_business_center);
                                    $entityManager->persist($bc_table_category);
                                    $entityManager->flush();
                                    $business_center = $new_business_center;
                                }
                                $userRepository = $entityManager->getRepository(configuration\user::class);
                                $user = $userRepository->findOneBy(['username' => $combinedRow['OUTLET CODE(BAVI)']]);
                                if (!$user) {
                                    $new_user = new configuration\user();
                                    $new_user->setUsername($combinedRow['OUTLET CODE(BAVI)']);
                                    $new_user->setPassword('123456');
                                    $path = $entityManager->find(configuration\path::class, 5);
                                    $new_user->setPath($path);
                                    $user_type = $entityManager->find(configuration_process\user_type::class, 2);
                                    $new_user->setUsertype($user_type);
                                    $new_user->setActivate(true);
                                    $new_user->setPicture("profile.png");
                                    $storeRepository = $entityManager->getRepository(configuration\store::class);
                                    $store = $storeRepository->findOneBy(['outlet_code' => $combinedRow['OUTLET CODE(BAVI)']]);
                                    if (!$store) {
                                        $store = new configuration\store();
                                        $store->setOutletcode($combinedRow['OUTLET CODE(BAVI)']);
                                        $created_by = $entityManager->find(configuration\user::class, $firstRecord->getCreatedby()->getId());
                                        $store->setCreatedby($created_by);
                                        $store->setOutletname($combinedRow['OUTLET NAME']);
                                        $store->setTown($combinedRow['TOWN GROUP']);
                                        $store->setZipcode($combinedRow['ZIP CODE']);
                                        $store->setAddress($combinedRow['ADDRESS']);
                                        $store->setLatitude($latitude);
                                        $store->setLongitude($longitude);
                                        $store->setDistance($combinedRow['distance']);
                                        $business_center->setCategorystore($store);
                                        $entityManager->persist($store);
                                    }
                                    $new_user->setStore($store);
                                    $entityManager->persist($new_user);
                                }
                                $entityManager->flush();
                            }
                        }
                    }
                    $entityManager->clear();
                    $entityManager->flush();    
                } catch (Exception $e) {
                    echo json_encode(["Message" => $e->getMessage()]);
                }
            } else {
                echo json_encode(["Message" => "File not found.", "path" => htmlspecialchars($targetDirectory)]);
            }

            try {
                $entityManager->remove($firstRecord);
            } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
                $managedEntity = $entityManager->getRepository(configuration\automation_store::class)->find($firstRecord->getId());
                if ($managedEntity) {
                    $entityManager->remove($managedEntity);
                } else {
                    echo "Entity not found, cannot remove.";
                }
            }
            $entityManager->flush();



        }
        $flag = "on";
        echo "Uploaded Successfully!\n";
    }

    sleep(2);
}
?>
