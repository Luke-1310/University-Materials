<?php

session_start();

$IDValutante = $_POST['IDValutante'];
$IDRisp = $_POST['IDRisp'];
$dataRisp = $_POST['dataRisp'];
$utilita = $_POST['utilita'];
$supporto = $_POST['supporto'];

include('connection.php');

$connessione = new mysqli($host, $user, $password, $db);

$query ="SELECT umn.reputazione FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";

$ris = $connessione->query($query);

if(mysqli_num_rows($ris) == 1){
    $row = $ris->fetch_assoc();
    $reputazione = $row['reputazione'];
}
else{
    header('Location:../../homepage.php');
}

//bene, ora bisogna aggiornare il file XML 
$xmlpath ="../XML/Q&A.xml";
$xmlstring = "";

foreach(file($xmlpath) as $nodo){   //Leggo il contenuto del file XML

    $xmlstring.= trim($nodo); 
}

$document = new DOMDocument();
$document->loadXML($xmlstring);

$elenco = $document->getElementsByTagName('domanda');

foreach($elenco as $domanda){

    $risposte = $domanda->getElementsByTagName('risposta');

    foreach($risposte as $risposta){
        
        $IDRisp_current = $risposta->getElementsByTagName('IDRisp')->item(0)->nodeValue;
        $date_current = $risposta->getElementsByTagName('dataRisp')->item(0)->nodeValue;

        //condizione per trovare la giusta risposta nella quale mettere la valutazione
        if($IDRisp_current == $IDRisp && $date_current == $dataRisp){

            $votazione = $document->createElement('votazione');

            $IDV = $document->createElement('IDValutante');
            $rep = $document->createElement('reputazione');
            $uti = $document->createElement('utilita');
            $sup = $document->createElement('supporto');

            $IDV->nodeValue = $IDValutante;
            $votazione->appendChild($IDV);

            $rep->nodeValue = $reputazione;
            $votazione->appendChild($rep);

            $uti->nodeValue = $utilita;
            $votazione->appendChild($uti);

            $sup->nodeValue = $supporto;
            $votazione->appendChild($sup);

            $risposta->appendChild($votazione);
        }
    }
}

$document->formatOutput = true; 
$xml = $document->saveXML();

file_put_contents($xmlpath, $xml); 

header('Location: ../../mostra_domanda_specifica.php');

?>