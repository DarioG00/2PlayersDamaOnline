<!DOCTYPE html>
<?php
  session_start();
  include "./configDB.php";
  include "./dbConnection.php";
?>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>2playersDama Online - Inviti</title>
  <link rel="stylesheet" href="../css/gameStyle.css">
  <script src="../js/scriptNotifiche.js"></script>
</head>
<body>
  <header>

    <div class="header-content">
      <div class="logo-title">
        <img src="../images/logo.png" alt="Logo" class="logo" />
        <h1>2PlayersDama Online</h1>
      </div>
  
      <div class="login-info">
        <div class="username-info">
            <img src="../images/pedina_bianca.png" alt="Avatar" class="avatar"/>
            <?php
              echo "<p>[ " . $_SESSION['username'] . " ]</p>";
            ?>
        </div>
        <a href="./logout.php">Logout</a>
      </div>
    </div>

    <hr>

    <nav class="top-navigation">
      <ul>
        <li><a href='./menu.php' id='link-partita'>Inizia Partita</a></li>
        <li><a href="./profilo.php ">Profilo</a></li>
        <li><a href='./amici.php' id='link-amici' >Amici</a></li>
        <li><a href="./classifica.php">Classifica</a></li>
      </ul>      
    </nav>

  </header>

  <div class="container">
    
    <main id="inviti">

      <section>
        <h2>Inviti per te</h2>
        
        <?php
            //Otteniamo gli inviti a giocare in sopeso dell'utente loggato
            $query = "SELECT mittente FROM guidi_607453.invitopartita WHERE destinatario = ? AND stato = 'sospeso'";
            $statement = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($statement, "s", $_SESSION["username"]);
            if(mysqli_stmt_execute($statement)){
              mysqli_stmt_bind_result($statement, $mittente);

              $tmp = false;
              while(mysqli_stmt_fetch($statement)){
                $tmp = true;
                echo "<div class='invito'>
                        <p><i>". $mittente ."</i> ti ha invitato a una partita.</p>
                        <form method='POST' action='./rispondiInvito.php' class='pad'>
                            <button type='submit' name='accetta' value ='".$mittente."'>Accetta</button>
                            <button type='submit' name='rifiuta' value='".$mittente."'>Rifiuta</button>
                        </form>
                      </div>";
              }
              if(!$tmp){
                echo "<p>Non ci sono inviti</p>";
              }
            }
      
            mysqli_stmt_free_result($statement);
            mysqli_close($db_connection);

        ?>

      </section>

    </main>
    
  </div>
    
</body>
</html>