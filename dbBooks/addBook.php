<?php
require __DIR__ . '/../dbLibrary/dbLibrary.php'; // Require the library for the database
require __DIR__ . '/../vars/default.php'; // Require the environment default variables

$db = loadDB($DB_NAME);

try {
    $db->beginTransaction();

    // BOOK
    // Check if it is already in the database
    $isbn = $_POST['isbn'];
    $query = 'SELECT EXISTS(SELECT * FROM `Books` WHERE `isbn` = :isbn)';
    $statement = $db->prepare($query);
    $statement->execute([
        'isbn' => $isbn
    ]);
    $exists = $statement->fetchColumn();

    if ($exists) {
        echo "It already exists";
        die();
    }

    $title = $_POST['title'];
    $description = checkNull($_POST['description']) ?? "Aucune description n'est disponible";
    $publisherName = $_POST['publisherName'];
    $idLocation = $_POST['idLocation'];
    $pageCount = $_POST['pageCount'];
    $languageCode = $_POST['languageCode'];
    $publishedDate = $_POST['publishedDate'];

    // Insert book details
    $query = 'INSERT INTO `books` (`isbn`, `title`, `description`, `publisherName`, `idLocation`, `pageCount`, `languageCode`, `publishedDate`) 
              VALUES (:isbn, :title, :description, :publisherName, :idLocation, :pageCount, :languageCode, :publishedDate)';
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
        $query = 'SELECT id FROM `authors` WHERE `olid` = :olid';
        $statement = $db->prepare($query);
        $statement->execute([
            'olid' => $keyName["key"],
        ]);
        $authorId = $statement->fetchColumn();

        if (!$authorId) { // If it does not exist yet
            // Add author to DB
            $query = 'INSERT INTO `authors` (`olid`, `name`) VALUES (:keyOLID, :name)';
            $statement = $db->prepare($query);
            $statement->execute([
                'keyOLID' => $keyName["key"],
                'name' => $keyName["authorName"],
            ]);

            // Get the last inserted author id
            $authorId = $db->lastInsertId();
        }

        // BOOK -- AUTHORS
        // Add link
        $query = 'INSERT INTO `books_authors` (`idBook`, `idAuthor`) VALUES (:isbnBook, :idAuthor)';
        $statement = $db->prepare($query);
        $statement->execute([
            'isbnBook' => $isbn,
            'idAuthor' => $authorId, 
        ]);
    }

    // DOWNLOAD THE COVER
    $coverURI = $_POST["coverURI"];
    // Getting the unique id image
    $query = 'SELECT `idImage` FROM `Books` WHERE `isbn` = :isbn';
    $statement = $db->prepare($query);
    $statement->execute([
        "isbn" => $isbn 
    ]);
    $idImage = $statement->fetchColumn();
    if (isValidURL($coverURI)) {
        $headers = get_headers($coverURI, 1);
        $contentType = $headers["Content-Type"];
        
        // Check if it is a jpeg image
        // if ($contentType === "image/jpg") {
        file_put_contents("../images/" . $idImage . ".jpeg", fopen($coverURI, 'r')); // Save file with read permission
        // }
    } else {
        file_put_contents("../images/" . $idImage . ".jpeg", fopen('https://placehold.co/260x398.jpeg?text=' . urlencode(splitTitle($title)), 'r')); // Save file with read permission
    }

    // Commit the transaction
    $db->commit();
    echo json_encode('Executed');
} catch (Exception $e) {
    // Rollback the transaction if an error occurs
    $db->rollback();
    echo json_encode('Error: ' . $e->getMessage());
}

function isValidURL($url) {
    return (strpos($url, "https://books.google.com") === 0) || 
           (strpos($url, "https://covers.openlibrary.org") === 0);
}

function checkNull($arg) {
    if ($arg == "null" || $arg == "undefined") {
        return;
    } else {
        return $arg;
    }
}

function getClosest($array, $search, $currentPosition) {
    $closestIndex = null;
    $length = count($array);

    $leftParser = $currentPosition;
    $rightParser = $currentPosition;

    while (!$closestIndex && ($leftParser >= 0 || $rightParser <= $length)) {
        if ($leftParser >= 0) {
            $leftParser -= 1;
            if ($array[$leftParser] == $search) {
                $closestIndex = $leftParser;
            }
        } elseif (!$closestIndex && ($rightParser <= $length)) {
            $leftParser += 1;
            if ($array[$rightParser] == $search) {
                $closestIndex = $rightParser;
            }
        }
    }

    return $closestIndex;
}

function splitTitle($title) {
    $array = str_split($title);

    for ($i = 1; $i <= floor(count($array)/20); $i++) {
        $array[getClosest($array, ' ', 20*$i)] = '\n';
    }
    
    return implode('', $array);
}