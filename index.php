<?php
require __DIR__ . '/dbLibrary/dbLibrary.php'; // Require the library for the database
require __DIR__ . '/vars/default.php'; // Require the environment default variables

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

$db = loadDB($DB_NAME);

$onBook = false;

if (isset($_GET["isbn"])) {
    // CHECK IF BOOK EXISTS
    $isbn = $_GET["isbn"];

    $query = 'SELECT EXISTS(SELECT `isbn` FROM `Books` WHERE `isbn` = :isbn)';
    $statement = $db->prepare($query);
    $statement->execute([
        'isbn' => $isbn
    ]);
    $exists = $statement->fetch()[0];

    if(!$exists) {
        header('Location: index');
    }

    $onBook = true;
}
?>
<!-- addDB -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajoutez</title>
    <script type="module" src="js/pages/addDB.js"></script>
    <script type="module" src="js/classes/Book.js"></script>
    <script type="module" src="vars/Tools.js"></script>
    <link rel="stylesheet" href="css/all.css"> <!-- Generic elements and classes -->
    <link rel="stylesheet" href="css/fonts.css"> <!-- Import fonts -->
    <link rel="stylesheet" href="css/pages/addDB.css"> <!-- Import page CSS and templates CSS -->
    <link rel="icon" href="images/DB-Logo.ico">
</head>
<body>
    <?php include 'menu.php' ?>
    <div id="header">
        <h1 id="page-title">Ajoutez dans<br>votre Bibliothèque</h1>
        <form action="" id="form">
            <div class="isbn10-group">
                <label for="isbn10">Code ISBN 10 : </label>
                <input type="text" name="isbn10" id="isbn10" placeholder="i.e.: 2266080555">
            </div>
            <div class="isbn13-group">
                <label for="isbn13">Code ISBN 13 : </label>
                <input type="text" name="isbn13" id="isbn13" placeholder="i.e: 9782837628163">
            </div>
        </form>
    </div>
    <div id="result">
    <?php
    if ($onBook) {
        ?>
        <script type="module">
            import { Book } from './js/classes/Book';
            import { key } from './vars/Tools';
            const book = new Book(<?= $isbn ?>, key);
            await book.useDatasD();
            document.querySelector('#result').append(book.getFullPage());
        </script>
        <?php
    }
    ?>
    </div>

    <template id="template-book-full-page">
        <!-- <div class="modal" id="modal-add-book">
            <div class="inner-modal">
                <h2>Choisissez votre (vos) location(s)</h2>
                <div class="selecteur">
                </div>
            </div>
        </div> -->
        <div class="left-card">
            <div class="cover">
                <img src="" alt="" class="cover-image">
            </div>
            <div class="btns">
                <button class="primary" id="add-to-db">Ajouter à la Bibliothèque <img class="emoji" src="emojis/📄.svg" alt="Red book emoji" srcset=""></button>
                <button class="add-list secondary" style="display: none;">Ajouter aux envies <img class="emoji" src="emojis/🕘.svg" alt="Red book emoji" srcset=""></button>
            </div>
        </div>
        <div class="about">
            <div class="main">
                <div class="main-header">
                    <h1 id="title"><!-- A AJOUTER <img class="emoji" src="emojis/📕.svg" alt="Red book emoji" srcset="">--></h1>
                    <h5 id="authors"></h5>
                </div>
                <p class="text" id="description"></p>
                <div class="notice" style="display: none;">
                    <h1>Parce que votre avis compte</h1>
                </div>
            </div>
            <div class="additional-data">
                <div id="infos">
                    <h1>Infos <img class="emoji" src="emojis/📢.svg" alt="Red book emoji" srcset=""></h1>
                    <ul id="infos-list">
                        <li class="info">Date de publication : <span id="published-date"></span></li>
                        <li class="info">Maison d'édition : <span id="publisher"></span></li>
                        <li class="info">Nombre de pages : <span id="page-count"></span></li>
                        <li class="info">ISBN : <span id="isbn"></span></li>
                        <li class="info">Langue : <span id="language"></span></li>
                        <li class="info">Sujet(s) : <span id="subjects"></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </template>
</body>
</html>

<style>

body {
    position: relative;
}

#loader-wrapper {
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    z-index: 5;
}
#loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
