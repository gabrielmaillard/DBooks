<?php
require __DIR__ . '/../dbLibrary/dbLibrary.php'; // Require the library for the database
require __DIR__ . '/../vars/default.php'; // Require the environment default variables

$db = loadDB($DB_NAME);

$isbn = $_POST['isbn'];

// CHECK IF THE BOOK EXISTS
$query = 'SELECT IFNULL((SELECT `idImage` FROM `Books` WHERE `isbn` = :isbn), 0)';
$statement = $db->prepare($query);
$statement->execute([
    'isbn' => $isbn
]);
$idImage = $statement->fetchColumn();

if (!$idImage) { // There is no idImage --> there is no book at all
    echo json_encode("RETURN");
    die();
}

// REMOVE BOOK -- AUTHORS
$query = 'DELETE FROM `Books_authors` WHERE `idBook` = :isbn';
$statement = $db->prepare($query);
$statement->execute([
    'isbn' => $isbn,
]);

// REMOVE BOOK
$query = 'DELETE FROM `Books` WHERE `isbn` = :isbn';
$statement = $db->prepare($query);
$statement->execute([
    'isbn' => $isbn,
]);

// REMOVE COVER FILE FROM SERVER
$imageDirectory = "../images/" . $idImage . ".jpeg";
if (file_exists($imageDirectory)) {
    unlink($imageDirectory);
}

echo json_encode("Executed!");