<?php

session_start();

$title = $_POST['titolo'];
$review = $_POST['testo'];
$rating = $_POST['voto'];

$xmlfile = "../XML/libri.xml";
$xmlstring = "";

foreach(file($xmlfile) as $nodo){
    $xmlstring.= trim($nodo);
}

$documento = new DOMDocument();
$documento->loadXML($xmlstring); // Corretto il nome del metodo da 'loadXML' a 'loadXML'

$libri = $documento->documentElement;

$titleExists = false;

// Scansiona i nodi 'book' esistenti nel documento XML

foreach ($libri->getElementsByTagName('book') as $existingBook) {
    // Ottengo il titolo del libro corrente
    $existingTitle = $existingBook->getElementsByTagName('titolo')->item(0)->nodeValue;

    // Confronta il titolo del libro corrente con quello fornito dal form
    if ($existingTitle === $title) {
        $titleExists = true;
        break; // Esci dal ciclo se il titolo esiste già
    }
}

if (!$titleExists) { // Corretto il controllo della variabile $titleExists
    $_SESSION['errore_tt'] = true;
    header('Location: ../../recensione.php');
    exit();
}

$xpath = new DOMXPath($documento);

$query = "//book[titolo = '{$title}']";
$libri_corrispondenti = $xpath->query($query);

if ($libri_corrispondenti->length > 0) {
    $libro = $libri_corrispondenti->item(0);
    $recensione = $documento->createElement('recensione');

    $utente = $documento->createElement('utente', $_SESSION['nome']);
    $recensione->appendChild($utente);

    $rec = $documento->createElement('rec', $review); // Corretto il nome della variabile
    $recensione->appendChild($rec);

    $ratingElement = $documento->createElement('rating', $rating);
    $recensione->appendChild($ratingElement);

    $libro->appendChild($recensione);

    $documento->formatOutput = true;
    
    // Salva il documento XML modificato
    $documento->save($xmlfile); // Corretto il salvataggio del documento
}

header('Location:../../homepage.php');
exit();
?>
