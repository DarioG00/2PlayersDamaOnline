<?php
    // Connessione al database
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    if ( mysqli_connect_errno() ) {
        exit('Connessione al database non riuscita. (' . mysqli_connect_error() . ')');
    }