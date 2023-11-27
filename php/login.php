<?php 


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
            $user_id = $row["id"];
            echo $user_id;
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
