<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    include_once "configDB.php";
    include "dbConnection.php";

    if(isset($_SESSION['player-mittente']) && isset($_SESSION['player-destinatario'])){

        $query = "SELECT * 
                  FROM guidi_607453.invitopartita 
                  WHERE mittente = ? AND destinatario = ?";

        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "ss", $_SESSION['player-mittente'], $_SESSION['player-destinatario']);
        if(mysqli_stmt_execute($statement)){

            mysqli_stmt_bind_result($statement, $mittente, $destinatario, $istante, $stato);
            echo "{ \"invitoPartita\": ";
            $tmp = false;
            while(mysqli_stmt_fetch($statement)){
                echo "{\"mittente\": \"". $mittente . "\",";
                echo "\"destinatario\": \"". $destinatario . "\",";
                echo "\"istante\": \"". $istante . "\",";
                echo "\"stato\": \"". $stato ."\"}";
                $tmp = true;
            }
            if(!$tmp){
                echo "\"vuoto\"";
            }
            echo "}";
        }
        
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        exit();
    }
    
    mysqli_close($db_connection);
    echo ("<script>alert('Errore: turno non settato');
                    window.history.back();
            </script>");
    exit();
?>