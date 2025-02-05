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
        echo ("<script>alert('Errore: player invitato non valido'); 
                        window.history.back();
                </script>");
        exit();
    }

    // controllo se il player invitato è tra gli amici dell'utente loggato
    $query = "SELECT count(*)
              FROM guidi_607453.amicizia
              WHERE ((mittente = ? AND destinatario = ?) OR (mittente = ? AND destinatario = ?))
              AND stato = 'accettata'";
    $statement = mysqli_prepare($db_connection, $query);

    mysqli_stmt_bind_param($statement, "ssss", $_SESSION["username"], $_POST["player-destinatario"], $_POST["player-destinatario"], $_SESSION["username"]);
    mysqli_stmt_execute($statement);

    mysqli_stmt_bind_result($statement, $amico);
    while(mysqli_stmt_fetch($statement)){
        if (!$amico){
            //La query è fallita, il player invitato non è tra gli amici dell'utente loggato
            mysqli_stmt_free_result($statement);
            mysqli_close($db_connection);
            echo ("<script>alert('Errore: il player ". $_POST["player-destinatario"] . " non è tra i tuoi amici');
                            window.history.back();
                    </script>");
            exit();
        }
    }
    
    mysqli_stmt_free_result($statement);

    // controllo se non ci sono già altri inviti in sospeso
    $query = "SELECT count(*)
              FROM guidi_607453.invitopartita
              WHERE ((mittente = ? AND destinatario = ?) OR (mittente = ? AND destinatario = ?))
              AND stato = 'sospeso'";
    $statement = mysqli_prepare($db_connection, $query);

    mysqli_stmt_bind_param($statement, "ssss", $_SESSION["username"], $_POST["player-destinatario"], $_POST["player-destinatario"], $_SESSION["username"]);
    mysqli_stmt_execute($statement);

    mysqli_stmt_bind_result($statement, $inviti);
    while(mysqli_stmt_fetch($statement)){
        if ($inviti > 0){
            //La query è fallita, c'è già un altro invito in sospeso
            mysqli_stmt_free_result($statement);
            mysqli_close($db_connection);
            echo ("<script>alert('Errore: impossibile, l\'invito a ". $_POST["player-destinatario"] . " è già stato richiesto');
                            window.history.back();
                    </script>");
            exit();
        }
    }
    
    mysqli_stmt_free_result($statement);

    // eseguo l'invito alla partita
    $query = "INSERT INTO guidi_607453.invitopartita VALUES (?,?, CURRENT_TIMESTAMP, 'sospeso')";

    $statement = mysqli_prepare($db_connection, $query);

    mysqli_stmt_bind_param($statement, "ss", $_SESSION["username"], $_POST["player-destinatario"]);

    if (!mysqli_stmt_execute($statement)){
        //La query è fallita, errore nell'inserimento di un invito
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: impossibile effettuare l\'invito alla partita');
                        window.history.back();
                </script>");
        exit();
    }
    
    mysqli_stmt_free_result($statement);

    mysqli_close($db_connection);

    $_SESSION['player-mittente'] = $_SESSION['username'];
    $_SESSION['player-destinatario'] = $_POST['player-destinatario'];

    $_SESSION['player-avversario'] = $_POST['player-destinatario'];

    header('Location: ./attesaPartita.php');
?>