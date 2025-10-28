<?php

require_once('res/PHP/connection.php');

// Crea la connessione col server
$conn = new mysqli($host, $user, $password);

//Controllo sulla connessione
if($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Crea il database se non esiste già
$sql = "CREATE DATABASE IF NOT EXISTS $db";

if ($conn->query($sql) === FALSE) {
    echo "Errore nella creazione del database " . $conn->error;
}

// Seleziona il database con cui vogliamo operare
$conn = new mysqli($host, $user, $password, $db);

//Crea la tabella utente se non esistente
//E' necessario mettere al campo id 'AUTO_INCREMENT' altrimenti inserito il secondo utente darebbe errore
//N.B. è essenziale porre l'accento grave ` al posto dell'apostrofo ' altrimenti darebbe errore -_-
$tab_utente = "CREATE TABLE IF NOT EXISTS `utente` (
    `id` int(11) NOT NULL AUTO_INCREMENT,               
    `username` varchar(30) NOT NULL,
    `email` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY (id)
)";

if ($conn->query($tab_utente) === FALSE) {
    echo "Errore nella creazione della tabella utente " . $conn->error;
}

$ins_ut = "INSERT INTO `utente` (`username`, `email`, `password`) VALUES
('Luke', 'luke@gmail.com', '" . password_hash('123', PASSWORD_DEFAULT) . "'),
('Ale_Col', 'ale@gmail.com', '" . password_hash('123', PASSWORD_DEFAULT) . "')";

if ($conn->query($ins_ut) === FALSE) {
    echo "Errore nell'inserimento degli utenti " . $conn->error;
}

//alla fine della creazione siverrà reindirizzati alla homepage
header('Location: homepage.php');

// Chiude la connessione al database
$conn->close();

?>