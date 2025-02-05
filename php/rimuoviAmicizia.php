<?php
    session_start();
    include "configDB.php";
    include "dbConnection.php";

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        echo ("<script>alert('Errore: " . $_SERVER['REQUEST_METHOD'] . " metodo non valido'); 
                        window.history.back();
                </script>");
        exit();
    }

    if (empty($_POST["player-rimosso"])){
        echo ("<script>alert('Errore: impossibile rimuovere l\'amico selezionato'); 
                        window.history.back();
                </script>");
        exit();
    }

    $query = "DELETE FROM guidi_607453.amicizia
              WHERE stato = 'accettata' 
              AND (mittente = ? OR destinatario = ?) AND (mittente = ? OR destinatario = ?)";
    $statement = mysqli_prepare($db_connection, $query);
    mysqli_stmt_bind_param($statement, "ssss", $_POST["player-rimosso"], $_POST["player-rimosso"], $_SESSION["username"], $_SESSION["username"]);

    if (!mysqli_stmt_execute($statement)){
        //L'update è fallito
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: impossibile rimuovere l\'amicizia con ". $_POST["player-rimosso"] . "');
                        window.history.back();
                </script>");
        exit();
    }

    mysqli_stmt_free_result($statement);
    mysqli_close($db_connection);

    echo ("<script>alert('Rimozione amicizia con ". $_POST["player-rimosso"] ." effettuata con successo');
                    window.history.back();
            </script>");
?>