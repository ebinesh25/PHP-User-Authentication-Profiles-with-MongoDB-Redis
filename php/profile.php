<?php 
$user_id = $_GET['user_id'] ?? '';
// $user_id = '11';

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Create a new Redis instance
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
session_start();


if(isset($_SESSION["userDetails"])) 
{
    // From Session
    $result = $_SESSION["userDetails"];
} 
else
 {
    $redisKey = 'user:' . $user_id;
    $userDetailsJson = $redis->get($redisKey);
    if ($userDetailsJson === false)
    {
        // Not found in redis, Retrieves from MongoDB
        $query = new MongoDB\Driver\Query( ['user_id' => $user_id],  []);
        $cursor = $manager->executeQuery('users.profile', $query);
        $result = current($cursor->toArray());

        echo "Result from MOngo";
        echo $result;
        
        //for future purpose
        $redis->set($redisKey, json_encode($result));
    } 
    else
    {
        $result = json_decode($userDetailsJson, true);
    }
}
if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(['message' => 'User not found']);
}
?>