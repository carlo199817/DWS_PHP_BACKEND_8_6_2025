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

use configuration_process\equipment;
use PhpOffice\PhpSpreadsheet\IOFactory;

$databaseName = "main_db";
$dbConnection = new DatabaseConnection($databaseName);
$entityManager = $dbConnection->getEntityManager();
$input = (array) json_decode(file_get_contents('php://input'), TRUE);

function cleanHeaders($headers)
{
    return array_values(array_filter($headers, function ($header) {
        return !is_null($header) && $header !== '';
    }));
}
function cleanHeadersWithTrim($headers)
{
    return array_map(function ($header) {
        return trim(str_replace(["\n", "\r"], '', $header));
    }, cleanHeaders($headers));
}



while (true) {
    $flag = "on";
    if ($flag === "on") {
        $flag = "off";



        $automation_store = $entityManager->getRepository(configuration\automation_store::class);
        $queryBuilder = $automation_store->createQueryBuilder('p');
        $queryBuilder->Where('p.process IS NULL');
        $results = $queryBuilder->getQuery()->getResult();

        if (count($results)) {
            $internal_flag = "on";

            if ($internal_flag === "on") {
                $internal_flag = "off";

                $targetDirectory = $file_directory . "/digital_workspace_file/file/store_asset"  . '/' . $results[0]->getFile();


                if (file_exists($targetDirectory)) {
                    try {
                        $spreadsheet = IOFactory::load($targetDirectory);
                        $sheet = $spreadsheet->getActiveSheet();
                        $data = $sheet->toArray();
                        $cleanHeaders = cleanHeaders(array_shift($data));
                        $headers = cleanHeadersWithTrim($cleanHeaders);
                        $assetRepository = $entityManager->getRepository(configuration_process\asset::class);
                        $equipmentRepository = $entityManager->getRepository(configuration_process\equipment::class);
                        $partRepository = $entityManager->getRepository(configuration_process\part::class);

                        foreach ($data as $row) {
                            $row = array_map(fn($value) => $value ?? '', $row);
                            $row = array_pad($row, count($headers), '');
                            $row = array_slice($row, 0, count($headers));
                            if (count($row) === count($headers)) {
                                $combinedRow = array_combine($headers, $row);

                                $category = trim($combinedRow['Category'] ?? '');

                                if ($category !== '') {
                                    $existingAsset = $assetRepository->findOneBy(['description' => $category]);

                                    if ($existingAsset) {
                                        $asset = $existingAsset;
                                    } else {
                                        $asset = new configuration_process\asset();
                                        $asset->setDescription($category);
                                        $entityManager->persist($asset);
                                    }
                                }
                                $entityManager->flush();
                                $assets = $entityManager->getRepository(configuration_process\asset::class)->findAll();
                                foreach ($assets as $asset) {
                                    $equipment = trim($combinedRow[$asset->getDescription()] ?? '');

                                    if ($equipment !== '') {
                                        $link_asset = $assetRepository->findOneBy(['description' => $combinedRow['Category']]);

                                        $existingEquipment = $equipmentRepository->findOneBy(['description' => $equipment]);
                                        if ($existingEquipment) {
                                            $new_equipment = $existingEquipment;
                                        } else {
                                            $new_equipment = new configuration_process\equipment();
                                            $new_equipment->setDescription($equipment);
                                            $entityManager->persist($new_equipment);
                                        }
                                        if (!$link_asset->getAssetequipment()->contains($new_equipment)) {
                                            $link_asset->setAssetequipment($new_equipment);
                                        }
                                    }
                                }
                                $entityManager->flush();
                                $part = trim($combinedRow['Part']);
                                if ($part !== '') {
                                    $link_equipment = $equipmentRepository->findOneBy(['description' => $combinedRow['Equipment']]);
                                    $existingPart = $partRepository->findOneBy(['description' => $part]);
                                    if ($existingPart) {
                                        $new_part = $existingPart;
                                    } else {
                                        $new_part = new configuration_process\part();
                                        $new_part->setDescription($part);
                                        $entityManager->persist($new_part);
                                    }



                                    if (!$link_equipment->getEquipmentpart()->contains($new_part)) {
                                        $link_equipment->setEquipmentpart($new_part);
                                    }
                                }
                            }
                        }

                        $entityManager->flush();
                        $entityManager->clear();
                    } catch (Exception $e) {
                        echo json_encode(["Message" => $e->getMessage()]);
                    }
                } else {
                    echo json_encode(["Message" => "File not found.", "path" => htmlspecialchars($targetDirectory)]);
                }
            }

            $automation_store = $entityManager->find(configuration\automation_store::class, $results[0]->getId());
            $automation_store->setProcess(true);
            $entityManager->flush();
            $internal_flag = "on";
            $flag = "on";
            echo "Upload Successfully!";
        } else {
            $flag = "on";
        }

        $flag = "on";
        echo "Uploaded Successfully!\n";
    }
    sleep(2);
}
