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

    if (empty($_POST["accetta"]) && empty($_POST["rifiuta"])){
        echo ("<script>alert('Errore: nessuna azione selezionata per rispondere all\'invito della partita'); 
                        window.history.back();
                </script>");
        exit();
    }

    if(isset($_POST["accetta"])){
        $query = "UPDATE guidi_607453.invitopartita SET stato = 'accettato' WHERE mittente = ? AND destinatario = ?";
        $_SESSION['player-mittente'] = $_POST["accetta"];
    }else{
        $query = "DELETE FROM guidi_607453.invitopartita WHERE stato = 'sospeso' AND mittente = ? AND destinatario = ?";
        $_SESSION['player-mittente'] = $_POST["rifiuta"];
    }
    
    $_SESSION['player-destinatario'] = $_SESSION['username'];

    $statement = mysqli_prepare($db_connection, $query);

    mysqli_stmt_bind_param($statement, "ss", $_SESSION['player-mittente'], $_SESSION['player-destinatario']);

    if (!mysqli_stmt_execute($statement)){
        //La query è fallita
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: impossibile rifiutare o accettare l\'invito');
                        window.history.back();
                </script>");
        exit();
    }
    
    mysqli_stmt_free_result($statement);

    if(isset($_POST["accetta"])){

        $_SESSION['player-avversario'] = $_SESSION['player-mittente'];

        // istanzio nuova partita
        $query = "INSERT INTO guidi_607453.partita (G1, G2) VALUES (?, ?)";
        $statement = mysqli_prepare($db_connection, $query);

        mysqli_stmt_bind_param($statement, "ss", $_SESSION['player-mittente'], $_SESSION['player-destinatario']);

        if (!mysqli_stmt_execute($statement)){
            //La query è fallita
            mysqli_stmt_free_result($statement);
            mysqli_close($db_connection);
            echo ("<script>alert('Errore: impossibile istanziare nuova partita');
                            window.history.back();
                    </script>");
            exit();
        }
        
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);

        header('Location: ./entraPartita.php');
    }else if(isset($_POST["rifiuta"])){
        mysqli_close($db_connection);
        unset($_SESSION['player-mittente']);
        unset($_SESSION['player-destinatario']);
        echo ("<script>alert('Invito alla partita da ". $_POST["rifiuta"] ." rifiutato con successo');
                        window.history.back();
                </script>");
        exit();
    } 
?>