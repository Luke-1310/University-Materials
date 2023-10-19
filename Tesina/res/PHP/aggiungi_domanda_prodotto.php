<?php

session_start();

include('connection.php');
include('funzioni.php');

$titolo = $_POST['titolo'];
$text = $_POST['testo'];
$date = $_POST['data'];

//mi mancano l'id e di convertire il titolo con l'isbn corrispettivo

$xmlpath = "../XML/catalogo.xml";
$fumetti = getFumetti($xmlpath);

foreach($fumetti as $fumetto){

    if($fumetto['titolo'] == $titolo){
        $isbn_e = $fumetto['isbn'];
    }

}

//mi prendo l'ID dell'utente corrente
$connessione = new mysqli($host, $user, $password, $db);

$query ="SELECT umn.id FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";

$ris = $connessione->query($query);

if(mysqli_num_rows($ris) == 1){
    $row = $ris->fetch_assoc();
    $id_e = $row['id'];
}
else{
    header('Location:../../homepage.php');
}

//ora che ho tutto mi preparo all'inserimento della domanda nel xml
$xmlfile = "../XML/Q&A.xml";
$xmlstring = "";

foreach(file($xmlfile) as $nodo){   //Leggo il contenuto del file XML

    $xmlstring.= trim($nodo); 
}

//valore di default di FAQ
$FAQ_e = -1;

$documento = new DOMDocument();
$documento->loadXML($xmlstring);

$elenco = $documento->documentElement;

$domanda = $documento->createElement('domanda');

$isbn = $documento->createElement('ISBNProdotto');
$FAQ = $documento->createElement('FAQ');
$id = $documento->createElement('IDDom');
$testo = $documento->createElement('testoDom');
$data = $documento->createElement('dataDom');

$isbn->nodeValue = $isbn_e;
$domanda->appendChild($isbn);

$FAQ->nodeValue = $FAQ_e;
$domanda->appendChild($FAQ);

$id->nodeValue = $id_e;
$domanda->appendChild($id);

$testo->nodeValue = $text;
$domanda->appendChild($testo);

$data->nodeValue = $date;
$domanda->appendChild($data);

$elenco->appendChild($domanda);

$documento->formatOutput = true; 
$xml = $documento->saveXML();

file_put_contents($xmlfile, $xml); 

header('Location:../../mostra_domande_prodotto.php');
?>