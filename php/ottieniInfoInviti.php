<?php
  if (session_status() === PHP_SESSION_NONE) session_start();
  include_once "configDB.php";
  include "dbConnection.php";

  //Otteniamo gli inviti a giocare in sopeso dell'utente loggato
  $query = "SELECT count(*) FROM guidi_607453.invitopartita WHERE destinatario = ? AND stato = ?";
  $statement = mysqli_prepare($db_connection, $query);
  $stato_invito = "sospeso";
  mysqli_stmt_bind_param($statement, "ss", $_SESSION["username"], $stato_invito);
  if(mysqli_stmt_execute($statement)){
    mysqli_stmt_bind_result($statement, $inviti);
    while(mysqli_stmt_fetch($statement)){
      echo "{\"inviti\": ". $inviti . "}";
    }
  }

  mysqli_stmt_free_result($statement);
  mysqli_close($db_connection);
?>