<?php
require __DIR__ . '/../dbLibrary/dbLibrary.php'; // Require the library for the database
require __DIR__ . '/../vars/default.php'; // Require the environment default variables

$db = loadDB($DB_NAME);

$isbn = $_POST['isbn'];

$query = 'SELECT EXISTS(SELECT * FROM `Books` WHERE `isbn` = :isbn)';
$statement = $db->prepare($query);
$statement->execute([
    'isbn' => $isbn
]);
$exists = $statement->fetch()[0];

echo json_encode($exists);