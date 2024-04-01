<?php
require __DIR__ . '/../dbLibrary/dbLibrary.php'; // Require the library for the database
require __DIR__ . '/../vars/default.php'; // Require the environment default variables

$db = loadDB($DB_NAME);

$string = $_POST['string'];
$newString = implode('* ', explode(' ', $string)) . '*';

$query = '
SELECT 
Books.isbn
FROM Books 
WHERE MATCH(title) 
AGAINST(:string IN BOOLEAN MODE);
';
$statement = $db->prepare($query);
$statement->execute([
    "string" => $newString,
]);

$results = $statement->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($results);