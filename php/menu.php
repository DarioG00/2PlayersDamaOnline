<!DOCTYPE html>
<?php
  session_start();
?>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>2playersDama Online - Menu</title>
  <link rel="stylesheet" href="../css/gameStyle.css">
  <script src="../js/scriptNotifiche.js"></script>
  <script>
    
    function initValidation(){
      const form = document.getElementById("form-ricerca");
      form.addEventListener("submit", (e) => {
        const fieldValue = document.getElementById("nome-cercato").value;

        let regExpr = /^[a-z0-9]{1,60}$/;
        if(!regExpr.test(fieldValue)){
          e.preventDefault();
          alert("username player con formato non valido");
        }

        if (fieldValue == null || fieldValue == "") {
          e.preventDefault();
          alert("nessun username inserito!");
        }
      });
    }
    
    document.addEventListener("DOMContentLoaded", initValidation);
  </script>
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
        <li><a href='./menu.php' id='link-partita' class='clicked'>Inizia Partita</a></li>
        <li><a href="./profilo.php ">Profilo</a></li>
        <li><a href='./amici.php' id='link-amici' >Amici</a></li>
        <li><a href="./classifica.php">Classifica</a></li>
      </ul>      
    </nav>

  </header>

  <div class="container">
    
    <main id="menu">
      <section>
          <h2>Cerca un player</h2>

          <form id="form-ricerca" method="post" action="./ottieniPlayer.php">
            <input id="nome-cercato" type="text" name="player-cercato" placeholder="Cerca player" pattern="[a-z0-9]{1,60}" title="Puoi inserire solo lettere minuscole e numeri, massimo 60 caratteri." required>
            <button type="submit">Invia</button>
          </form>
      
      </section>

      <?php
        if(isset($_SESSION["player-cercato"])){

          echo "<section>
                <p><i>". $_SESSION["player-cercato"] ."<i></p>
                <form method='post' action='./inviaInvito.php'>
                  <button type='submit' name='player-destinatario' value='". $_SESSION["player-cercato"] ."'>Invita a giocare</button>
                </form>
                <form method='post' action='./inviaRichiesta.php'>
                  <button type='submit' name='player-destinatario' value='". $_SESSION["player-cercato"] ."'>Aggiungi amico</button>
                </form>
                </section>";

          unset($_SESSION["player-cercato"]);

        }
      ?>

    </main>

    <aside>
      <div class="msg-inviti">
        <h2>Nuovi inviti:</h2>
        <a href='./inviti.php' id='link-inviti'>Vedi inviti</a>
      </div>
    </aside>
    
  </div>
    
</body>
</html>