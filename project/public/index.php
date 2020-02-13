<?php

$options = [
    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    \PDO::ATTR_EMULATE_PREPARES => false,
];
$databaseConnection = new \PDO('mysql:host=db;dbname=project;charset=utf8mb4', 'project', 'project', $options);

$queryHandle = $databaseConnection->query('SELECT NOW() AS currentTime');
$queryHandle->execute();
$currentTime = $queryHandle->fetch(\PDO::FETCH_ASSOC);

echo sprintf('<div class="center">Current time from the database server is: %1$s</div>', $currentTime['currentTime']);

$queryHandle->closeCursor();

phpinfo();