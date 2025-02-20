<?php 

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php"; 
/*CONFIGURATION*/
require __DIR__ . '/src/configuration/user.php';
require __DIR__ . '/src/configuration/store.php';
/*CONFIGURATION_PROCESS*/
require __DIR__ . '/src/configuration_process/user_type.php';
require __DIR__ . '/src/configuration_process/platform.php';
require __DIR__ . '/src/configuration_process/form.php';
require __DIR__ . '/src/configuration_process/connection_form.php';
require __DIR__ . '/src/configuration_process/justification_form.php';
require __DIR__ . '/src/configuration_process/table_form.php';
require __DIR__ . '/src/configuration_process/itinerary_type.php';
require __DIR__ . '/src/configuration_process/connection_itinerary.php';
require __DIR__ . '/src/configuration_process/justification_itinerary.php';
/*PROCESS*/
require __DIR__ . '/src/process/user.php';


class DatabaseConnection {
    private $connectionParams = [
        'user' => 'test',
        'password' => 'Secret_1234', 
        'host' => '127.0.0.1', 
        'port' => '3306',  
        'driver' => 'pdo_mysql', 
    ]; 

    private $entityManager;

    public function __construct(string $dbname) {
        $this->connectionParams['dbname'] = $dbname; 
        $proxyDir = __DIR__ . '/proxies';  
        if (!is_dir($proxyDir)) {
            mkdir($proxyDir, 0777, true);  
        }
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: array(
                __DIR__ . "/src/configuration",
                __DIR__ . "/src/configuration_process",
                __DIR__ . "/src/process",
        ), 
            isDevMode: true
        );
        $config->setProxyDir($proxyDir);
        $config->setProxyNamespace('DoctrineProxies');
        $connection = DriverManager::getConnection($this->connectionParams);
        $this->entityManager = new EntityManager($connection, $config);
    }
    public function getEntityManager(): EntityManager {
        return $this->entityManager;
    }

}
