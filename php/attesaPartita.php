<!DOCTYPE html>
<?php
    session_start();
?>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>2playersDama Online - Attendi...</title>
  <link rel="stylesheet" href="../css/gameStyle.css">
  <script src="../js/scriptAttesaInvito.js"></script>
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
        <a href="./annullaInvito.php">Annulla invito</a>
      </div>
    </div>

  </header>

  <div class="container">
    <main>
      <div class="msg-attesa">
        <?php
          echo "<p>In attesa che ". $_SESSION['player-destinatario'] ." accetti l'invito...</p>";
        ?>
        <p id="tempo-attesa"></p>
      </div>
    </main>
    
  </div>
    
</body>
</html>