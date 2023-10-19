<?php

session_start();

include('connection.php');

$id = $_POST['id'];
$ruolo = $_POST['ruolo'];

$connessione = new mysqli($host, $user, $password, $db);

if (isset($_POST['bottone_promuovi'])) {

    if($ruolo == "CL"){
        $ruolo = "GS";
    }
    else if($ruolo == "GS"){
        $ruolo = "AM";
    }

    $query_u = "UPDATE utenteMangaNett 
                SET ruolo = '$ruolo'
                WHERE id  = '$id' AND id <> 1";
    //"id <> 1" DEVE essere diverso da 1, questo per tutelare il fatto che ci sia almeno un admin!

    $result_u = $connessione->query($query_u);

    //Verifico se la query ha restituito risultati
    if (!$result_u) {
        echo "Errore nella query: " . $connessione->error;
        exit(1);
    }

    header('Location:../../lista_utenti.php');
    exit(1);
} 
  
if (isset($_POST['bottone_retrocedi'])) {
    
    if($ruolo == "AM"){
        $ruolo = "GS";
    }
    else if($ruolo == "GS"){
        $ruolo = "CL";
    }

    $query_u = "UPDATE utenteMangaNett 
                SET ruolo = '$ruolo'
                WHERE id  = '$id' AND id <> 1";
    //"id <> 1" DEVE essere diverso da 1, questo per tutelare il fatto che ci sia almeno un admin!
    
    $result_u = $connessione->query($query_u);

    //Verifico se la query ha restituito risultati
    if (!$result_u) {
        echo "Errore nella query: " . $connessione->error;
        exit(1);
    }

    header('Location:../../lista_utenti.php');

    exit(1);
}

?>