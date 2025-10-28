<?php

session_start();

$crediti = $_POST['crediti'];
$dataCorrente = date('Y-m-d\TH:i:s');
//la prima cosa che bisogna fare è controllare se il campo "crediti" è vuoto
if($_POST['crediti'] == ""){

    $_SESSION['errore_storico_cr'] = 'true';
    header('Location:../../storico_crediti.php'); //header sono l'analogo degli href
    exit(1);
}

//ora quello che bisogna fare è prendere tutti i campi necessari per salvare la richiesta

//inizio prendendomi l'ID dell'utente corrente
require('connection.php');

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



// La funzione trim() viene utilizzata per rimuovere eventuali spazi bianchi iniziali o finali dal contenuto di ogni riga del file XML prima di concatenarlo alla variabile $xmlstring.
$xmlfile = "../XML/richieste_crediti.xml";  //Percorso del file XML
$xmlstring = "";

foreach(file($xmlfile) as $nodo){   //Leggo il contenuto del file XML

    $xmlstring.= trim($nodo); 
}

$documento = new DOMDocument();
$documento->loadXML($xmlstring);    //Carico il contenuto del file XML dentro $documento per poterlo manipolare tramite DOM

$storico = $documento->documentElement;

$richiesta = $documento->createElement('richiesta');

$IDUtente = $documento->createElement('IDUtente');
$quantita = $documento->createElement('quantita');
$data = $documento->createElement('dataRichiesta');
$risposta = $documento->createElement('risposta');

//bene ora inseriamo i valori
$IDUtente->nodeValue = $row['id'];
$quantita->nodeValue = $crediti;
$data->nodeValue = $dataCorrente;
$risposta->nodeValue = 0;

$richiesta->appendChild($IDUtente);
$richiesta->appendChild($quantita);
$richiesta->appendChild($data);
$richiesta->appendChild($risposta);

$storico->appendChild($richiesta);

$documento->formatOutput = true;    //Formatta il documento XML per renderlo più leggibile
$xml = $documento->saveXML();   //saveXML è un metodo che restituisce il documento XML come una stringa di testo (formattata)

file_put_contents($xmlfile, $xml);  //sovrascrive il contenuto del vecchio file XML con quello nuovo

$_SESSION['richiesta_ok'] = true;

header('Location:../../storico_crediti.php');


?>