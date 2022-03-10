<?php

function connectToDb()
{
    $dbServername = 'localhost';
    $dbUsername = 'admin';
    $dbPassword = '';
    $dbName = 'veroe-3-2';

    $dbConnection = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

    return $dbConnection;
}

function showTables()
{
    $dbConnection = connectToDb();
    $query = 'SHOW TABLES;';
    $stmt = mysqli_stmt_init($dbConnection);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        echo 'SQL failed <br>';
        return;
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $tables = $result->fetch_all();

    $tableLinks = [];
    foreach ($tables as $table) {
        echo
        "<form action='' method='POST'>
            <input type='submit' name='tableToShow' value='${table[0]}'>
        </form>";
    }
}

showTables();

// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';

// $tableToShow = $_POST['tableToShow'] ?? '';

// function showTable($table)
// {
//     echo 'showing table ' . $table;
//     $query = "SELECT * FROM $table;";
//     // $stmt = 
// }

// if (!empty($tableToShow)) {
//     showTable($tableToShow);
// }
