<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    include_once "configDB.php";
    include "dbConnection.php";

    if(isset($_SESSION['username']) && isset($_SESSION['player-avversario']) && isset($_SESSION['partita']['codice'])){

        $query = "SELECT * 
                  FROM guidi_607453.messaggio 
                  WHERE partita = ?
                  ORDER BY istanteMessaggio";

        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "i", $_SESSION['partita']['codice']);
        if(mysqli_stmt_execute($statement)){

            mysqli_stmt_bind_result($statement, $partita, $mittente, $destinatario, $istante, $contenuto);
            echo "{ \"messaggi\": ";
            $tmp = false;
            $count = 0;
            while(mysqli_stmt_fetch($statement)){
                if($count == 0) echo "[ ";
                if($count > 0) echo ", ";

                echo "{ \"partita\": ". $partita . ", ";
                echo "\"mittente\": \"". $mittente . "\", ";
                echo "\"destinatario\": \"". $destinatario . "\", ";
                echo "\"istanteMessaggio\": \"". $istante ."\", ";
                echo "\"contenuto\": \"". $contenuto ."\" }";

                $tmp = true;
                $count++;
            }
            if(!$tmp){
                echo "\"vuoto\" }";
            }else{
                echo "]}";
            }

        }

        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        exit();
    }

    mysqli_close($db_connection);
    echo ("<script>alert('Errore: campi richieste nell\'invio messaggio non settati');
                    window.history.back();
            </script>");
    exit();
?>