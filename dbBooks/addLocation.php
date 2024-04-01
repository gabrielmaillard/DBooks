<?php
require __DIR__ . '/../dbLibrary/dbLibrary.php'; // Require the library for the database
require __DIR__ . '/../vars/default.php'; // Require the environment default variables

$db = loadDB($DB_NAME);

$name = $_POST["name"];

$query = 'INSERT INTO Locations (id, name) VALUES (NULL, :name);';
$statement = $db->prepare($query);
$statement = $statement->execute([
    ":name" => $name
]);

if ($statement) {
    $array = [
        "id" => $db->lastInsertId(),
        "name"=> $name,
    ];
    echo json_encode($array);
}