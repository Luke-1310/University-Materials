<?php

session_start();

include('connection.php');
include('funzioni.php');

$username = $_POST['username'];
$data = $_POST['data'];
$ID = $_POST['id'];

$connessione = new mysqli($host, $user, $password, $db);

//in questo caso devo bannare l'utente e rendere il commento non visibile, mettendo il campo segnalazione del commento su 0
if (isset($_POST['bottone_ban_ok']) && isset($_GET['from']) && $_GET['from'] != "recensione") {

    //1° STEP metto il record SQL bannato dell'utente su 'true'
    $sql = "UPDATE utenteMangaNett 
    SET ban = 1
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

    $document = new DOMDocument();
    $document->load($xmlpath);

    $domande_doc = $document->getElementsByTagName('domanda');

    //ci troviamo nel caso di segnalazione di una domanda
    if(isset($_GET['from']) && $_GET['from'] == "domanda"){
    
        foreach($domande_doc as $domanda_doc){

            $domanda_IDDom = $domanda_doc->getElementsByTagName('IDDom')->item(0)->nodeValue;
            $domanda_dataDom = $domanda_doc->getElementsByTagName('dataDom')->item(0)->nodeValue;
        
            if($domanda_IDDom == $ID && $domanda_dataDom == $data){

                $domanda_doc->getElementsByTagName('segnalazione')->item(0)->nodeValue = 0;
                break;
            }
            
        }
    }

    //ci troviamo nel caso di segnalazione di una risposta
    else if(isset($_GET['from']) && $_GET['from'] == "risposta"){

        foreach($domande_doc as $domanda_doc){

            $risposteNodes = $domanda_doc->getElementsByTagName('risposta');
                    
            foreach ($risposteNodes as $rispostaNode) {
        
                $IDRisp = $rispostaNode->getElementsByTagName('IDRisp')->item(0)->nodeValue;
                $dataRisp = $rispostaNode->getElementsByTagName('dataRisp')->item(0)->nodeValue;
        
                if($IDRisp == $ID && $dataRisp == $data){

                    $rispostaNode->getElementsByTagName('segnalazione')->item(0)->nodeValue = 0;
                    break;
                }
            }
        }
    }

    $document->save($xmlpath);

    $_SESSION['ban_ok'] = true;
    header('Location:../../gestione_segnalazioni.php');
    exit(1);
} 

//in questo caso devo bannare l'utente e rendere la recensione non visibile, mettendo il campo segnalazione del commento su 0
if(isset($_POST['bottone_ban_ok']) && isset($_GET['from']) && $_GET['from'] == "recensione"){
    
    //1° STEP metto il record SQL bannato dell'utente su 'true'
    $sql = "UPDATE utenteMangaNett 
    SET ban = 1
    WHERE username = '$username'";

    if ($connessione->query($sql)) {} 
    else {
        $_SESSION['errore_query'] = 'true';
        header('Location:../../gestione_segnalazione.php');
        exit(1);
    }

    //2° STEP metto il campo segnalazione a 0
    //per fare ciò devo capire se devo controllare una domanda o una risposta
    $xmlpath = "../XML/catalogo.xml";

    //devo caricarmi anche il file originale in modo tale da sovrascriverlo successivamente
    $document = new DOMDocument();
    $document->load($xmlpath);

    $fumetti_doc = $document->getElementsByTagName('fumetto');
    
    foreach($fumetti_doc as $fumetto_doc){

        $recensioni_doc = $fumetto_doc->getElementsByTagName('recensione');
    
        foreach($recensioni_doc as $recensione_doc){

            $IDRec = $recensione_doc->getElementsByTagName('IDRecensore')->item(0)->nodeValue;
            $dataRec = $recensione_doc->getElementsByTagName('dataRecensione')->item(0)->nodeValue;

            if($IDRec == $ID && $dataRec == $data){

                $recensione_doc->getElementsByTagName('segnalazione')->item(0)->nodeValue = 0;
                break;
            }
        }
    }
    

    $document->save($xmlpath);

    $_SESSION['ban_ok'] = true;
    header('Location:../../gestione_segnalazioni.php');
    exit(1);

}
  
//in questo caso NON devo bannare l'utente e rimuovere la segnalazione
//quindi devo fare solo il 2° step del caso precedente
if (isset($_POST['bottone_ban_ko']) && isset($_GET['from']) && $_GET['from'] != "recensione") {
    
    $xmlpath = "../XML/Q&A.xml";

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
        
            if($domanda_IDDom == $ID && $domanda_dataDom == $data){

                $domanda_doc->getElementsByTagName('segnalazione')->item(0)->nodeValue = -1;
                break;
            }
        }
    }

    //ci troviamo nel caso di segnalazione di una risposta
    else if(isset($_GET['from']) && $_GET['from'] == "risposta"){

        foreach($domande_doc as $domanda_doc){

            $risposteNodes = $domanda_doc->getElementsByTagName('risposta');
                    
            foreach ($risposteNodes as $rispostaNode) {
        
                $IDRisp = $rispostaNode->getElementsByTagName('IDRisp')->item(0)->nodeValue;
                $dataRisp = $rispostaNode->getElementsByTagName('dataRisp')->item(0)->nodeValue;
        
                if($IDRisp == $ID && $dataRisp == $data){

                    $rispostaNode->getElementsByTagName('segnalazione')->item(0)->nodeValue = -1;
                    break;
                }
            }
        }
    }

    $document->save($xmlpath);

    $_SESSION['noban_ok'] = true;
    header('Location:../../gestione_segnalazioni.php');
    exit(1);
}

if(isset($_POST['bottone_ban_ko']) && isset($_GET['from']) && $_GET['from'] == "recensione"){
    
    $xmlpath = "../XML/catalogo.xml";

    //devo caricarmi anche il file originale in modo tale da sovrascriverlo successivamente
    $document = new DOMDocument();
    $document->load($xmlpath);

    $fumetti_doc = $document->getElementsByTagName('fumetto');
    
    foreach($fumetti_doc as $fumetto_doc){

        $recensioni_doc = $fumetto_doc->getElementsByTagName('recensione');
    
        foreach($recensioni_doc as $recensione_doc){

            $IDRec = $recensione_doc->getElementsByTagName('IDRecensore')->item(0)->nodeValue;
            $dataRec = $recensione_doc->getElementsByTagName('dataRecensione')->item(0)->nodeValue;

            if($IDRec == $ID && $dataRec == $data){

                $recensione_doc->getElementsByTagName('segnalazione')->item(0)->nodeValue = 0;
                break;
            }
        }
    }

    $document->save($xmlpath);

    $_SESSION['noban_ok'] = true;
    header('Location:../../gestione_segnalazioni.php');
    exit(1);
}

?>