<?php
require __DIR__ . '/dbLibrary/dbLibrary.php'; // Require the library for the database
require __DIR__ . '/vars/default.php'; // Require the environment default variables

$db = loadDB($DB_NAME);

// Get all books
$query = '
SELECT
Books.isbn,
Books.title,
Books.publisherName,
Books.idImage,
Books.description,
Authors.name AS authorName
FROM Books
INNER JOIN Books_authors
ON Books_authors.idBook = Books.isbn
INNER JOIN Authors
ON Authors.id = Books_authors.idAuthor
';
$statement = $db->prepare($query);
$statement->execute();

$results = $statement->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

function generateSentenceWithElements($elements, $start, $separator, $lastSeparator = null, $end = null) {
    if (count($elements) > 1) {
        foreach (array_slice($elements, 0, -1) as $element) {
            $start .= ($element . $separator);
        }

        $start .= ($lastSeparator ? $lastSeparator : $separator) . end($elements) . ($end ? $end : "");

        return $start;
    }
    
    return $start . $elements[0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisez</title>
    <link rel="stylesheet" href="css/fonts.css"> <!-- Import fonts -->
    <link rel="stylesheet" href="css/all.css"> <!-- Import generic elements and classes styles -->
    <link rel="stylesheet" href="css/pages/visualize.css"> <!-- Import page style -->
    <link rel="icon" href="images/DB-Logo.ico">
</head>
<body>
    <?php include 'menu.php' ?>
    <div id="header">
        <h1 id="page-title">Visualisez<br>votre Bibliothèque</h1>
    </div>
    <div id="results">
        <?php
        foreach ($results as $isbn => $datas) {
            $title = $datas[0]["title"];
            $publisher = $datas[0]["publisherName"];
            $idImage = $datas[0]["idImage"];
            $descriptionExtract = substr($datas[0]["description"], 0, 270);
            $authorsNames = [];
            foreach($datas as $authorDatas) {
                $authorsNames[] = $authorDatas["authorName"];
            }
            ?>
            <div class="book-card" onclick="window.location.href='index?isbn='+<?= $isbn ?>">
            <div class="left-part">
                <img src="images/<?= $idImage ?>.jpeg" alt="Book cover" srcset="">
            </div>
            <div class="right-part">
                <div class="header">
                    <h1><?= $title ?></h1>
                    <h5><?= generateSentenceWithElements($authorsNames, "écrit par ", ", ", "et ") ?></h5>
                </div>
                <p><?= $descriptionExtract ?>...</p>
            </div>
            </div>
            <?php
        }
        ?>
    </div>

    <template id="book-card-small">
        <div class="book-card">
            <div class="left-part">
                <img src="" alt="Book cover" srcset="" class="cover">
            </div>
            <div class="right-part">
                <div class="header">
                    <h1 class="title"></h1>
                    <h5 class="authors"></h5>
                </div>
                <p class="description"></p>
            </div>
        </div>
    </template>
</body>
</html>