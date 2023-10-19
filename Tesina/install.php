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

//Crea la tabella utenteDati se non esistente
//E' necessario mettere al campo id 'AUTO_INCREMENT' altrimenti inserito il secondo utente darebbe errore
//N.B. è essenziale porre l'accento grave ` al posto dell'apostrofo ' altrimenti darebbe errore -_-
$tab_utente = "CREATE TABLE IF NOT EXISTS `utenteDati` (
    `id` int(11) NOT NULL AUTO_INCREMENT,               
    `nome` varchar(50) NOT NULL,
    `cognome` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(50) NOT NULL,
    `via_di_residenza` varchar(50) NOT NULL,
    `civico` varchar(5) NOT NULL,
    `numero_di_telefono` varchar(10) NOT NULL,

    PRIMARY KEY (id)
)";

if ($conn->query($tab_utente) === FALSE) {
    echo "Errore nella creazione della tabella utente " . $conn->error;
}

$ins_ut = "INSERT INTO `utenteDati` (`nome`, `cognome`, `password`, `email`, `via_di_residenza`, `civico`, `numero_di_telefono`) VALUES
('Luca', 'Privitera', '" . password_hash('123', PASSWORD_DEFAULT) . "', 'privitera@gmail.com', 'Andrea Doria', '3', '3490201244'),
('Alex', 'Di Maggio', '" . password_hash('123', PASSWORD_DEFAULT) . "', 'dimaggio@gmail.com', 'Andrea Doria', '3', '3482201634')";

if ($conn->query($ins_ut) === FALSE) {
    echo "Errore nell'inserimento degli utenti " . $conn->error;
}

//Crea la tabella utenteMangaNett se non esistente
//E' necessario mettere al campo id 'AUTO_INCREMENT' altrimenti inserito il secondo utente darebbe errore
//N.B. è essenziale porre l'accento grave ` al posto dell'apostrofo ' altrimenti darebbe errore -_-
$tab_utenteMangaNett = "CREATE TABLE IF NOT EXISTS `utenteMangaNett` (
    
    `id` int(11) NOT NULL,                
    `username` varchar(20) NOT NULL,
    `data_registrazione` date NOT NULL,
    `ruolo` varchar(2) NOT NULL,
    `crediti` float(10) NOT NULL,
    `reputazione` int(2) NOT NULL,
    `segnalazione` tinyint(1) NOT NULL,
    `ban` tinyint(1) NOT NULL,

    PRIMARY KEY (id),
    FOREIGN KEY (id) REFERENCES `utenteDati` (id)
)";

if ($conn->query($tab_utenteMangaNett) === FALSE) {
    echo "Errore nella creazione della tabella utente " . $conn->error;
}
// date("Y-m-d")

$ins_utManga = "INSERT INTO `utenteMangaNett` (`id`, `username`, `data_registrazione`, `ruolo`, `crediti`, `reputazione`, `segnalazione`, `ban`) VALUES
('1', 'Luke88', '2023-08-08', 'AM', '5', '12', '0', '0'),
('2', 'alexdm02192', '2023-08-08', 'GS', '0', '12', '0', '0')";

if ($conn->query($ins_utManga) === FALSE) {
    echo "Errore nell'inserimento degli utenti " . $conn->error;
}

//alla fine della creazione si verrà reindirizzati alla homepage
header('Location: homepage.php');

// Chiude la connessione al database
$conn->close();

?>