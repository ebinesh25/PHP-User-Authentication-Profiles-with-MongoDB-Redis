<?php

use MongoDB\Client;

if (extension_loaded('redis')) {
    echo "Redis extension is installed.";
} else {
    echo "Redis extension is not installed.";
}

if (extension_loaded('mongodb')) {
    echo "MongoDB extension is installed.";
} else {
    echo "MongoDB extension is not installed.";
}

if (extension_loaded('mysqli')) {
    echo "MySQLi extension is installed.";
} else {
    echo "MySQLi extension is not installed.";
}

$connectionString = "mongodb://localhost:27017/";
try {
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017/?directConnection=true");

    // Check if the MongoDB connection is enabled

    // echo "Connected to MongoDB successfully.";

    // Insert values into the "guvi.users.profile" collection
    $bulk = new MongoDB\Driver\BulkWrite;
    $document = ['name' => 'Aswin', 'age' => 21];
    $bulk->insert($document);
    $manager->executeBulkWrite('users.profile', $bulk);

    // Retrieve and display values from the "guvi.users.profile" collection
    $query = new MongoDB\Driver\Query([]);
    $cursor = $manager->executeQuery('users.profile', $query);


    echo "============all the data================";
    foreach ($cursor as $document) {
        $name = property_exists($document, 'name') ? $document->name : 'N/A';
        $age = property_exists($document, 'age') ? $document->age : 'N/A';
        echo "Name: " . $name . ", Age: " . $age . "<br>";
    }

    

} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Failed to connect to MongoDB: " . $e->getMessage();
}
