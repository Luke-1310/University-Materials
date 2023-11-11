<?php

session_start();

include('connection.php');
include('funzioni.php');

$response = $_POST['risposta'];

//al solito, devo prendermi l'id e la data della risposta 
$date = $_POST['data'];

//e anche l'isbn
$isbn = $_POST['isbn'];

//id rispondente
$connessione = new mysqli($host, $user, $password, $db);

$query ="SELECT umn.id FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";

$ris = $connessione->query($query);

if(mysqli_num_rows($ris) == 1){
    $row = $ris->fetch_assoc();
    $id = $row['id'];
}
else{
    header('Location:../../homepage.php');
}

//bene ora che ho tutti i dati devo trovare la domanda corretta ed inserire la risposta
$xmlfile = "../XML/catalogo.xml";  //Percorso del file XML

$fumetti = getFumetti($xmlfile);

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

    $dataDomNode = $domanda->getElementsByTagName('dataDom')[0]->nodeValue;
    $ISBNProdottoNode = $domanda->getElementsByTagName('ISBNProdotto')[0]->nodeValue;

    //un controllo incrociato con ISBN e data-ora dovrebbe bastare
    if($dataDomNode == $date && $ISBNProdottoNode  == $isbn){
        
        $rispostaElement = $document->createElement('risposta');

        $utenteIDElement = $document->createElement('IDRisp', $id);
        $rispostaElement->appendChild($utenteIDElement);
        
        $FAQ = "-1";
        $FAQElement = $document->createElement('FAQ', $FAQ);
        $rispostaElement->appendChild($FAQElement);

        $segnalazione = "-1";
        $segnalazioneElement = $document->createElement('segnalazione', $segnalazione);
        $rispostaElement->appendChild($segnalazioneElement);

        $IDSegnalatore = "-1";
        $IDSegnalatoreElement = $document->createElement('IDSegnalatore', $IDSegnalatore);
        $rispostaElement->appendChild($IDSegnalatoreElement);

        $date_c = date('Y-m-d\TH:i:s');
        $dataElement = $document->createElement('dataRisp', $date_c);
        $rispostaElement->appendChild($dataElement);

        $testoElement = $document->createElement('testoRisp', $response);
        $rispostaElement->appendChild($testoElement);

        $domanda->appendChild($rispostaElement);
        break;
    }
}

$document->formatOutput = true; 
$xml = $document->saveXML();

file_put_contents($xmlpath, $xml); 

$_SESSION['richiesta_ok'] = true;
header('Location:../../mostra_domande_prodotto.php');

?>