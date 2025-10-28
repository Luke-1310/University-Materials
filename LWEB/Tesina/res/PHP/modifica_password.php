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

//faccio un check tra quella presa dal form e quella già presente nel db
//in caso affermativo voglio un errore perché si sta modificando la password con una password uguale alla precedente!
$username = $_SESSION['nome'];

$controllo = "SELECT* FROM utentedati ud, utentemanganett um  WHERE um.username = '$username' AND ud.id = um.id";
if($ris = $connessione->query($controllo)){

    if(mysqli_num_rows($ris) == 1){

        $row = $ris->fetch_array(MYSQLI_ASSOC); //prendiamo la password hashata
        
        if(password_verify($password, $row['password'])){

            $_SESSION['errore_psw_precedente'] = 'true';
            header('Location: ../../modifica_password.php');
            exit(1);   
        }
    }
}

$sql = "UPDATE utenteDati 
        SET password = '$hashed_password'
        WHERE id IN (SELECT id FROM utenteMangaNett WHERE username = '{$_SESSION['nome']}')";

if ($connessione->query($sql)) {
    header('Location:../../profilo.php');
} 
else{
    $_SESSION['errore_query'] = 'true';
    header('Location:../../modifica_password.php');
    exit(1);
}

// Chiudi la connessione al database
mysqli_close($connessione);

?>