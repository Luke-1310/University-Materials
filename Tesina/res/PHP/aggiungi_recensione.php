<?php

session_start();

include("connection.php");
include('funzioni.php');

$connessione = new mysqli($host, $user, $password, $db);

$recensoreID = $_POST['ID'];
$recensione_testo = $_POST['recensione'];
    
$query = "SELECT umn.reputazione FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.username = '{$_SESSION["nome"]}'";
$result = $connessione->query($query);

if ($result) {

    $row = $result->fetch_assoc();
    $reputazione_recensore = $row['reputazione'];
} 
else {
    echo "Errore nella query: " . $connessione->error;
}

$xmlpath = "../XML/catalogo.xml";
$xmlstring = "";

foreach(file($xmlpath) as $nodo){   //Leggo il contenuto del file XML

    $xmlstring.= trim($nodo); 
}

$document = new DOMDocument();
$document->loadXML($xmlstring);

$fumetti_doc = $document->getElementsByTagName('fumetto');

foreach ($fumetti_doc as $fumetto_doc) {

    $fumetto_titolo = $fumetto_doc->getElementsByTagName('titolo')->item(0)->nodeValue;

    if ($_SESSION['info_titolo'] === $fumetto_titolo) {

        $recensioneElement = $document->createElement('recensione');

        $recensoreIDElement = $document->createElement('IDRecensore', $recensoreID);
        $recensioneElement->appendChild($recensoreIDElement);

        $dataRec = date('Y-m-d\TH:i:s');
        $dataElement = $document->createElement('dataRecensione', $dataRec);
        $recensioneElement->appendChild($dataElement);

        $testoElement = $document->createElement('testoRecensione', $recensione_testo);
        $recensioneElement->appendChild($testoElement);

        $reputazioneElement = $document->createElement('reputazioneRecensore', $reputazione_recensore);
        $recensioneElement->appendChild($reputazioneElement);
        
        $segnalazione = "-1";
        $segnalazioneElement = $document->createElement('segnalazione', $segnalazione);
        $recensioneElement->appendChild($segnalazioneElement);

        $IDSegnalatore = "-1";
        $IDSegnalatoreElement = $document->createElement('IDSegnalatore', $IDSegnalatore);
        $recensioneElement->appendChild($IDSegnalatoreElement);

        $fumetto_doc->appendChild($recensioneElement);
        break;
    }
}

$document->formatOutput = true; 
$xml = $document->saveXML();

file_put_contents($xmlpath, $xml); 

$_SESSION['richiesta_ok']= true;
header('Location:../../prodotti_info.php');
?>