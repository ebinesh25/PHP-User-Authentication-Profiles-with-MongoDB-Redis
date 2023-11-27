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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if email exists in the database
    $sql = "SELECT * FROM user_profile WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Email exists, now check if password matches
        $row = $result->fetch_assoc();
        $hashed_password = $row["PASSWORD"];    
        if (password_verify($password, $hashed_password)) {
            // Password matches, redirect to home page
            echo "valid profile";
            // header("Location: ../profile.html");

            exit;
        } else {
            // Password does not match
            echo "invalid password";

        }
    } else {
        // Email does not exist
        echo "invalid email";
    }
}

?>
