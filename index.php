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

function showTables(): string
{
    $dbConnection = connectToDb();
    $query = 'SHOW TABLES;';
    $stmt = mysqli_stmt_init($dbConnection);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        echo 'SQL failed <br>';
        return '';
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $tables = $result->fetch_all();

    mysqli_stmt_close($stmt);
    mysqli_close($dbConnection);

    $tableLinks = '';
    foreach ($tables as $table) {
        $tableLinks .=
            "<form action='' method='POST'>
                <input type='submit' name='tableToShow' value='${table[0]}'>
            </form>";
    }

    return $tableLinks;
}

$tableLinks = showTables();

// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';

$tableToShow = $_POST['tableToShow'] ?? '';

function showTable($table): string
{
    $dbConnection = connectToDb();
    $query = "SELECT * FROM ${table};";
    $stmt = mysqli_stmt_init($dbConnection);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        echo 'SQL failed <br>';
        return '';
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($dbConnection);

    $first = true;
    $htmlTable = '';
    $htmlTable .= "<h3>table: ${table}</h3>";
    $htmlTable .= '<table>';
    while ($rows = $result->fetch_assoc()) {
        if ($first) {
            $htmlTable .= '<tr>';
            foreach ($rows as $key => $value) {
                $htmlTable .= "<th>${key}</th>";
            }
            $htmlTable .= '</tr>';

            $first = false;
        }
        $htmlTable .= '<tr>';
        foreach ($rows as $key => $value) {
            $htmlTable .= "<td>${value}</td>";
        }
        $htmlTable .= '</tr>';
    }
    $htmlTable .= '</table>';

    return $htmlTable;
}

if (!empty($tableToShow)) {
    $htmlTable = showTable($tableToShow);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <h3>database tables:</h3>
    <?php if (!empty($tableLinks)) : ?>
        <?= $tableLinks ?>
    <?php endif ?>

    <hr>

    <?php if (!empty($htmlTable)) : ?>
        <?= $htmlTable ?>
    <?php endif ?>

</body>

</html>