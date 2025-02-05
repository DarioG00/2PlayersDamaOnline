<?php
    include "./configDB.php";
    include "./dbConnection.php";

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: " . $_SERVER['REQUEST_METHOD'] . " metodo non valido'); 
                        window.history.back();
                </script>");
        exit();
    }

    if (empty($_POST["username"])){
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: il nome utente non può essere vuoto'); 
                        window.history.back();
                </script>");
        exit();
    }

    if (empty($_POST["password"])){
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: la password non può essere vuota'); 
                        window.history.back();
                </script>");
        exit();
    }

    if (empty($_POST["password-conferma"])){
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: la password di conferma non può essere vuota'); 
                        window.history.back();
                </script>");
        exit();
    }

    if ($_POST["password-conferma"] != $_POST["password"]){
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: la password di conferma errata'); 
                        window.history.back();
                </script>");
        exit();
    }

    $usernameValid = preg_match("/^[a-z0-9]{1,60}$/", $_POST["username"]);
    $passwordValid = preg_match("/^[A-Za-z0-9!@#$%^&*]{8,16}$/", $_POST["password"]);

    if(!$usernameValid || !$passwordValid){
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: formato username o password non validi'); 
                    window.history.back();
            </script>");
        exit();
    }

    //Dobbiamo registrare l'utente nel sistema
    $hash =  password_hash($_POST["password"], PASSWORD_BCRYPT);
    $query = "INSERT INTO guidi_607453.utente (username, hash) VALUES (?,?)";
    $statement = mysqli_prepare($db_connection, $query);
    mysqli_stmt_bind_param($statement, "ss", $_POST["username"], $hash);

    if (!mysqli_stmt_execute($statement)){
        //La query è fallita -> l'utente esiste gia
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: username già esistente');
                        window.history.back();
                </script>");
        exit();
    }else{
        mysqli_stmt_free_result($statement);
        mysqli_close($db_connection);

        echo ("<script>alert('Registrazione utente effettuata con successo');
                </script>");
        header('Location: ../index.html');
        exit();
    }

    mysqli_stmt_free_result($statement);
    mysqli_close($db_connection);

?>