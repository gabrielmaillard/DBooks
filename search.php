<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherchez</title>
    <link rel="stylesheet" href="css/fonts.css"> <!-- Import fonts -->
    <link rel="stylesheet" href="css/all.css"> <!-- Generic elements and classes -->
    <link rel="stylesheet" href="css/pages/search.css"> <!-- Page style -->
    <script src="js/pages/search.js" type="module" defer></script>
    <link rel="icon" href="images/DB-Logo.ico">
</head>
<body>
    <?php include 'menu.php' ?>
    <div id="header">
        <h1 id="page-title">Recherchez dans<br>votre Bibliothèque</h1>
        <form action="POST" id="search-form">
            <label for="title">Titre du livre</label>
            <input type="text" name="title" id="search-title" style="width: 200px" placeholder="Troubled blood">
            <button id="submit-search" class="submit">Rechercher <img src="emojis/🔍.svg" alt="Magnifying glass" srcset="" class="emoji"></button>
        </form>
    </div>
    <div id="results"></div>

    <template id="template-book-card">
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