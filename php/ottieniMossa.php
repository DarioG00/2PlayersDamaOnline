<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    include_once "configDB.php";
    include "dbConnection.php";

    if(isset($_POST['turno'])){

        $query = "SELECT *
                  FROM guidi_607453.mossa 
                  WHERE partita = ? AND turno = ? AND giocatore = ?
                  ORDER BY istanteMossa DESC
                  LIMIT 1";

        $statement = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($statement, "iis", $_SESSION['partita']['codice'], $_POST['turno'], $_SESSION['player-avversario']);

        if(mysqli_stmt_execute($statement)){

            mysqli_stmt_bind_result($statement, $turno, $partita, $istanteMossa, $giocatore, $lastX, $lastY, $newX, $newY, $eatX, $eatY);
            echo "{ \"mossa\": ";
            $tmp = false;
            $count = 0;
            while(mysqli_stmt_fetch($statement)){    
                if($istanteMossa != $_SESSION['ultima-mossa']){
                    $_SESSION['ultima-mossa'] = $istanteMossa;

                    echo "{\"turno\": ". $turno . ", ";
                    echo "\"partita\": ". $partita . ", ";
                    echo "\"istanteMossa\": \"". $istanteMossa . "\", ";
                    echo "\"giocatore\": \"". $giocatore . "\", ";
                    echo "\"lastX\": ". $lastX .", ";
                    echo "\"lastY\": ". $lastY .", ";
                    echo "\"newX\": ". $newX .", ";
                    echo "\"newY\": ". $newY .", ";
    
                    if(isset($eatX) && isset($eatY)){
                        echo "\"eatX\": ". $eatX .", ";
                        echo "\"eatY\": ". $eatY ."}";
                        
                        if($_SESSION['player-mittente'] == $_SESSION['player-avversario']){
                            $_SESSION['partita']['punteggioG1']++;
                        }else if($_SESSION['player-destinatario'] == $_SESSION['player-avversario']){
                            $_SESSION['partita']['punteggioG2']++;
                        }  
                    }else{
                        echo "\"eatX\": \"vuoto\", ";
                        echo "\"eatY\": \"vuoto\" }";
                    }
                    $tmp = true;
                }
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