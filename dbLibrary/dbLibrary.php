<?php

function newPDO($db_name) {
    // Function to create a new pdo to a given database
    $db = new PDO('mysql:host=localhost;dbname=' . $db_name . ';charset=utf8', 'root', '');

    return $db;
}

function tryConnection($db_name) {
    try
    {
        $db = newPDO($db_name);
    }
    catch (Exception $e)
    {
        return false;
    }

    return $db;
}

function loadDB($db_name) {
    $db = tryConnection($db_name);
    if (!$db)
    {
        ?>
        <script>
            window.stop();
        </script>
        <?php
    }
    else
    {
        return $db;
    }
}