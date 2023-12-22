<?php

$isbn = $_POST['isbn'];
$rating = $_POST['rating'];
$nome = $_POST['nomeAutore'];
$cognome = $_POST['cognomeAutore'];
$titolo = $_POST['titolo'];
$anno = $_POST['anno'];
$editore = $_POST['editore'];

$xmlfile = "biblioteca.xml";  //Percorso del file XML
$xmlstring = "";

foreach(file($xmlfile) as $nodo){   //Leggo il contenuto del file XML

    $xmlstring.= trim($nodo); 
}

$documento = new DOMDocument();
$documento->loadXML($xmlstring);    //Carico il contenuto del file XML dentro $documento per poterlo manipolare tramite DOM

$libri = $documento->documentElement;   //Nodo radice del documento XML

$book = $documento->createElement('book'); 

$book->setAttribute('isbn', $isbn);    
$book->setAttribute('rating', $rating);    

$autore = $documento->createElement('autore');
$nomeAutore = $documento->createElement('nome', $nome);
$cognomeAutore = $documento->createElement('cognome', $cognome);
$autore->appendChild($nomeAutore);
$autore->appendChild($cognomeAutore);

$titoloElem = $documento->createElement('titolo', $titolo);
$annoElem = $documento->createElement('anno', $anno);
$editoreElem = $documento->createElement('editore', $editore);

$book->appendChild($autore);
$book->appendChild($titoloElem);

if (!empty($anno)) {
    $book->appendChild($annoElem);
}
else{
    $book->appendChild("NON INSERITO");
}

$book->appendChild($editoreElem);

$root = $documento->documentElement;
$root->appendChild($book);

$documento->formatOutput = true;    
$xml = $documento->saveXML();   

file_put_contents($xmlfile, $xml);  

header('Location:homepage.php');
?>