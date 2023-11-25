<?php

session_start();

include('connection.php');
include('funzioni.php');

$connessione = new mysqli($host, $user, $password, $db);

$importoDaPagare = $_POST['daPagare'];
$bonusDaAccreditare = $_POST['totaleBonus'];

//ora bisogna fare dei controlli ed eventualemente confermare l'ordine

//controllo se l'utente può pagare con i crediti che ha in quel momento
$query = "SELECT umn.crediti FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.username = '{$_SESSION['nome']}'";
$result = $connessione->query($query);

if ($result) {
    $row = $result->fetch_assoc();
} 

if($row['crediti'] < $importoDaPagare){
    $_SESSION['crediti_insufficienti'] = true;
    exit(1);
}
else{
    $crediti_rimanenti = $row['crediti'] - $importoDaPagare;
}

//controllo ora se le quantita sono sufficienti per poterle scalare ed aggiornare l'XML
$pathXml = "../XML/catalogo.xml";

$document = new DOMDocument();
$document->load($pathXml);

$fumetti_doc = $document->getElementsByTagName('fumetto');

foreach($_SESSION['carrello'] as $fumetto_carrello){

    foreach ($fumetti_doc as $fumetto_doc) {

        $fumetto_isbn = $fumetto_doc->getAttribute('isbn');
        
        if($fumetto_carrello['isbn'] == $fumetto_isbn){

            $fumetto_quantita = $fumetto_doc->getElementsByTagName('quantita')->item(0)->nodeValue;

            if($fumetto_carrello['quantita'] <= $fumetto_quantita){

                $fumetto_quantita -= $fumetto_carrello['quantita'];
                $fumetto_doc->getElementsByTagName('quantita')->item(0)->nodeValue = $fumetto_quantita;
            }
            else{
                $_SESSION['quantita_insufficienti'] = $fumetto_quantita;
                // header('Location: ../../catalogo.php');
                exit(1);
            }
        }
    }
}

//query per aggiornare i crediti dell'utente
$query = "UPDATE utenteMangaNett 
SET crediti = '$crediti_rimanenti' 
WHERE username = '{$_SESSION['nome']}'";

$result = $connessione->query($query);

if (!$result) {
    echo "Errore nella query: " . $connessione->error;
    exit(1);
} 

// Salva il documento XML aggiornato nel file
$document->save($pathXml);

$query = "SELECT umn.id FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.username = '{$_SESSION['nome']}'";
$result = $connessione->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $id = $row['id'];
} 
else{
    echo 3;
}


//ora devo aggiornare lo storico acquisti
$xmlfile = "../XML/storico_acquisti.xml";  
$xmlstring = "";

foreach(file($xmlfile) as $nodo){   

    $xmlstring.= trim($nodo); 
}

$documento = new DOMDocument();
$documento->loadXML($xmlstring);    

$storico = $documento->documentElement;   

$acquisto = $documento->createElement('acquisto'); 

$IDUtente = $documento->createElement('IDUtente');
$data = $documento->createElement('data');
$bonus = $documento->createElement('bonus');

$IDUtente->nodeValue = $id; 
$acquisto->appendChild($IDUtente);

$dataCorrente = date('Y-m-d\TH:i:s');
$data->nodeValue = $dataCorrente; 
$acquisto->appendChild($data);

$bonus->nodeValue = $bonusDaAccreditare; 
$acquisto->appendChild($bonus);


foreach($_SESSION['carrello'] as $fumetto_carrello){
    
    $fumetto = $documento->createElement('fumetto');

    $titolo = $documento->createElement('titolo');
    $isbn = $documento->createElement('isbn');
    $quantita = $documento->createElement('quantita');
    $prezzo = $documento->createElement('prezzo');

    $titolo->nodeValue = $fumetto_carrello['titolo']; 
    $fumetto->appendChild($titolo);

    $isbn->nodeValue = $fumetto_carrello['isbn']; 
    $fumetto->appendChild($isbn);

    $quantita->nodeValue = $fumetto_carrello['quantita']; 
    $fumetto->appendChild($quantita);

    $prezzo->nodeValue = $fumetto_carrello['prezzo_scontato']; 
    $fumetto->appendChild($prezzo);

    $acquisto->appendChild($fumetto);
}

$storico->appendChild($acquisto);  

$documento->formatOutput = true;    
$xml = $documento->saveXML();   

file_put_contents($xmlfile, $xml);

// Salva il documento XML aggiornato nel file
$document->save($pathXml);

unset($_SESSION['carrello']);

$_SESSION['ordine completato'] = true;

// header('Location: ../../catalogo.php');
?>