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
  <title>2playersDama Online - Classifica</title>
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
        <li><a href="./classifica.php" class="clicked">Classifica</a></li>
      </ul>    
    </nav>

  </header>

  <div class="container">

      <main id="classifica">
        
        <section>
          <h2>Top #10 players tra i tuoi amici</h2>
          <table>
              <thead>
                  <tr>
                      <th>Posizione</th>
                      <th>Username</th>
                      <th>Vittorie</th>
                  </tr>
              </thead>
              <tbody>
              <?php
                $query = "SELECT P.Vincitore, count(*) AS vittorie
                          FROM guidi_607453.partita P
                          WHERE (P.g1 = ? OR P.g2 = ?) AND vincitore <> 'pareggio'
                          GROUP BY P.Vincitore
                          ORDER BY vittorie DESC
                          LIMIT 10";

                $statement = mysqli_prepare($db_connection, $query);
                mysqli_stmt_bind_param($statement, "ss", $_SESSION["username"], $_SESSION["username"]);
                if(mysqli_stmt_execute($statement)){
                  mysqli_stmt_bind_result($statement, $username, $vittorie);
                  $posizione = 1;
                  while(mysqli_stmt_fetch($statement)){
                      echo "<tr>
                              <td>". $posizione ."</td>
                              <td>". $username ."</td>
                              <td>". $vittorie ."</td>
                            </tr>";
                      $posizione++;
                  }
                }

                mysqli_stmt_free_result($statement);
                mysqli_close($db_connection);

              ?>
              </tbody>
            </table>
        </section>

      </main>
  </div>
</body>
</html>