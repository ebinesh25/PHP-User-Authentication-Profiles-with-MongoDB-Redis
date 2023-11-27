<?php
// Establish connection to MySQL
$servername = "localhost"; 
$username = "root"; 
$password = "";
$dbname = "guvi";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first-name"];
    $last_name = $_POST["last-name"];
    $email = $_POST["email"];
    $country = $_POST["country"];
    $state = $_POST["state"];
    $city = $_POST["city"];
    $password = $_POST["password"];

    // Hash the password before storing it in the database (for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the database
    $sql = "INSERT INTO user_profile (first_name, last_name, email, password, country, state, city)
            VALUES ('$first_name', '$last_name', '$email', '$hashed_password', '$country', '$state', '$city')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    
}

$conn->close();
?>

