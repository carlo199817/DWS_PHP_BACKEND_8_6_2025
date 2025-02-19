<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;

require_once "vendor/autoload.php";


$sharedConnectionParams = [
    'user' => 'root',
    'password' => '',
    'host' => '127.0.0.1',
    'port' => '3306',
    'driver' => 'pdo_mysql',
];



// Reusable function to handle database setup, schema updates, and configuration
function setupDbAndUpdateSchema($dbName, $configPaths) {
    // Create the metadata configuration
    $dbConfig = ORMSetup::createAttributeMetadataConfiguration(
        paths: $configPaths,
        isDevMode: true
    );

    // Database connection parameters (you might have shared parameters here)
    $dbConnectionParams = array_merge($GLOBALS['sharedConnectionParams'], ['dbname' => $dbName]);
    $dbConnection = DriverManager::getConnection($dbConnectionParams);

    // Create the EntityManager with the provided configuration
    $dbEntityManager = new EntityManager($dbConnection, $dbConfig);

    // Create the SchemaTool and update schema
    $dbSchemaTool = new SchemaTool($dbEntityManager);
    $dbClasses = $dbEntityManager->getMetadataFactory()->getAllMetadata();
    $dbSchemaTool->updateSchema($dbClasses, true);

    // Output success message
    echo "Schema updated for $dbName.\n";
}

// Define paths for main_db and dws_db_2025
$mainDbPaths = [
    __DIR__ . "/src/configuration", 
    __DIR__ . "/src/configuration_process"
];

$secondDbPaths = [
    __DIR__ . "/src/process", 
    __DIR__ . "/src/configuration_process"
];

// Call the function for each database
setupDbAndUpdateSchema('main_db', $mainDbPaths);
setupDbAndUpdateSchema('dws_db_2025', $secondDbPaths);




// // Combine paths for general configuration and process configuration
// $combinedPaths = array_merge(
//     [__DIR__ . "/src/configuration"], 
//     [__DIR__ . "/src/configuration_process"]
// );

// // Create a single configuration that includes both metadata paths
// $mainDbConfig = ORMSetup::createAttributeMetadataConfiguration(
//     paths: $combinedPaths, 
//     isDevMode: true
// );

// // Database connection parameters (you might have shared parameters here)
// $mainDbConnectionParams = array_merge($sharedConnectionParams, ['dbname' => 'main_db']);
// $mainDbConnection = DriverManager::getConnection($mainDbConnectionParams);

// // Create the EntityManager with the combined configuration
// $mainDbEntityManager = new EntityManager($mainDbConnection, $mainDbConfig);



// $mainDbSchemaTool = new SchemaTool($mainDbEntityManager); 
// $mainDbClasses = $mainDbEntityManager->getMetadataFactory()->getAllMetadata();


// $mainDbSchemaTool->updateSchema($mainDbClasses, true);  


// echo "Schema updated for main_db.\n";



// $secondcombinedPaths = array_merge(
//     [__DIR__ . "/src/process"], 
//     [__DIR__ . "/src/configuration_process"]
// );

// // Create a single configuration that includes both metadata paths
// $secondDbConfig = ORMSetup::createAttributeMetadataConfiguration(
//     paths: $secondcombinedPaths, 
//     isDevMode: true
// );

// // Database connection parameters (you might have shared parameters here)
// $secondDbConnectionParams = array_merge($sharedConnectionParams, ['dbname' => 'dws_db_2025']);
// $secondDbConnection = DriverManager::getConnection($secondDbConnectionParams);

// // Create the EntityManager with the combined configuration
// $secondDbEntityManager = new EntityManager($secondDbConnection, $secondDbConfig);



// $secondDbSchemaTool = new SchemaTool($secondDbEntityManager); 
// $secondDbClasses = $secondDbEntityManager->getMetadataFactory()->getAllMetadata();


// $secondDbSchemaTool->updateSchema($secondDbClasses, true);  


// echo "Schema updated for dws_db_2025.\n";







// $mainDbConfig = ORMSetup::createAttributeMetadataConfiguration(
//     paths: array(__DIR__ . "/src/configuration"), 
//     isDevMode: true
// );

// $mainDbConnectionParams = array_merge($sharedConnectionParams, ['dbname' => 'main_db']);
// $mainDbConnection = DriverManager::getConnection($mainDbConnectionParams);


// $mainDbEntityManager = new EntityManager($mainDbConnection, $mainDbConfig); 

// $mainDbSchemaTool = new SchemaTool($mainDbEntityManager); 
// $mainDbClasses = $mainDbEntityManager->getMetadataFactory()->getAllMetadata();


// $mainDbSchemaTool->updateSchema($mainDbClasses, true);  


// echo "Schema updated for main_db.\n";




// $config_processDbConfig = ORMSetup::createAttributeMetadataConfiguration(
//     paths: array(__DIR__ . "/src/configuration_process"), 
//     isDevMode: true
// );



// $main_config_processDbConnectionParams = array_merge($sharedConnectionParams, ['dbname' => 'main_db']);
// $main_config_processDbConnection = DriverManager::getConnection($main_config_processDbConnectionParams);


// $main_config_processDbEntityManager = new EntityManager($main_config_processDbConnection, $config_processDbConfig); 

// $main_config_processDbSchemaTool = new SchemaTool($main_config_processDbEntityManager); 
// $main_config_processDbClasses = $main_config_processDbEntityManager->getMetadataFactory()->getAllMetadata();


// $main_config_processDbSchemaTool->updateSchema($main_config_processDbClasses, true);  




// $second_config_processDbConnectionParams = array_merge($sharedConnectionParams, ['dbname' => 'dws_db_2025']);
// $second_config_processDbConnection = DriverManager::getConnection($second_config_processDbConnectionParams);


// $second_config_processDbEntityManager = new EntityManager($second_config_processDbConnection, $config_processDbConfig); 

// $second_config_processDbSchemaTool = new SchemaTool($second_config_processDbEntityManager); 
// $second_config_processDbClasses = $main_config_processDbEntityManager->getMetadataFactory()->getAllMetadata();


// $second_config_processDbSchemaTool->updateSchema($second_config_processDbClasses, true);  

// echo "Schema updated for config_process.\n";







// $processDbConfig = ORMSetup::createAttributeMetadataConfiguration(
//     paths: array(__DIR__ . "/src/process/"), 
//     isDevMode: true
// );

// $processDbConnectionParams = array_merge($sharedConnectionParams, ['dbname' => 'dws_db_2025']);
// $processDbConnection = DriverManager::getConnection($processDbConnectionParams);


// $processDbEntityManager = new EntityManager($processDbConnection, $processDbConfig); 


// $processDbSchemaTool = new SchemaTool($processDbEntityManager); 
// $processDbClasses = $processDbEntityManager->getMetadataFactory()->getAllMetadata();


// $processDbSchemaTool->updateSchema($processDbClasses, true);  


// echo "Schema updated for process_db.\n";






?>