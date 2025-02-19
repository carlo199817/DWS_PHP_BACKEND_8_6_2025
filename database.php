<?php 

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php"; 
require __DIR__ . '/src/configuration/user.php';
require_once __DIR__ . '/src/configuration/client.php';
require_once __DIR__ . '/src/configuration/membership.php';
require_once __DIR__ . '/src/configuration/rental.php'; 
require_once __DIR__ . '/src/configuration/rates.php'; 
require_once __DIR__ . '/src/configuration/entrance_fees.php'; 
require_once __DIR__ . '/src/configuration/open_play.php'; 
require_once __DIR__ . '/src/configuration/default_rate.php'; 
require_once __DIR__ . '/src/configuration/payment_method.php'; 
require_once __DIR__ . '/src/configuration/slot.php';  
require_once __DIR__ . '/api/security/token.php';  

class DatabaseConnection {
    private $connectionParams = [
        'user' => 'main',
        'password' => '6VXewK71KL6h37nmrOjkLFxui7nz3dS7msuuTyWGdcLUQIqzZZ', 
        'host' => '127.0.0.1', 
        'port' => '3999',  
        'driver' => 'pdo_mysql', 
    ]; 

    private $entityManager;

    public function __construct(string $dbname) {
        $this->connectionParams['dbname'] = $dbname; 

        // Define the path to store Doctrine proxy classes (make sure this is writable)
        $proxyDir = __DIR__ . '/proxies';  // Adjust this path as needed
        
        // Make sure the proxy directory is writable by the web server or PHP process
        if (!is_dir($proxyDir)) {
            mkdir($proxyDir, 0777, true);  // Create the directory if it doesn't exist
        }

        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: array(__DIR__ . "/src/process"), 
            isDevMode: true
        );

        // Set proxy directory and namespace
        $config->setProxyDir($proxyDir);
        $config->setProxyNamespace('DoctrineProxies');

        // Set up the connection
        $connection = DriverManager::getConnection($this->connectionParams);
        
        // Create the entity manager
        $this->entityManager = new EntityManager($connection, $config);
    }

    public function getEntityManager(): EntityManager {
        return $this->entityManager;
    }

    // Optionally, add methods to handle common database tasks
}
