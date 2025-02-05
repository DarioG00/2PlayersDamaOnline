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
        echo ("<script>alert('Errore: nessuna azione richiesta per la richiesta d\'amicizia'); 
                        window.history.back();
                </script>");
        exit();
    }

    if(isset($_POST["accetta"])){
        $query = "UPDATE guidi_607453.amicizia SET stato = 'accettata' WHERE mittente = ? AND destinatario = ?";
        $mittente = $_POST["accetta"];
    }else{
        $query = "DELETE FROM guidi_607453.amicizia WHERE stato = 'sospesa' AND mittente = ? AND destinatario = ?";
        $mittente = $_POST["rifiuta"];
    }

    $statement = mysqli_prepare($db_connection, $query);

    mysqli_stmt_bind_param($statement, "ss", $mittente, $_SESSION["username"]);

    if (!mysqli_stmt_execute($statement)){
        //L'update è fallito
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: impossibile rifiutare o accettare l\'amicizia');
                        window.history.back();
                </script>");
        exit();
    }

    mysqli_stmt_free_result($statement);
    mysqli_close($db_connection);

    if(isset($_POST["rifiuta"])){
        echo ("<script>alert('Richiesta d\'amicizia di ".$mittente." rifiutata con successo');
                        window.history.back();
                </script>");
        exit();
    }else{
        echo ("<script>alert('Richiesta d\'amicizia di ".$mittente." accettata con successo');
                        window.history.back();
                </script>");
        exit();
    }
?>