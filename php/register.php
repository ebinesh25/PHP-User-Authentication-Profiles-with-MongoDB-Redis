<?php
// Establish connection to MySQL
$servername = "localhost"; 
$username = "root"; 
$password = "";
$dbname = "guvi";


$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017/?directConnection=true");
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["first-name"];
    $lastname = $_POST["last-name"];
    $email = $_POST["email"];
    $country = $_POST["country"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    
    $password = $_POST["password"];


    
    // Store email and password in MySQL
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO user_profile (email, password) VALUES ('$email', '$hashed_password')";


    $sql = "SELECT * FROM user_profile WHERE email = '$email'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $user_id = $row["id"];

    // Store user data in MongoDB
    $collection = 'users.profile';
    $document = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'country' => $country,
        'email' => $email,
        'city' => $city,
        'state' => $state,
        'user_id' => $user_id
    ];

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($document);

    try {
        $mongo->executeBulkWrite($collection, $bulk);
        echo "New record created successfully";
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error: " . $e->getMessage();
    }



    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}