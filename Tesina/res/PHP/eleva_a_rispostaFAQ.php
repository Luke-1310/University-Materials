<?php

session_start();

include('connection.php');
include('funzioni.php');

$ID_POST = $_POST['IDRisp'];
$data_POST = $_POST['dataRisp'];

//mi mancano l'id e di convertire il titolo con l'isbn corrispettivo

$xmlpath = "../XML/Q&A.xml";
$domande = getDomande($xmlpath);

//ora che ho tutto mi preparo all'inserimento della domanda nel xml
$document = new DOMDocument();
$document->load($xmlpath);

$domande_doc = $document->getElementsByTagName('domanda');

foreach($domande_doc as $domanda_doc){

    $risposteNodes = $domanda_doc->getElementsByTagName('risposta');
            
    foreach ($risposteNodes as $rispostaNode) {

        $IDRisp = $rispostaNode->getElementsByTagName('IDRisp')->item(0)->nodeValue;
        $dataRisp = $rispostaNode->getElementsByTagName('dataRisp')->item(0)->nodeValue;

        if($IDRisp == $ID_POST && $data_POST == $dataRisp){
            $rispostaNode->getElementsByTagName('FAQ')->item(0)->nodeValue = 1;
            break;
        }
    }
}

$document->save($xmlpath);

header('Location:../../FAQ.php');
?>