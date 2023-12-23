<?php


    include 'connection.php';
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);

    // Variables
    $firstname = "Sibi";
    $lastname = "Cena";
    $email = "sample@gmailcom";
    $country = "India";
    $city = "Coimbatore";
    $state = "Tamil Nadu";
    $user_id = "1";

    function store_in_redis($user_id) {
        global $redis, $firstname, $lastname, $email, $country, $city, $state;

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
    }

    function store_in_mongo($user_id) {
        global $mongo, $firstname, $lastname, $email, $country, $city, $state;

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

    }

    function retrieve_from_redis($user_id){
        global $redis;

        $redisKey = 'user:' . $user_id;
        $userDetails = $redis->get($redisKey);
        $userDetails = json_decode($userDetails, true);

        // Access the retrieved data
        $firstname = $userDetails['firstname'];
        $lastname = $userDetails['lastname'];
        $email = $userDetails['email'];
        $country = $userDetails['country'];
        $city = $userDetails['city'];
        $state = $userDetails['state'];

        // Print the retrieved data with messages
        echo "\n\n============From Redis============\n\n";
        // echo "\nData retrieved from Redis:\n";
        echo "First Name: $firstname\n";
        echo "Last Name: $lastname\n";
        echo "Email: $email\n";
        echo "Country: $country\n";
        echo "City: $city\n";
        echo "State: $state\n";
    }


    // Retrieve user data from MongoDB
    function retrieve_from_mongo($user_id){
        global $mongo;
        $collection = 'guvi.users.profile';
        $filter = ['user_id' => $user_id];
        $options = [];
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $mongo->executeQuery($collection, $query);
        $userData = current($cursor->toArray());

        // echo "\nData retrieved from MongoDB:\n";
        echo "\n\n============From MongoDB============\n\n";
        echo "First Name: " . $userData->firstname . "\n";
        echo "Last Name: " . $userData->lastname . "\n";
        echo "Email: " . $userData->email . "\n";
        echo "Country: " . $userData->country . "\n";
        echo "City: " . $userData->city . "\n";
        echo "State: " . $userData->state . "\n";
    }

    store_in_redis('2');
    store_in_mongo('2');

    retrieve_from_redis('2');
    retrieve_from_mongo('2');
  






?>