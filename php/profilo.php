<!DOCTYPE html>
<?php
  session_start();
?>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>2playersDama Online - Profilo</title>
  <link rel="stylesheet" href="../css/gameStyle.css">
  <script src="../js/scriptNotifiche.js"></script>
  <script src="../js/scriptProfilo.js"></script>
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
        <li><a href="./profilo.php" class="clicked">Profilo</a></li>
        <li><a href='./amici.php' id='link-amici' >Amici</a></li>
        <li><a href="./classifica.php">Classifica</a></li>
      </ul>
    </nav>

  </header>

  <div class="container">
    <main id="profilo">
      <section>
        <h2>Profilo</h2>
        <?php
          echo "<div><p>Username:   ". $_SESSION["username"]."</p></div>";
        ?>
        <div><p id="vittorie">Vittorie: </p></div>
        <div><p id="num-amici">#Amici: </p></div>
      </section>

    </main>
  </div>
</body>
</html>