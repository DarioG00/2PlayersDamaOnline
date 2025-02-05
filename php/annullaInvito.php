<?php
    session_start();
    include "configDB.php";
    include "dbConnection.php";

    if (empty($_SESSION["player-mittente"]) || empty($_SESSION["player-destinatario"])){
        echo ("<script>alert('Errore: players non settati'); 
                        window.history.back();
                </script>");
        exit();
    }

    $mittente = $_SESSION["player-mittente"];
    $destinatario = $_SESSION["player-destinatario"];
    unset($_SESSION["player-mittente"]);
    unset($_SESSION["player-destinatario"]);

    $query = "DELETE FROM guidi_607453.invitopartita WHERE mittente = ? AND destinatario = ?";

    $statement = mysqli_prepare($db_connection, $query);

    mysqli_stmt_bind_param($statement, "ss", $mittente, $destinatario);

    if (!mysqli_stmt_execute($statement)){
        //La query è fallita
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: impossibile annullare l'invito alla partita');
                        window.history.back();
                </script>");
        exit();
    }
    
    mysqli_stmt_free_result($statement);
    mysqli_close($db_connection);

    header('Location: ./menu.php');
?>