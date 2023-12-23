<?php 
    include 'connection.php';

    // Create a new Redis instance
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);

    function insert_email_pswd($email, $password){
        global $conn;

        // Check if email already exists in MySQL
        $sql = "SELECT * FROM credentials WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "Email already exists";
            exit;
        }

        // Store email and password in MySQL
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // $hashed_password = $password;
        $sql = "INSERT INTO credentials (email, password) VALUES ('$email', '$hashed_password')";
        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
            exit;
        }


    }
    

    function verify_passowrd($email, $password){
        global $conn, $redis, $mongo;

        $sql = "SELECT * FROM credentials WHERE email = '$email'";
        $result = $conn->query($sql);

        if (mysqli_num_rows($result) > 0) {


            $row = $result->fetch_assoc();
            $hashed_password = trim($row["password"]);
            $user_id = $row["id"];
        
    
            if (password_verify(trim($password), $hashed_password)) {
            // if ($password === $hashed_password) {

                echo "Valid emial and paswd\n";
    
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

                    // $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
                    // $collection = $mongoClient->guvi->users->profile;
                    // $document = $collection->findOne(['email' => $email]);


                    $userDetails = array(
                        'firstname' => $document['firstname'],
                        'lastname' => $document['lastname'],
                        'email' => $document['email'],
                        'country' => $document['country'],
                        'city' => $document['city'],
                        'state' => $document['state']
                    );
                    // Storing in redis
                    $redis->set($redisKey, json_encode($userDetails));
                } else {
                    $userDetails = json_decode($userDetailsJson, true);
                }
    
                // storing in session
                // $_SESSION["userDetails"] = $userDetails;
    
                echo "user id:", $user_id;

                // exit;
            } else {
                echo "invalid password";
            }
        } 
        else {
            echo "invalid email";
        }
    }


    // Main Function
    $email = "randyordttosd0n@rko.com";
    $password = "@ebinesh.A25";

    insert_email_pswd($email, $password);
    verify_passowrd($email, $password);

?>