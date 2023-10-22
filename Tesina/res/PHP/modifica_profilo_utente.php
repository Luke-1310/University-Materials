<?php

session_start();

include('connection.php');

$connessione = new mysqli($host, $user, $password, $db);

//ho incluso la connessione al db perché adesso mi prendo i valori dell'utente e gli compilo il form (per la password c'è da fare qualcosina in più)
$query = "SELECT ud.* FROM utenteDati ud INNER JOIN utenteMangaNett umn ON ud.id = umn.id WHERE umn.username = '{$_SESSION['nome_utente']}'";

//mi prendo i dati inviati col form
$nome = $connessione->real_escape_string($_POST['nome']);
$cognome = $connessione->real_escape_string($_POST['cognome']);
$email = $connessione->real_escape_string($_POST['email']);
$telefono = $connessione->real_escape_string($_POST['telefono']);

$residenza = $connessione->real_escape_string($_POST['residenza']);
$civico = $connessione->real_escape_string($_POST['civico']);

$username = $connessione->real_escape_string($_POST['username']);

$controllo_email = "SELECT COUNT(*) AS count FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE ud.email = '{$_SESSION['mod_email']}' AND umn.username != '{$_SESSION['nome_utente']}'";

$ris_e  = $connessione->query($controllo_email);

if ($ris_e){ 
    $row = $ris_e->fetch_assoc();
    $count = $row['count'];

} 
else {
    $_SESSION['errore_query'] = 'true';
    header('Location:../../modifica_profilo_utente.php');
    exit(1);
}

if ($count > 0) {
    $_SESSION['errore_email_ex'] = 'true';
    header('Location:../../modifica_profilo_utente.php');
    exit(1);
}

//con la seguente query controllo se il numero di telefono inserito è già presente nel db, escludendo però il numero dell'username corrente poiché non rilevante ai finti della correttezza 
$controllo_telefono = "SELECT COUNT(*) AS count FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE ud.numero_di_telefono = '{$_SESSION['mod_telefono']}' AND umn.username != '{$_SESSION['nome_utente']}'";

$ris_tel  = $connessione->query($controllo_telefono);

if ($ris_tel){ 
    $row = $ris_tel->fetch_assoc();
    $count = $row['count'];

} 
else {
    $_SESSION['errore_query'] = 'true';
    header('Location:../../modifica_profilo_utente.php');
    exit(1);
}

if ($count > 0) {
    $_SESSION['errore_tel_ex'] = 'true';
    header('Location:../../modifica_profilo_utente.php');
    exit(1);
} 

// Aggiornamento della tabella utenteDati
$sql_utenteDati = "UPDATE utenteDati 
                   SET nome = '$nome', cognome = '$cognome', 
                       email = '$email', via_di_residenza = '$residenza', civico = '$civico', 
                       numero_di_telefono = '$telefono'
                        WHERE id IN (SELECT id FROM utenteMangaNett WHERE username = '{$_SESSION['nome_utente']}')";

if ($connessione->query($sql_utenteDati)) {} 
else {
    $_SESSION['errore_query'] = 'true';
    header('Location:../../modifica_profilo_utente.php');
    exit(1);
}

// Aggiornamento della tabella utenteMangaNett
$sql_utenteMangaNett = "UPDATE utenteMangaNett 
                    SET username = '$username'
                    WHERE username = '{$_SESSION['nome_utente']}'";

if ($connessione->query($sql_utenteMangaNett)) {
    $_SESSION['nome_utente'] = $username;
} 
else {
    $_SESSION['errore_query'] = 'true';
    header('Location:../../modifica_profilo_utente.php');
    exit(1);
}

header('Location:../../vedi_informazioni.php');

// Chiudi la connessione al database
mysqli_close($connessione);

?>