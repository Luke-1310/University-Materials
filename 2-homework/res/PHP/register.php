<?php

session_start();

require('connection.php');

$connessione = new mysqli($host, $user, $password, $db);

//real_escape_string() è una funzione usata per creare una stringa valida per SQL
$username = $connessione->real_escape_string($_POST['username']);
$email = $connessione->real_escape_string($_POST['email']);
$password = $connessione->real_escape_string($_POST['password']);
$password2 = $connessione->real_escape_string($_POST['password2']);

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$controllo = "SELECT* FROM utente u WHERE u.username = '$username'"; 
$ris = mysqli_query($connessione, $controllo);

if(mysqli_num_rows($ris) > 0){
    $_SESSION['errore'] = 'true';
    header('Location:../../register.php'); //header sono l'analogo degli href
    exit(1);
}

$controllo_email = "SELECT* FROM utente e WHERE e.email = '$email'";
$ris_e = mysqli_query($connessione, $controllo_email);

if(mysqli_num_rows($ris_e) > 0){
    $_SESSION['errore_e'] = 'true';
    header('Location:../../register.php');
    exit(1);
}

if($password !== $password2){
    $_SESSION['errore_p'] = 'true';
    header('Location:../../register.php');
    exit(1);
}

$sql = "INSERT INTO utente (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
$ins = mysqli_query($connessione, $sql);

header('Location:../../login.php');

?>