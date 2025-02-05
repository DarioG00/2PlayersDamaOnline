<?php
    session_start();
    include_once "./configDB.php";
    include "./dbConnection.php";

    if (empty($_SESSION["player-mittente"]) || empty($_SESSION["player-destinatario"])){
        echo ("<script>alert('Errore: players non settati'); 
                        window.history.back();
                </script>");
        exit();
    }

    // ottieni il codice della partita che sta per cominciare
    $query = "SELECT codice
              FROM guidi_607453.partita
              WHERE g1 = ? AND g2 = ?
              ORDER BY istantepartita DESC
              LIMIT 1";
  
    $statement = mysqli_prepare($db_connection, $query);

    mysqli_stmt_bind_param($statement, "ss", $_SESSION["player-mittente"], $_SESSION["player-destinatario"]);

    if (!mysqli_stmt_execute($statement)){
        //La query è fallita
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: impossibile entrare nella partita');
                        window.location.href = './abbandonaPartita.php';
                </script>");
        exit();
    }else{
        mysqli_stmt_bind_result($statement, $codice);
        while(mysqli_stmt_fetch($statement)){
            $_SESSION['partita']['codice'] = $codice;
        }
    }
    
    mysqli_stmt_free_result($statement);
    mysqli_close($db_connection);

    $_SESSION['partita']['punteggioG1'] = 0;
    $_SESSION['partita']['punteggioG2'] = 0;
    $_SESSION['ultima-mossa'] = 0;
    $_SESSION['partita']['turno-player'] = $_SESSION['player-destinatario'];

    header('Location: ./partita.php');
?>