<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    include_once "configDB.php";
    include "dbConnection.php";

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: " . $_SERVER['REQUEST_METHOD'] . " metodo non valido'); 
                        window.history.back();
                </script>");
        exit();
    }

    if (empty($_POST["player-cercato"]) || $_POST["player-cercato"] == $_SESSION["username"]){
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: player cercato non valido'); 
                        window.history.back();
                </script>");
        exit();
    }

    $playerValid = preg_match("/^[a-z0-9]{1,60}$/", $_POST["player-cercato"]);

    if(!$playerValid){
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: formato username player cercato non valido'); 
                    window.history.back();
            </script>");
        exit();
    }

    $query = "SELECT count(*) FROM guidi_607453.utente WHERE username = ?";

    $statement = mysqli_prepare($db_connection, $query);

    mysqli_stmt_bind_param($statement, "s", $_POST["player-cercato"]);

    if(mysqli_stmt_execute($statement)){
        mysqli_stmt_bind_result($statement, $player);

        while (mysqli_stmt_fetch($statement)){
            if(!$player){
                echo ("<script>alert('Errore: player non esistente');
                        window.history.back();
                        </script>");
                mysqli_stmt_free_result($statement);
                mysqli_close($db_connection);
                exit();
            }
        }
    }
    
    mysqli_stmt_free_result($statement);
    mysqli_close($db_connection);

    $_SESSION["player-cercato"] = $_POST["player-cercato"];

    header('Location: ./menu.php');
?>