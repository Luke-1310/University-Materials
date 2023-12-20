<?php

session_start();

include('connection.php');
include('funzioni.php');

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
    //"id <> 1" DEVE essere diverso da 1, questo per tutelare il superadmin!

    $result_u = $connessione->query($query_u);

    //Verifico se la query ha restituito risultati
    if (!$result_u) {
        echo "Errore nella query: " . $connessione->error;
        exit(1);
    }

    //ora devo mettere la reputazione a 12, di base la promozione copre sia il ruolo di GS che AM e quindi sicuro tale ruoli deve avere reputazione a 12
    $query_repu = "UPDATE utenteMangaNett 
                SET reputazione = '12'
                WHERE id  = '$id' AND id <> 1";

    $result_repu = $connessione->query($query_repu);

    //Verifico se la query ha restituito risultati
    if (!$result_repu) {
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

        $xmlPath = "../XML/Q&A.xml";
        $xmlPathFumetti = "../XML/catalogo.xml";

        $reputazione = calcolaReputazione($id,$xmlPath,$xmlPathFumetti);

        //in questo caso devo anche modificare la reputazione, un cliente non può avere reputazione 12
        $query_repu = "UPDATE utenteMangaNett 
        SET reputazione = '$reputazione'
        WHERE id  = '$id' AND id <> 1";

        $result_repu = $connessione->query($query_repu);

        //Verifico se la query ha restituito risultati
        if (!$result_repu) {
            echo "Errore nella query: " . $connessione->error;
            exit(1);
        }
    }

    $query_u = "UPDATE utenteMangaNett 
                SET ruolo = '$ruolo'
                WHERE id  = '$id' AND id <> 1";
    //"id <> 1" DEVE essere diverso da 1, questo per tutelare il superadmin!
    
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