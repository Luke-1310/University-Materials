<?php

session_start();

require('connection.php');

$connessione = new mysqli($host, $user, $password, $db);

//real_escape_string() è una funzione usata per creare una stringa valida per SQL
$username = $connessione->real_escape_string($_POST['username']);
$password = $connessione->real_escape_string($_POST['password']);

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $controllo = "SELECT* FROM utentedati ud, utentemanganett um  WHERE um.username = '$username' AND ud.id = um.id";

    if($ris = $connessione->query($controllo)){
        
        if(mysqli_num_rows($ris) == 1){
            $row = $ris->fetch_array(MYSQLI_ASSOC); //prendiamo la password hashata
            
            if(password_verify($password, $row['password'])){
                
                $_SESSION['loggato'] = 'true';
                $_SESSION['nome'] = $username;
                header('Location:../../homepage.php'); //header sono l'analogo degli href
                exit(1);
            }
            else{
                $_SESSION['errore'] = 'true';
                header('Location:../../login.php'); //header sono l'analogo degli href
                exit(1);
            }
        }
        else{
            $_SESSION['errore'] = 'true';
            header('Location:../../login.php'); //header sono l'analogo degli href
            exit(1);
        }
    }

    else{
        $_SESSION['errore_v'] = 'true';
        header('Location:../../login.php'); //header sono l'analogo degli href
        exit(1);
    }
    
    $connessione->close(); //Chiudo la connessione
}
?>