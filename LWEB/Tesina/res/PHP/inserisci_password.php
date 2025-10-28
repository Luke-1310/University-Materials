<?php

session_start(); //ho lasciato fuori questa funzione, invece di metterla quando il login va a buon fine per poter stampare un messaggio di errore

require('connection.php');

$connessione = new mysqli($host, $user, $password, $db);

//mi prendo la password inserita nel form
$psw = $_POST['password'];

//faccio un check tra quella presa dal form e quella criptata
//mi prendo la password criptata nel db

$username = $_SESSION['nome'];

$controllo = "SELECT* FROM utentedati ud, utentemanganett um  WHERE um.username = '$username' AND ud.id = um.id";

if($ris = $connessione->query($controllo)){

    if(mysqli_num_rows($ris) == 1){

        $row = $ris->fetch_array(MYSQLI_ASSOC); //prendiamo la password hashata
        
        if(password_verify($psw, $row['password'])){
            
            if($_SESSION['azione'] == "dati"){
                header('Location:../../modifica_profilo.php'); //header sono l'analogo degli href
                exit(1);
            }

            if($_SESSION['azione'] == "psw"){
                header('Location:../../modifica_password.php'); //header sono l'analogo degli href
                exit(1);
            }
        }
        else{
            $_SESSION['errore_mp'] = 'true';
            header('Location:../../inserisci_password.php'); //header sono l'analogo degli href
            exit(1);
        }
    }
    $connessione->close(); //Chiudo la connessione
}

?>