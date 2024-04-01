<?php
require __DIR__ . '/../dbLibrary/dbLibrary.php'; // Require the library for the database
require __DIR__ . '/../vars/default.php'; // Require the environment default variables

$db = loadDB($DB_NAME);

// BOOK
// Check if it is already in the database
$isbn = $_POST['isbn'];
$query = 'SELECT EXISTS(SELECT * FROM `Books` WHERE `isbn` = :isbn)';
$statement = $db->prepare($query);
$statement->execute([
    'isbn' => $isbn
]);
$exists = $statement->fetch()[0];

if ($exists) {
    echo "It already exists";
    die();
}

$title = $_POST['title'];
$description = $_POST['description'];
$publisherName = $_POST['publisherName'];
$idLocation = $_POST['idLocation'];
$pageCount = $_POST['pageCount'];
$languageCode = $_POST['languageCode'];
$publishedDate = $_POST['publishedDate'];


// Check if each author exists in DB
$query = 'INSERT INTO `books` (`isbn`, `title`, `description`, `publisherName`, `idLocation`, `pageCount`, `languageCode`, `publishedDate`) VALUES (:isbn, :title, :description, :publisherName, :idLocation, :pageCount, :languageCode, :publishedDate)';
$statement = $db->prepare($query);
$statement->execute([
    'isbn' => $isbn,
    'title' => $title,
    'description' => $description,
    'publisherName' => $publisherName,
    'idLocation' => $idLocation,
    'pageCount' => $pageCount,
    'languageCode' => $languageCode,
    'publishedDate' => $publishedDate,
]);


// AUTHORS and BOOK -- AUTHORS
$authorsKeysNames = json_decode($_POST['authorsKeysNames'], true);
foreach ($authorsKeysNames as $keyName) {
    // AUTHORS
    // Check if already exists thanks to OLID
    $query = 'SELECT EXISTS(SELECT * FROM `authors` WHERE `olid` = :olid)';
    $statement = $db->prepare($query);
    $statement->execute([
        'olid' => $keyName["key"],
    ]);
    if (!$statement->fetch()[0]) { // If it does not exist yet
        // Add author to DB
        $query = 'INSERT INTO `authors` (`id`, `olid`, `name`) VALUES (NULL, :keyOLID, :name)';
        $statement = $db->prepare($query);
        $statement->execute([
            'keyOLID' => $keyName["key"],
            'name' => $keyName["authorName"],
        ]);
    }

    // BOOK -- AUTHORS
    // Get AuthorId
    $query = 'SELECT id FROM `authors` WHERE olid = :olid';
    $statement = $db->prepare($query);
    $statement->execute([
        'olid' => $keyName["key"],
    ]);

    $idAuthor = $statement->fetch()[0];

    // Add link
    $query = 'INSERT INTO `books_authors` (`idBook`, `idAuthor`) VALUES (:isbnBook, :idAuthor)';
    $statement = $db->prepare($query);
    $statement->execute([
        'isbnBook' => $isbn,
        'idAuthor' => $idAuthor, 
    ]);
}

// DOWNLOAD THE COVER

$coverURI = $_POST["coverURI"];

if (isValidURL($coverURI)) {
    $headers = get_headers($coverURI, 1);
    $contentType = $headers["Content-Type"];
    
    // Check if it is a jpeg image
    // if ($contentType === "image/jpg") {
    // Getting the unique id image
    $query = 'SELECT `idImage` FROM `Books` WHERE `isbn` = :isbn';
    $statement = $db->prepare($query);
    $statement->execute([
        "isbn" => $isbn 
    ]);
    $idImage = $statement->fetchColumn();
    file_put_contents("../images/" . $idImage . ".jpeg", fopen($coverURI, 'r')); // Save file with read permission
    // }
}

function isValidURL($url) {
    return (strpos($url, "https://books.google.com") === 0) || 
           (strpos($url, "https://covers.openlibrary.org") === 0);
}

echo json_encode('Executed');