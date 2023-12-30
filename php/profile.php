<?php 
// include 'is_saved?';
// header('Content-Type: application/json');
session_start();

// $user_id = '33';
$user_id = $_SESSION["userID"];
// echo $user_id;

$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Create a new Redis instance
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
// session_start();


// if(!isset($_SESSION["userDetails"])) 
// {
//     // From Session
//     $result = $_SESSION["userDetails"];
//     echo "Printed From Session";
// } 

$redisKey = 'user:' . $user_id;
$userDetailsJson = $redis->get($redisKey); 
// echo 'usd =>' . $userDetailsJson;

if ($userDetailsJson) //if true
{
    // echo "\n\n============From Redis============\n\n";

    $result = json_decode($userDetailsJson, true);
} else {
    // Not found in redis, Retrieves from MongoDB
    
    // $query = new MongoDB\Driver\Query( ['user_id' => $user_id],  []);
    // $cursor = $manager->executeQuery('guvi.users.profile', $query);
    // $result = current($cursor->toArray());

    $collection = 'guvi.users.profile';
    $filter = ['user_id' => $user_id];
    $options = [];
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $mongo->executeQuery($collection, $query);
    $result = current($cursor->toArray());
    if ($result) {
        $userData = $result;
    
        // echo "Result from Mongo";
        // echo "\n\n============From MongoDB============\n\n";
        // echo "First Name: " . $userData->firstname . "\n";
        // echo "Last Name: " . $userData->lastname . "\n";
        // echo "Email: " . $userData->email . "\n";
        // echo "Country: " . $userData->country . "\n";
        // echo "City: " . $userData->city . "\n";
        // echo "State: " . $userData->state . "\n";
        
        //for future purpose
        $redis->set($redisKey, json_encode($result));
    } else {
        echo "No user found with user_id: $user_id";
    }
}

if ($result) {
    echo json_encode($result);
} else {
    echo json_encode($user_id);
}
?>