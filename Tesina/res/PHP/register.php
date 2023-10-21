<?php

session_start();

require('connection.php');

$connessione = new mysqli($host, $user, $password, $db);

//real_escape_string() è una funzione usata per creare una stringa valida per SQL
$nome = $connessione->real_escape_string($_POST['nome']);
$cognome = $connessione->real_escape_string($_POST['cognome']);
$email = $connessione->real_escape_string($_POST['email']);
$telefono = $connessione->real_escape_string($_POST['telefono']);

$residenza = $connessione->real_escape_string($_POST['residenza']);
$civico = $connessione->real_escape_string($_POST['civico']);

$username = $connessione->real_escape_string($_POST['username']);
$password = $connessione->real_escape_string($_POST['password']);
$password2 = $connessione->real_escape_string($_POST['password2']);

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

//mi salvo in delle varibaili di sessione i campi da ricompilare nel form
$_SESSION['form_nome'] = $nome;
$_SESSION['form_cognome'] = $cognome;
$_SESSION['form_email'] = $email;
$_SESSION['form_telefono'] = $telefono;

$_SESSION['form_residenza'] = $residenza;
$_SESSION['form_civico'] = $civico;

$_SESSION['form_username'] = $username;

//controllo email già esistente
$controllo_email = "SELECT* FROM utentedati e WHERE e.email = '$email'";
$ris_e = mysqli_query($connessione, $controllo_email);

if(mysqli_num_rows($ris_e) > 0){
    $_SESSION['errore_e'] = 'true';
    header('Location:../../register.php');
    exit(1);
}

//controllo nr telefono già esistente
$controllo_telefono = "SELECT* FROM utentedati t WHERE t.numero_di_telefono = '$telefono'";
$ris_t = mysqli_query($connessione, $controllo_telefono);

if(mysqli_num_rows($ris_t) > 0){
    $_SESSION['errore_t'] = 'true';
    header('Location:../../register.php');
    exit(1);
}
//controllo se la password rispetta i parametri
//~ è il carattere delimitatore dell'espressione regolare
if (!preg_match('~^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[0-9]).{8,}$~', $password)){
    $_SESSION['errore_preg'] = 'true';
    header('Location:../../register.php');
    exit(1);
}


//controllo se le password sono uguali
if($password !== $password2){
    $_SESSION['errore_p'] = 'true';
    header('Location:../../register.php');
    exit(1);
}

//controllo username già esistente
$controllo = "SELECT* FROM utentemanganett u WHERE u.username = '$username'"; 
$ris = mysqli_query($connessione, $controllo);

if(mysqli_num_rows($ris) > 0){
    $_SESSION['errore_ur'] = 'true';
    header('Location:../../register.php'); //header sono l'analogo degli href
    exit(1);
}

$sql = "INSERT INTO utenteDati (nome, cognome, password, email, via_di_residenza, civico, numero_di_telefono) VALUES ('$nome', '$cognome', '$hashed_password', '$email', '$residenza', '$civico', '$telefono')";

//Mi ricavo la data attuale per la registrazione
$data = date("Y-m-d");

//metto gli altri valori standard nelle corrispettive variabili di inserimento
$ruolo = "CL";
$crediti = 0;
$reputazione = 1;
$segnalazione = 0;
$ban = 0;

// Esegui l'istruzione di inserimento in utenteDati
if ($connessione->query($sql) === FALSE) {
    header('Location:../../register.php'); //header sono l'analogo degli href
    exit(1);
} 

else {
    // Recupera l'ID dell'utente appena inserito
    $utenteID = mysqli_insert_id($connessione);

    // Ora puoi usare $utenteID per inserire un record in utenteMangaNett
    $sqlinsert = "INSERT INTO utenteMangaNett (id, username, data_registrazione, ruolo, crediti, reputazione, segnalazione, ban) VALUES ('$utenteID','$username', '$data', '$ruolo', '$crediti', '$reputazione', '$segnalazione', '$ban')";

    if ($connessione->query($sqlinsert) === FALSE) {
        $_SESSION['errore_utDati'] = 'true';
        header('Location:../../register.php'); //header sono l'analogo degli href
        exit(1);
    }

    //unsetto tutte le variabili di sessione utilizzati prima visto che il form è andato a buon fine
    unset($_SESSION['form_nome']);
    unset($_SESSION['form_cognome']);
    unset($_SESSION['form_email']);
    unset($_SESSION['form_telefono']);

    unset($_SESSION['form_residenza']);
    unset($_SESSION['form_civico']);

    unset($_SESSION['form_username']);

    // Chiudi la connessione al database
    mysqli_close($connessione);

    $_SESSION['registrazione_ok'] = 'true';

    header('Location:../../login.php');
}

?>