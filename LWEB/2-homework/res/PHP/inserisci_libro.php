<?php

session_start();

require('connection.php');

$connessione = new mysqli($host, $user, $password, $db);

//real_escape_string() è una funzione usata per creare una stringa valida per SQL
$titolo = $connessione->real_escape_string($_POST['titolo']);
$ISBN = $connessione->real_escape_string($_POST['ISBN']);
$lunghezza = intval($connessione->real_escape_string($_POST['lunghezza'])); //converto la stringa in intero
$data = $connessione->real_escape_string($_POST['data']);
$autore = $connessione->real_escape_string($_POST['autore']);

// Verifica se è stato caricato l'immagine
if(isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {

    $img_tmp_name = $_FILES['img']['tmp_name'];
    $img_name = $_FILES['img']['name'];

    // Leggi il contenuto del file immagine come binario
    $img_bin = file_get_contents($img_tmp_name);

    // Codifica l'immagine in binario utilizzando base64
    $img_base64 = base64_encode($img_bin);
    $img = $connessione->real_escape_string($img_base64);
    
} else {
    // Se non è stato caricato alcun file o si è verificato un errore -> valore vuoto
    $img = ""; 
}

//controllo se l'ISBN inserito già esiste nel db
$controllo_ISBN = "SELECT* FROM libro l WHERE l.ISBN13 = '$ISBN'"; 
$ris = mysqli_query($connessione, $controllo_ISBN);

if(mysqli_num_rows($ris) > 0){
    $_SESSION['errore_i'] = 'true';
    header('Location:../../inserisci_libro.php'); //header sono l'analogo degli href
    exit(1);
}

$controllo_titolo = "SELECT* FROM libro l WHERE l.titolo = '$titolo'";
$ris_t = mysqli_query($connessione, $controllo_titolo);

if(mysqli_num_rows($ris_t) > 0){
    $_SESSION['errore_t'] = 'true';
    header('Location:../../inserisci_libro.php');
    exit(1);
}

$sql = "INSERT INTO libro (titolo, ISBN13, lunghezza, data_uscita, immagine, autore) VALUES ('$titolo', '$ISBN', '$lunghezza', '$data', '$img', '$autore')";
$ins = mysqli_query($connessione, $sql);
header('Location:../../homepage.php');

?>