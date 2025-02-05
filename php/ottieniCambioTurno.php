<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    include_once "configDB.php";
    include "dbConnection.php";

    if(isset($_POST['turno'])){

        $query = "SELECT *
                  FROM guidi_607453.cambioturno 
                  WHERE turno = ? AND partita = ?";

        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ii", $_POST['turno'], $_SESSION['partita']['codice']);

        if(mysqli_stmt_execute($statement)){

            mysqli_stmt_bind_result($statement, $turno, $partita);
            echo "{ \"cambio\": ";
            $tmp = false;
            while(mysqli_stmt_fetch($statement)){
                echo "{\"turno\": ". $turno . ",";
                echo "\"partita\": ". $partita . " }";
                $tmp = true;
            }
            if(!$tmp){
                echo "\"vuoto\"";
            }
            echo "}";
        }
        
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        $_SESSION['partita']['turno-player'] = $_SESSION['player-avversario'];
        exit();
    }
    
    mysqli_close($db_connection);
    echo ("<script>alert('Errore: lettura cambio turno'); 
                    window.history.back();
            </script>");
    exit();
?>