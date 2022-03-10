<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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

    mysqli_stmt_close($stmt);
    mysqli_close($dbConnection);
}

showTables();

// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';

$tableToShow = $_POST['tableToShow'] ?? '';

function showTable($table)
{
    $dbConnection = connectToDb();
    $query = "SELECT * FROM ${table};";
    $stmt = mysqli_stmt_init($dbConnection);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        echo 'SQL failed <br>';
        return;
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $first = true;
    echo "<h3>table: ${table}<h3>";
    echo '<table>';
    while ($rows = $result->fetch_assoc()) {
        if ($first) {
            echo '<tr>';
            foreach ($rows as $key => $value) {
                echo "<th>${key}</th>";
            }
            echo '</tr>';

            $first = false;
        }
        echo '<tr>';
        foreach ($rows as $key => $value) {
            echo "<td>${value}</td>";
        }
        echo '</tr>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($dbConnection);
}

if (!empty($tableToShow)) {
    showTable($tableToShow);
}
