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
  <title>2playersDama Online - Amici</title>
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
        <li><a href='./amici.php' id='link-amici' class='clicked'>Amici</a></li>
        <li><a href="./classifica.php">Classifica</a></li>
      </ul>    
    </nav>

  </header>

  <div class="container">

      <main id="amici">
        
        <section>
          <h2>Lista degli amici</h2>
          <?php
              //Otteniamo gli amici dell'utente loggato
              $query = "SELECT IF(mittente = ?, destinatario, mittente) 
                        FROM guidi_607453.amicizia
                        WHERE (mittente = ? OR destinatario = ?) AND stato = 'accettata'";
              $statement = mysqli_prepare($db_connection, $query);
              mysqli_stmt_bind_param($statement, "sss", $_SESSION["username"], $_SESSION["username"], $_SESSION["username"]);
              mysqli_stmt_execute($statement);

              mysqli_stmt_bind_result($statement, $amico);

              while(mysqli_stmt_fetch($statement)){
                  echo "<div class='amico'>
                          <p>".$amico."</p>
                          <form method='post' action='./rimuoviAmicizia.php'>
                            <button type='submit' name='player-rimosso' value='". $amico ."'>Rimuovi</button>
                          </form>
                        </div>";
              }
                      
              mysqli_stmt_free_result($statement);
              mysqli_close($db_connection);
          ?>
        </section>

      </main>

      <aside>
        <div class="msg-richieste">
          <h2>Nuove richieste d'amicizia:</h2>
          <a href='./richieste.php' id='link-richieste'>Vedi richieste</a>
        </div>
      </aside>

  </div>
</body>
</html>