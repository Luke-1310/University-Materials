<?php

session_start();

include('connection.php');
include('funzioni.php');

$username = $_POST['username'];
$data = $_POST['data'];
$ID = $_POST['id'];

$connessione = new mysqli($host, $user, $password, $db);

//in questo caso devo bannare l'utente e rendere il commento non visibile, mettendo il campo segnalazione del commento su 0
if (isset($_POST['bottone_ban_ok'])) {

    //1° STEP metto il record SQL bannato dell'utente su 'true'

    $sql = "UPDATE utenteMangaNett 
    SET ban = 'true'
    WHERE username = '$username'";

    // Esegui le query di aggiornamento e gestisci gli eventuali errori
    if ($connessione->query($sql)) {} 
    else {
        $_SESSION['errore_query'] = 'true';
        header('Location:../../gestione_segnalazione.php');
        exit(1);
    }

    //2° STEP metto il campo segnalazione a 0
    //per fare ciò devo capire se devo controllare una domanda o una risposta
    $xmlpath = "../XML/Q&A.xml";
    $domande = getDomande($xmlpath);

    //devo caricarmi anche il file originale in modo tale da sovrascriverlo successivamente
    $document = new DOMDocument();
    $document->load($xmlpath);

    $domande_doc = $document->getElementsByTagName('domanda');

    //controllo da dove è partito il collegamento tramite GET
    //ci troviamo nel caso di segnalazione di una domanda
    if(isset($_GET['from']) && $_GET['from'] == "domanda"){
    
        foreach($domande_doc as $domanda_doc){

            $domanda_IDDom = $domanda_doc->getElementsByTagName('IDDom')->item(0)->nodeValue;
            $domanda_dataDom = $domanda_doc->getElementsByTagName('dataDom')->item(0)->nodeValue;
        
            foreach($domande as $domanda){
        
                if($domanda_IDDom == $ID && $domanda_dataDom == $data){

                    $domanda_doc->getElementsByTagName('segnalazione')->item(0)->nodeValue = 0;
                    break;
                }
            }
        }
    }
    $document->save($xmlpath);

    $_SESSION['ban_ok'] = true;
    header('Location:../../gestione_segnalazione.php');
    exit(1);
} 
  
//in questo caso NON devo bannare l'utente e rimuovere la segnalazione
if (isset($_POST['bottone_ban_ko'])) {
    
    echo $username;

    // header('Location:../../lista_utenti.php');
    // exit(1);
}

?>