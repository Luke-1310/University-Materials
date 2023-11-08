<?php

session_start();

include('connection.php');
include('funzioni.php');

$ID_POST = $_POST['IDRisp'];
$data_POST = $_POST['dataRisp'];

$xmlpath = "../XML/Q&A.xml";

//ora che ho tutto mi preparo all'inserimento della domanda nel xml
$document = new DOMDocument();
$document->load($xmlpath);

$domande_doc = $document->getElementsByTagName('domanda');

foreach($domande_doc as $domanda_doc){

    $risposte = $domanda_doc->getElementsByTagName('risposta');
            
    foreach ($risposte as $risposta) {

        $IDRisp = $risposta->getElementsByTagName('IDRisp')->item(0)->nodeValue;
        $dataRisp = $risposta->getElementsByTagName('dataRisp')->item(0)->nodeValue;

        if($IDRisp == $ID_POST && $dataRisp == $data_POST){
            // echo "SONO DENTRO L'IF CONCLUSIVO";
            $risposta->getElementsByTagName('FAQ')->item(0)->nodeValue = 1;
            break;
        }
        // echo "SONO DOPO L'IF    ";
    }
}

$document->save($xmlpath);

header('Location:../../FAQ.php');
?>