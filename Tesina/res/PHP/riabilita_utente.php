<?php

session_start();

include('connection.php');

$connessione = new mysqli($host, $user, $password, $db);

$sql = "UPDATE utenteMangaNett 
        SET ban = 0
        WHERE username  = '{$_SESSION['nome_utente']}'";

// Esegui le query di aggiornamento e gestisci gli eventuali errori
if ($connessione->query($sql)) {
    header('Location:../../lista_utenti.php');
} 

else {
    echo "Errore nella query: " . $connessione->error;
    exit(1);
}

?>