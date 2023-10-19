<?php

session_start();

include('connection.php');

$connessione = new mysqli($host, $user, $password, $db);

//ho incluso la connessione al db perché adesso mi prendo i valori dell'utente e gli compilo il form (per la password c'è da fare qualcosina in più)
$query = "SELECT ud.* FROM utenteDati ud INNER JOIN utenteMangaNett umn ON ud.id = umn.id WHERE umn.username = '{$_SESSION['nome']}'";


//mi prendo i dati inviati col form
$nome = $connessione->real_escape_string($_POST['nome']);
$cognome = $connessione->real_escape_string($_POST['cognome']);
$email = $connessione->real_escape_string($_POST['email']);
$telefono = $connessione->real_escape_string($_POST['telefono']);

$residenza = $connessione->real_escape_string($_POST['residenza']);
$civico = $connessione->real_escape_string($_POST['civico']);

$username = $connessione->real_escape_string($_POST['username']);
$password = $connessione->real_escape_string($_POST['password']);
$password2 = $connessione->real_escape_string($_POST['password2']);

//hasho la password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

//mi salvo in delle varibaili di sessione i campi da ricompilare nel form
$_SESSION['mod_nome'] = $nome;
$_SESSION['mod_cognome'] = $cognome;
$_SESSION['mod_email'] = $email;
$_SESSION['mod_telefono'] = $telefono;

$_SESSION['mod_residenza'] = $residenza;
$_SESSION['mod_civico'] = $civico;

$_SESSION['mod_username'] = $username;

//controllo se la nuova email è già presente nel db, trascurando ovviamente l'utente corrente, poiché magari non l'ha modificata e così facendo darebbe errore riscontrando un uguaglianza
//con la seguente query controllo se la nuova email inserita è già presente nel db, escludendo però i dati dell'username corrente poiché non rilevante ai finti della correttezza 
$controllo_email = "SELECT COUNT(*) AS count FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE ud.email = '{$_SESSION['mod_email']}'  AND umn.username != '{$_SESSION['nome']}'";

$ris_e  = $connessione->query($controllo_email);

if ($ris_e){ 
    $row = $ris_e->fetch_assoc();
    $count = $row['count'];

} 
else {
    $_SESSION['errore_query'] = 'true';
    header('Location:../../modifica_profilo.php');
    exit(1);
}

if ($count > 0) {
    $_SESSION['errore_email_ex'] = 'true';
    header('Location:../../modifica_profilo.php');
    exit(1);
}

//con la seguente query controllo se il numero di telefono inserito è già presente nel db, escludendo però il numero dell'username corrente poiché non rilevante ai finti della correttezza 
$controllo_telefono = "SELECT COUNT(*) AS count FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE ud.numero_di_telefono = '{$_SESSION['mod_telefono']}'  AND umn.username != '{$_SESSION['nome']}'";

$ris_tel  = $connessione->query($controllo_telefono);

if ($ris_tel){ 
    $row = $ris_tel->fetch_assoc();
    $count = $row['count'];

} 
else {
    $_SESSION['errore_query'] = 'true';
    header('Location:../../modifica_profilo.php');
    exit(1);
}

if ($count > 0) {
    $_SESSION['errore_tel_ex'] = 'true';
    header('Location:../../modifica_profilo.php');
    exit(1);
} 

//con la seguente query controllo se l'username inserito è già presente nel db, escludendo però quello dell'utente corrente poiché non rilevante ai finti della correttezza 
$controllo_username = "SELECT COUNT(*) AS count FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.username = '{$_SESSION['mod_username']}'  AND umn.username != '{$_SESSION['nome']}'";

$ris_usr  = $connessione->query($controllo_username);

if ($ris_usr){ 
    $row = $ris_usr->fetch_assoc();
    $count = $row['count'];

} 
else {
    $_SESSION['errore_query'] = 'true';
    header('Location:../../modifica_profilo.php');
    exit(1);
}

if ($count > 0) {
    $_SESSION['errore_usr_ex'] = 'true';
    header('Location:../../modifica_profilo.php');
    exit(1);
} 

//controllo se la password rispetta i parametri
//~ è il carattere delimitatore dell'espressione regolare
if (!preg_match('~^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[0-9]).{8,}$~', $password)){
    $_SESSION['errore_preg'] = 'true';
    header('Location:../../modifica_profilo.php');
    exit(1);
}


//controllo se le password sono uguali
if($password !== $password2){
    $_SESSION['errore_p'] = 'true';
    header('Location:../../modifica_profilo.php');
    exit(1);
}

// Aggiornamento della tabella utenteDati
$sql_utenteDati = "UPDATE utenteDati 
                   SET nome = '$nome', cognome = '$cognome', password = '$hashed_password', 
                       email = '$email', via_di_residenza = '$residenza', civico = '$civico', 
                       numero_di_telefono = '$telefono'
                        WHERE id IN (SELECT id FROM utenteMangaNett WHERE username = '{$_SESSION['nome']}')";

// Aggiornamento della tabella utenteMangaNett
$sql_utenteMangaNett = "UPDATE utenteMangaNett 
                        SET username = '{$_SESSION['mod_username']}' 
                        WHERE username = '{$_SESSION['nome']}'";


// Esegui le query di aggiornamento e gestisci gli eventuali errori
if ($connessione->query($sql_utenteDati) && $connessione->query($sql_utenteMangaNett)) {
    $_SESSION['nome'] = $_SESSION['mod_username'];
} else {
    $_SESSION['errore_query'] = 'true';
    header('Location:../../modifica_profilo.php');
    exit(1);
}

header('Location:../../profilo.php');


// Chiudi la connessione al database
mysqli_close($connessione);



?>