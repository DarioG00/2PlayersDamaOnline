<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    include_once "configDB.php";
    include "dbConnection.php";

    //Otteniamo le vittorie dell'utente loggato
    $query = "SELECT count(*) FROM guidi_607453.partita WHERE vincitore = ?";
    $statement = mysqli_prepare($db_connection, $query);
    mysqli_stmt_bind_param($statement, "s", $_SESSION["username"]);
    mysqli_stmt_execute($statement);

    if(mysqli_stmt_bind_result($statement, $vittorie)){
        while(mysqli_stmt_fetch($statement)){
            echo "{\"vittorie\": ". $vittorie . "}";
        }
    }

    mysqli_stmt_free_result($statement);
    mysqli_close($db_connection);
?>