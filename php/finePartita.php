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

    // notifico che la partita è stata abbandonata
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
        echo ("<script>alert('Errore: impossibile rimuovere l'invito alla partita');
                        window.history.back();
                </script>");
        exit();
    }
    
    mysqli_stmt_free_result($statement);
    
    // aggiorno il vincitore della partita
    $partita = $_SESSION['partita']['codice'];
    unset($_SESSION['partita']['codice']);

    if($_SESSION['partita']['punteggioG1'] > $_SESSION['partita']['punteggioG2']){
        $vincitore = $mittente;
    }else{
        $vincitore = $destinatario;
    }
    unset($_SESSION['partita']['punteggioG1']);
    unset($_SESSION['partita']['punteggioG2']);

    $query = "UPDATE guidi_607453.partita SET vincitore = ? WHERE codice = ?";
    $statement = mysqli_prepare($db_connection, $query);
    mysqli_stmt_bind_param($statement, "si", $vincitore, $partita);

    if (!mysqli_stmt_execute($statement)){
        //La query è fallita
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: impossibile aggiornare il vincitore della partita');
                        window.history.back();
                </script>");
        exit();
    }
    
    mysqli_stmt_free_result($statement);

    // pulizia dei messaggi della partita
    $query = "DELETE FROM guidi_607453.messaggio 
              WHERE partita = ?";
    $statement = mysqli_prepare($db_connection, $query);
    mysqli_stmt_bind_param($statement, "i", $partita);
    if(!mysqli_stmt_execute($statement)){
        //La query è fallita, errore nella cancellazione messaggi letti
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: impossibile fare la pulizia dei messaggi');
                        window.history.back();
                </script>");
        exit();
    }

    mysqli_stmt_free_result($statement);

    mysqli_close($db_connection);
    
    unset($_SESSION['player-avversario']);
    unset($_SESSION['partita']['turno-player']);
    unset($_SESSION['ultima-mossa']);

    header('Location: ./menu.php');
?>