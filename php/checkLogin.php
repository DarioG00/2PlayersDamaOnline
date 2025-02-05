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

    $usernameValid = preg_match("/^[a-z0-9]{1,60}$/", $_POST["username"]);
    $passwordValid = preg_match("/^[A-Za-z0-9!@#$%^&*]{8,16}$/", $_POST["password"]);

    if(!$usernameValid || !$passwordValid){
        mysqli_close($db_connection);
        echo ("<script>alert('Errore: formato username o password non validi'); 
                    window.history.back();
            </script>");
        exit();
    }

    //Dobbiamo loggare l'utente nel sistema
    $query = "SELECT hash FROM guidi_607453.utente WHERE username = ?";
    $statement = mysqli_prepare($db_connection, $query);
    mysqli_stmt_bind_param($statement, "s", $_POST["username"]);
    mysqli_stmt_execute($statement);

    // Binding del risultato alla variabile $hash
    mysqli_stmt_bind_result($statement, $hash);
    while (mysqli_stmt_fetch($statement)){
        if (password_verify($_POST["password"], $hash)) {
            // Se corretto, creaiamo la sessione, settiamo il campo login
            // e salviamo i dati forniti dall'utente
            session_create_id();
            session_start();
            
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["password"] = $_POST["password"];
            $_SESSION["login"] = "SI";

            mysqli_stmt_free_result($statement);
            mysqli_close($db_connection);

            header('Location: ./menu.php');
            exit();
        }
    }

    
    // Se non corretto, settiamo la sessione a NO
    session_start();
    $_SESSION["login"] = "NO";
    echo ("<script>alert('Login fallito');
                    window.history.back();
            </script>");

    
    mysqli_stmt_free_result($statement);
    mysqli_close($db_connection);
?>