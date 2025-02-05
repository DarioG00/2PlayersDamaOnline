<!DOCTYPE html>
<?php
  session_start();
?>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>2playersDama Online - Partita</title>
  <link rel="stylesheet" href="../css/gameStyle.css">
  <script src="../js/scriptDama.js"></script>
</head>
<body>
  <header>

    <div class="header-content">
      <div class="logo-title">
        <img src="../images/logo.png" alt="Logo" class="logo" />
        <h1>2PlayersDama Online</h1>
      </div>
  
      <div class="players-info">
        <div class="player-info">
            <img src="../images/pedina_nera.png" alt="Avatar" class="avatar"/>
            <?php
              echo "<p>[ " . $_SESSION['player-mittente'] . " ]</p>";
            ?>
        </div>
        <p>VS</p>
        <div class="player-info">
            <img src="../images/pedina_bianca.png" alt="Avatar" class="avatar"/>
            <?php
              echo "<p>[ " . $_SESSION['player-destinatario'] . " ]</p>";
            ?>
        </div>
      </div>

      <div class="leave-section">
        <a href="./abbandonaPartita.php">Abbandona partita</a>
      </div>
    </div>

  </header>

  <div class="container-partita">

    
    <aside>
      <div class="chat-container">
          <h2>Chat</h2>
          <?php
            echo "<div class='chat-names'>
                    <p id='avversario'><i>". $_SESSION['player-avversario'] . "</i></p>
                    <p id='username'><i>". $_SESSION['username'] . "</i></p>
                  </div>";
          ?>
          <div class="chat-messages" id="chat-messages">
              <!-- Messaggi della chat -->
          </div>
          <div class="chat-input">
              <input type="text" id="chat-msg" name="messaggio" placeholder="Scrivi un messaggio..." pattern=".{0,200}" title="Puoi inserire solo messaggi di max 200 caratteri.">
              <button id="chat-button">Invia</button>
          </div>
      </div>
    </aside>

    <main id="partita">
        <canvas id="scacchiera" width="480" height="480">
            Il tuo browser non supporta canvas.
        </canvas>
    </main>

    <aside>
        <div class="game-section">
          <h2>Turno</h2>
          <?php
            echo "<p id='turno-player'>". $_SESSION['partita']['turno-player'] . "</p>";
          ?>
          <button id="confirm-button">Cambia turno</a>
        </div>
      </aside>
    
  </div>
    
</body>
</html>