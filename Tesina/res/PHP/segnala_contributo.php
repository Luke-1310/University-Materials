<?php

session_start();

include("funzioni.php");
include("connection.php");

//mi prendo i dati tramite POST
$ID = $_POST['ID'];
$data = $_POST['data'];

//siccome devo inserire l'ID del segnalatore, ovvero dell'utente corrente devo ricavarmelo
$connessione = new mysqli($host, $user, $password, $db);

$query = "SELECT umn.id FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.username = '{$_SESSION['nome']}'";
$result = $connessione->query($query);

//Verifico se la query ha restituito risultati
if ($result) {

    $row = $result->fetch_assoc();
    $idSegn = $row['id'];
} 

else {
    echo "Errore nella query: " . $connessione->error;
}

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

                $domanda_doc->getElementsByTagName('segnalazione')->item(0)->nodeValue = 1;
                $domanda_doc->getElementsByTagName('IDSegnalatore')->item(0)->nodeValue = $idSegn;
                break;
            }
        }
    }
    
    $document->save($xmlpath);
    
    $_SESSION['segnalazione_ok'] = true;
    header('Location:../../mostra_domande_prodotto.php');
    exit(1);
}

//ci troviamo nel caso di segnalazione di una risposta
else if(isset($_GET['from']) && $_GET['from'] == "risposta"){

    foreach($domande_doc as $domanda_doc){

        $risposteNodes = $domanda_doc->getElementsByTagName('risposta');
                
        foreach ($risposteNodes as $rispostaNode) {
    
            $IDRisp = $rispostaNode->getElementsByTagName('IDRisp')->item(0)->nodeValue;
            $dataRisp = $rispostaNode->getElementsByTagName('dataRisp')->item(0)->nodeValue;
    
            if($IDRisp == $ID && $dataRisp == $data){

                $rispostaNode->getElementsByTagName('segnalazione')->item(0)->nodeValue = 1;
                $rispostaNode->getElementsByTagName('IDSegnalatore')->item(0)->nodeValue = $idSegn;
                break;
            }
        }
    }

    $document->save($xmlpath);
    
    $_SESSION['segnalazione_ok'] = true;
    header('Location:../../mostra_domande_prodotto.php');
    exit(1);
}


?>