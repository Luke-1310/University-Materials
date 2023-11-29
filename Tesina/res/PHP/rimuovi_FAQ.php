<?php

session_start();

include('connection.php');
include('funzioni.php');

$IDDom = $_POST['IDDom'];
$dataDom = $_POST['dataDom'];

//mi mancano l'id e di convertire il titolo con l'isbn corrispettivo

$xmlpath = "../XML/Q&A.xml";

//ora che ho tutto mi preparo all'inserimento della domanda nel xml
$document = new DOMDocument();
$document->load($xmlpath);

$domande_doc = $document->getElementsByTagName('domanda');

//per ciascuna domanda del documento controllo se è la domanda giusta e la elevo a FAQ
foreach($domande_doc as $domanda_doc){

    $domanda_IDDom = $domanda_doc->getElementsByTagName('IDDom')->item(0)->nodeValue;
    $domanda_dataDom = $domanda_doc->getElementsByTagName('dataDom')->item(0)->nodeValue;

    if($domanda_IDDom == $IDDom && $domanda_dataDom == $dataDom){

        $domanda_doc->getElementsByTagName('FAQ')->item(0)->nodeValue = 0;

        $risposte = $domanda_doc->getElementsByTagName('risposta');

        foreach($risposte as $risposta){
            $risposta->getElementsByTagName('FAQ')->item(0)->nodeValue = 0;
        }
        
        $_SESSION['FAQ_rimozione_ok'] = true;

        break;
    }
}

$document->save($xmlpath);

header('Location:../../FAQ.php');
?>