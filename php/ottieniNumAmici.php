<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    include_once "configDB.php";
    include "dbConnection.php";

    // Otteniamo il numero di amici dell'utente loggato
    $query = "SELECT count(*) FROM guidi_607453.amicizia WHERE (mittente = ? OR destinatario = ?) AND stato = 'accettata'";
    $statement = mysqli_prepare($db_connection, $query);
    mysqli_stmt_bind_param($statement, "ss", $_SESSION["username"], $_SESSION["username"]);
    if(mysqli_stmt_execute($statement)){
        mysqli_stmt_bind_result($statement, $amici);
        while(mysqli_stmt_fetch($statement)){
            echo "{\"amici\": ". $amici . "}";
        }
    }

    mysqli_stmt_free_result($statement);
    mysqli_close($db_connection);
?>