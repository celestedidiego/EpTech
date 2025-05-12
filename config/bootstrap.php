<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;

require_once __DIR__ . '/../vendor/autoload.php';

$connectionParams = [
    'dbname'   => 'eptech',
    'user'     => 'root',
    'password' => '',
    'host'     => '127.0.0.1',
    'driver'   => 'pdo_mysql',
];

// Check if the database exists
try {
    $conn = new PDO("mysql:host=".$connectionParams['host']."; charset=utf8;", $connectionParams['user'], $connectionParams['password']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . $connectionParams['dbname']."'");
    if ($stmt->rowCount() == 0) {
        // Database does not exist, create it
        $sql = "CREATE DATABASE " . $connectionParams['dbname'];
        $conn->exec($sql);
    }
    $conn = null;
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
// Configuration Doctrine
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/../src/Entity'], // Percorso delle entità
    isDevMode: true
);

$entityManager = new EntityManager(
    DriverManager::getConnection($connectionParams, $config),
    $config
);

function GetEntityManager (): EntityManager 
{
    global $entityManager;
    return $entityManager;
}
