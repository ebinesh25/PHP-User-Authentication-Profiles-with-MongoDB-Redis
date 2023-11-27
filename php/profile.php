<?php 
$user_id = $_GET['user_id'] ?? '';

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

$filter = ['user_id' => $user_id];
$options = [];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('users.profile', $query);

$result = current($cursor->toArray());

if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(['message' => 'User not found']);
}
?>