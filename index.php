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

    $firstRow = true;
    $htmlTable = "<h3>table: ${table}</h3>";
    $htmlTable .= '<table>';
    while ($rows = $result->fetch_assoc()) {
        // create header row
        if ($firstRow) {
            $htmlTable .= '<tr>';
            foreach ($rows as $key => $value) {
                $htmlTable .= "<th>${key}</th>";
            }
            $htmlTable .= '</tr>';

            $firstRow = false;
        }

        // create rows
        $htmlTable .= '<tr>';
        foreach ($rows as $key => $value) {
            // save id to use with delete button
            if ($key === 'id') {
                $id = $value;
            }
            $htmlTable .= "<td>${value}</td>";
        }
        // add delete button to end of row in learners table
        if ($table === 'learners') {
            $htmlTable .= '
                <td>
                    <form action="" method="POST">
                        <button type="submit" name="deleteLearner" value="' . $id . '">ⓧ</button>
                    </form>
                </td>';
            $htmlTable .= '</tr>';
        }
    }
    $htmlTable .= '</table>';

    return $htmlTable;
}

$tableToShow = $_POST['tableToShow'] ?? '';

if (!empty($tableToShow)) {
    $htmlTable = showTable($tableToShow);
}


function insertLearner()
{
    $dbConnection = connectToDb();

    $groupID = $_POST['group_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $active = isset($_POST['active']) ? true : false;

    $query = 'INSERT INTO learners (group_id, name, email, active)
    VALUES (?, ?, ?, ?);';
    $stmt = mysqli_stmt_init($dbConnection);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        echo 'SQL failed';
        return;
    }
    mysqli_stmt_bind_param($stmt, 'issi', $groupID, $name, $email, $active);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($dbConnection);
}

if (key_exists('insertLearner', $_POST)) {
    insertLearner();
}


function deleteLearner()
{
    $dbConnection = connectToDb();

    $learnerID = $_POST['deleteLearner'];

    $query = 'DELETE FROM learners WHERE id = ?;';
    $stmt = mysqli_stmt_init($dbConnection);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        echo 'SQL failed';
        return;
    }
    mysqli_stmt_bind_param($stmt, 'i', $learnerID);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($dbConnection);
}

if (key_exists('deleteLearner', $_POST)) {
    deleteLearner();
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

    <form action="" method="POST">
        <h3>insert learner</h3>
        <p>
            <label for="group_id">group id</label>
            <input type="number" id="group_id" name="group_id" value="<?= $_POST['group_id'] ?? '' ?>">
        </p>
        <p>
            <label for="name">name</label>
            <input type="text" id="name" name="name" value="<?= $_POST['name'] ?? '' ?>">
        </p>
        <p>
            <label for="email">email</label>
            <input type="email" id="email" name="email" value="<?= $_POST['email'] ?? '' ?>">
        </p>
        <p>
            <label for="active">active</label>
            <input type="checkbox" id="active" name="active" <?= isset($_POST['active']) ? "checked" : '' ?>>
        </p>
        <input type="submit" name="insertLearner">
    </form>

    <!-- <form action="" method="POST">
        <h3>delete learner</h3>
        <p>
            <label for="id">learner id</label>
            <input type="number" id="id" name="id">
            <input type="submit" value="delete" name="deleteLearner">
        </p>
    </form> -->

</body>

</html>