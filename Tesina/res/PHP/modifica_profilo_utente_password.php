<?php

session_start();

require('connection.php');

$connessione = new mysqli($host, $user, $password, $db);

$password = $connessione->real_escape_string($_POST['password']);
$password2 = $connessione->real_escape_string($_POST['password2']);

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

//controllo se la password rispetta i parametri
//~ è il carattere delimitatore dell'espressione regolare
if (!preg_match('~^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[0-9]).{8,}$~', $password)){
    $_SESSION['errore_preg'] = 'true';
    header('Location: ../../modifica_password.php');
    exit(1);
}

//controllo se le password sono uguali
if($password !== $password2){
    $_SESSION['errore_p'] = 'true';
    header('Location: ../../modifica_password.php');
    exit(1);
}

// Aggiornamento della tabella utenteMangaNett
$sql = "UPDATE utenteDati 
        SET password = '$hashed_password'
        WHERE id IN (SELECT id FROM utenteMangaNett WHERE username = '{$_SESSION['nome_utente']}')";

// Esegui le query di aggiornamento e gestisci gli eventuali errori
if ($connessione->query($sql)) {

    $_SESSION['richiesta_ok'] = true;
    header('Location:../../vedi_informazioni.php');
} 
else {

    $_SESSION['errore_query'] = 'true';
    header('Location:../../modifica_password.php');
    exit(1);
}

// Chiudi la connessione al database
mysqli_close($connessione);

?>