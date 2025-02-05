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

    if (isset($_POST['turno']) && isset($_POST["lastX"]) && isset($_POST["lastY"]) && isset($_POST["newX"]) && isset($_POST["newY"])){
        // eseguo invio mossa
        if(isset($_POST["eatX"]) && isset($_POST["eatY"])){
            $query = "INSERT INTO guidi_607453.mossa (Turno, Partita, IstanteMossa, Giocatore, lastX, lastY, newX, newY, eatX, eatY) 
                    VALUES (?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?)";

            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "iisiiiiii", $_POST['turno'], $_SESSION['partita']['codice'], $_SESSION["username"], 
                                                $_POST["lastX"], $_POST["lastY"], $_POST["newX"], $_POST["newY"], $_POST["eatX"], $_POST["eatY"]);
        
        }else{
            $query = "INSERT INTO guidi_607453.mossa (Turno, Partita, IstanteMossa, Giocatore, lastX, lastY, newX, newY) 
                    VALUES (?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?)";

            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "iisiiii", $_POST['turno'], $_SESSION['partita']['codice'], $_SESSION["username"], 
                                                $_POST["lastX"], $_POST["lastY"], $_POST["newX"], $_POST["newY"]);
        }

        if (!mysqli_stmt_execute($statement)){
            //La query è fallita, errore nell'inserimento di un invito
            mysqli_stmt_free_result($statement);
            mysqli_close($db_connection);
            echo ("<script>alert('Errore: impossibile effettuare l\'invio mossa');
                            window.history.back();
                    </script>");
            exit();
        }
        
        mysqli_stmt_free_result($statement);

        // aggiorno punti
        if(isset($_POST["eatX"]) && isset($_POST["eatY"])){

            if($_SESSION['player-mittente'] == $_SESSION['username']){
                $query = "UPDATE guidi_607453.partita SET punteggioG1 = ? WHERE codice = ?";
                $punteggio = ++$_SESSION['partita']['punteggioG1'];
            }else if($_SESSION['player-destinatario'] == $_SESSION['username']){
                $query = "UPDATE guidi_607453.partita SET punteggioG2 = ? WHERE codice = ?";
                $punteggio = ++$_SESSION['partita']['punteggioG2'];
            }        

            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "ii", $punteggio, $_SESSION["username"]);
            mysqli_stmt_free_result($statement);    
        }

        mysqli_close($db_connection);
    }else{
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: non è possibile fare l\'invio mossa'); 
                        window.history.back();
                </script>");
        exit();
    }

    
?>