<?php 
$servername = "localhost"; 
$username = "root"; 
$password = "";
$dbname = "guvi";

$conn = new mysqli($servername, $username, $password, $dbname);

$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017/?directConnection=true");

// To check redis connection
try {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    echo $redis->ping() ? "Redis connected successfully\n" : "Redis connection failed\n";
} catch (Exception $e) {
    echo "Redis connection failed: " . $e->getMessage();
}


// To check MongoDB connection
try {
    $mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017/?directConnection=true");
    $command = new MongoDB\Driver\Command(['ping' => 1]);
    $mongo->executeCommand('db', $command);
    echo "MongoDB connected successfully\n";
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "MongoDB connection failed: \n" . $e->getMessage();
}


?>