<?php

require __DIR__ . '/../../../../../vendor/autoload.php';

use Database\Driver;
use Database\Connection;

$user = 'root';
$password = '';
$dsn = 'mysql:host=localhost;dbname=chronicfrommaars_database';

$connection = new Database\Connection($dsn,$user,$password);
$driver = new Database\Driver($connection);

$driver->delete($_GET['id'],$_GET['columnname'],$_GET['table']);

