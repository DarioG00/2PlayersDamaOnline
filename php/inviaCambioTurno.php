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

    if (isset($_POST['turno'])){
        // eseguo invio cambio turno
        $query = "INSERT INTO guidi_607453.cambioturno VALUES (?, ?)";

        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ii", $_SESSION['partita']['codice'], $_POST['turno']);

        if (!mysqli_stmt_execute($statement)){
            //La query è fallita, errore nell'inserimento del cambio turno
            mysqli_stmt_free_result($statement);
            mysqli_close($db_connection);
            echo ("<script>alert('Errore: impossibile effettuare il cambio turno');
                            window.history.back();
                    </script>");
            exit();
        }
        
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        
        $_SESSION['partita']['turno-player'] = $_SESSION['player-avversario'];
    }else{
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: messaggio non valido'); 
                        window.history.back();
                </script>");
        exit();
    }
?>