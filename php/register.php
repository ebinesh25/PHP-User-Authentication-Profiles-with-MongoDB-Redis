<?php
include 'connection.php';

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
session_start();

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["first-name"];
    $lastname = $_POST["last-name"];
    $email = $_POST["email"];
    $country = $_POST["country"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $password = $_POST["password"];

    // Check if email already exists in MySQL
    $sql = "SELECT * FROM credentials WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "Email already exists";
        exit;
    }

    // Store email and password in MySQL
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO credentials (email, password) VALUES ('$email', '$hashed_password')";
    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        exit;
    }

    // Get the user_id from MySQL
    $sql = "SELECT id FROM credentials WHERE email = '$email'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $user_id = $row["id"];

    
    // Store user details in Redis
    $redisKey = 'user:' . $user_id;
    $userDetails = array(
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'country' => $country,
        'city' => $city,
        'state' => $state
    );
    $redis->set($redisKey, json_encode($userDetails));

    // Store user data in MongoDB
    $collection = 'guvi.users.profile';
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
    $mongo->executeBulkWrite($collection, $bulk);

    

    if($mongo){
        echo "Saved";
    }
    else{
        echo "Notsaved";
    }
    
}
?>