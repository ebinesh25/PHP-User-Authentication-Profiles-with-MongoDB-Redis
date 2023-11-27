<?php 
$servername = "localhost"; 
$username = "root"; 
$password = "";
$dbname = "guvi";

$conn = new mysqli($servername, $username, $password, $dbname);

$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017/?directConnection=true");


?>