<?php
require __DIR__ . '/../dbLibrary/dbLibrary.php'; // Require the library for the database
require __DIR__ . '/../vars/default.php'; // Require the environment default variables

$db = loadDB($DB_NAME);

$isbn = $_POST['isbn'];

// CHECK IF BOOK EXISTS

$query = 'SELECT EXISTS(SELECT `isbn` FROM `Books` WHERE `isbn` = :isbn)';
$statement = $db->prepare($query);
$statement->execute([
    'isbn' => $isbn
]);
$exists = $statement->fetch()[0];

if(!$exists) {
    die();
}

// BOOK

$query = '
SELECT Books.isbn, Books.title, Books.description, Books.publisherName, Books.pageCount, Books.languageCode, Books.publishedDate, Books.idImage, Locations.name
FROM Books
INNER JOIN Locations
ON Locations.id = Books.idLocation
WHERE isbn = :isbn
';
$statement = $db->prepare($query);
$statement->execute([
    "isbn" => $isbn,
]);

$bookInfo = $statement->fetch(PDO::FETCH_ASSOC);
$coverURI = "../images/" . $bookInfo["idImage"] . ".jpeg";
if (!file_exists($coverURI)) {
    $bookInfo["idImage"] = 0;
}

// NOW GET AUTHORS

$queryAuthors = '
SELECT Authors.olid, Authors.name AS authorName
FROM Authors
INNER JOIN Books_authors
ON Books_authors.idAuthor = Authors.id
WHERE Books_authors.idBook = :isbn
';
$statementAuthors = $db->prepare($queryAuthors);
$statementAuthors->execute([
    "isbn" => $isbn,
]);

$authorNames = $statementAuthors->fetchAll(PDO::FETCH_ASSOC);

if ($bookInfo) {
    $bookInfo['author_names'] = $authorNames;
    echo json_encode($bookInfo);
    return json_encode($bookInfo);
} else {
    echo json_encode(null); // Aucun résultat trouvé
}