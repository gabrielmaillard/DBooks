<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locations</title>
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/fonts.css"> <!-- Import fonts -->
    <link rel="stylesheet" href="css/header.css">
    <script type="module" src="js/pages/locations.js"></script>
    <link rel="icon" href="images/DB-Logo.ico">
</head>
<body>
    <div id="header">
        <h1 id="page-title">Créez des<br>Locations</h1>
        <form action="" id="form">
            <div class="group">
                <label for="name">Nom : </label>
                <input type="text" name="name" id="name" placeholder="i.e : Bibliothèque du salon">
                <input type="submit" value="Créer" style="display: inline-block;">
            </div>
        </form>
    </div>
    <table id="table">
        <thead>
            <tr>
                <th>Identifiant</th>
                <th>Nom</th>
                <!-- <th>Description</th> -->
            </tr>
        </thead>
        <tbody id="tbody">
            <?php
            require __DIR__ . '/dbLibrary/dbLibrary.php'; // Require the library for the database
            require __DIR__ . '/vars/default.php'; // Require the environment default variables
            
            $db = loadDB($DB_NAME);

            $statement = $db->prepare('
            SELECT *
            FROM Locations
            ');
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row) {
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</body>
</html>