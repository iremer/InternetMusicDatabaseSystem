<?php
$host = 'localhost';
$dbname = 'imuuds';
$user = 'root';
$password = '';

//DSN - data source name
$dsn = "mysql:host=$host;dbname=$dbname";
$connection = new PDO($dsn, $user, $password);
