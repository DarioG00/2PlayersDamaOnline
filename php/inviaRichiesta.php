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

    if (empty($_POST["player-destinatario"]) || $_POST["player-destinatario"] == $_SESSION["username"]){
        echo ("<script>alert('Errore: player non valido'); 
                        window.history.back();
                </script>");
        exit();
    }

    // Controllo se non sono già amici mittente e destinatario
    $query = "SELECT count(*)
              FROM guidi_607453.amicizia
              WHERE (mittente = ? AND destinatario = ?) OR (mittente = ? AND destinatario = ?)";
    $statement = mysqli_prepare($db_connection, $query);

    mysqli_stmt_bind_param($statement, "ssss", $_SESSION["username"], $_POST["player-destinatario"], $_POST["player-destinatario"], $_SESSION["username"]);
    mysqli_stmt_execute($statement);

    mysqli_stmt_bind_result($statement, $richieste);
    while(mysqli_stmt_fetch($statement)){
        if ($richieste > 0){
            // La query è fallita, c'è già un'altra richiesta
            mysqli_stmt_free_result($statement);
            mysqli_close($db_connection);
            echo ("<script>alert('Errore: la richiesta d\'amicizia a ". $_POST["player-destinatario"] ." è già stata effettuata');
                            window.history.back();
                    </script>");
            exit();
        }
    }
    
    mysqli_stmt_free_result($statement);

    // Eseguo richiesta amicizia
    $query = "INSERT INTO guidi_607453.amicizia VALUES (?,?,'sospesa')";

    $statement = mysqli_prepare($db_connection, $query);

    mysqli_stmt_bind_param($statement, "ss", $_SESSION["username"], $_POST["player-destinatario"]);

    if (!mysqli_stmt_execute($statement)){
        //La query è fallita
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: impossibile eseguire la richiesta d\'amicizia a ". $_POST["player-destinatario"] . "');
                        window.history.back();
                </script>");
        exit();
    }
    
    mysqli_stmt_free_result($statement);
    mysqli_close($db_connection);

    echo ("<script>alert('Richiesta d\'amicizia a ". $_POST["player-destinatario"] . " effettuata con successo');
                        window.history.back();
                </script>");
?>