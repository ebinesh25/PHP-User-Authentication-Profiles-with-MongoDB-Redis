<?php 
include 'connection.php';

// Create a new Redis instance
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST["email"];
    $password = $_POST["password"];
    // $email = "sibiebi2002@gmail.com";
    // $password = "@ebinesh.A25";

    // Check if email exists in the database
    $sql = "SELECT * FROM credentials WHERE email = '$email'";
    $result = $conn->query($sql);

    // echo mysqli_num_rows($result);

    if (mysqli_num_rows($result) > 0) {
        $row = $result->fetch_assoc();
        // print_r($row);

        $hashed_password = trim($row["password"]);
        $user_id = $row["id"];

        // To verify passwords
        if (password_verify($password, $hashed_password)) {


            // Retrieve user details from Redis
            $redisKey = 'user:' . $email;
            $userDetailsJson = $redis->get($redisKey);
            if ($userDetailsJson === false) {

                // Not in Redis, retrieve from MongoDB
                $filter = ['email' => $email];
                $options = [];

                $query = new MongoDB\Driver\Query($filter, $options);
                $cursor = $mongo->executeQuery('guvi.users.profile', $query);

                $document = current($cursor->toArray());


                $userDetails = array(
                    'firstname' => $document->firstname,
                    'lastname' => $document->lastname,
                    'email' => $document->email,
                    'country' => $document->country,
                    'city' => $document->city,
                    'state' => $document->state
                );
                // Storing in redis
                $redis->set($redisKey, json_encode($userDetails));

            } else {
                $userDetails = json_decode($userDetailsJson, true);
            }

            // storing user ID in session
            $_SESSION["userDetails"] = $userDetails;
            $_SESSION["userID"] = $user_id;

            echo $user_id;
            exit;
        } else {
            echo "invalid password";
        }
    } 
    else {
        echo "invalid email";
    }
}

?>
