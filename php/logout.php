<?php
    session_start();
    unset($_SESSION['login']);
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['player-cercato']);
    unset($_SESSION['partita']);
    header('Location: ../index.html');
?>