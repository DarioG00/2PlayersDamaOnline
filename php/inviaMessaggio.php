<?php
    session_start();
    include "configDB.php";
    include "dbConnection.php";

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: " . $_SERVER['REQUEST_METHOD'] . " metodo non valido'); 
                        window.history.back();
                </script>");
        exit();
    }

    if (isset($_POST["messaggio"])){

        $messageValid = preg_match("/^.{0,200}$/", $_POST["messaggio"]);

        if(!$messageValid){
            mysqli_close($db_connection);
            echo ("<script>alert('Errore: contenuto messaggio troppo lungo (max 200 caratteri)'); 
                        window.history.back();
                </script>");
            exit();
        }

        // eseguo invio messaggio
        $query = "INSERT INTO guidi_607453.messaggio VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?)";

        $statement = mysqli_prepare($db_connection, $query);

        mysqli_stmt_bind_param($statement, "isss", $_SESSION['partita']['codice'], $_SESSION["username"], $_SESSION["player-avversario"], $_POST['messaggio']);

        if (!mysqli_stmt_execute($statement)){
            //La query è fallita, errore nell'inserimento di un messaggio
            mysqli_stmt_free_result($statement);
            mysqli_close($db_connection);
            echo ("<script>alert('Errore: impossibile effettuare l\'invio messaggio');
                            window.history.back();
                    </script>");
            exit();
        }
        
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
    }else{
        
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: non è possibile fare l\'invio messaggio'); 
                        window.history.back();
                </script>");
        exit();
    }

    
?>