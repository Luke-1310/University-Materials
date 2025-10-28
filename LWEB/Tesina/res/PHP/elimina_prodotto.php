<?php

session_start();

$ISBN_prodotto = $_POST['isbn'];

$pathXml = "../XML/catalogo.xml";

//bene, ora che l'array che contiene i fumetti è stato aggiornato bisogna aggiornare il file XML 
$document = new DOMDocument();
$document->load($pathXml);

//Mi prendo tutti gli elementi 'fumetto' nel documento
$fumetti_doc = $document->getElementsByTagName('fumetto');

foreach ($fumetti_doc as $fumetto_doc) {

    $fumetto_isbn = $fumetto_doc->getAttribute('isbn');

    if ($ISBN_prodotto === $fumetto_isbn){

        $fumettoDaRimuovere = $fumetto_doc;
        $fumettoDaRimuovere->parentNode->removeChild($fumettoDaRimuovere);

        $immagineDaEliminare = "../WEBSITE_MEDIA/PRODUCT_MEDIA/{$ISBN_prodotto}.jpg";

        if (file_exists($immagineDaEliminare)) {
            unlink($immagineDaEliminare);
        }

        break;
    }
}


// Salva il documento XML aggiornato nel file
$document->save($pathXml);

//ora cancello qualsiasi domanda con quel isbn, cancellando la domanda cancello anche le relative risposte e valutazioni
$pathXml = "../XML/Q&A.xml";

$document = new DOMDocument();
$document->load($pathXml);

$domande_doc = $document->getElementsByTagName('domanda');

foreach ($domande_doc as $domanda_doc) {

    $domanda_isbn = $domanda_doc->getElementsByTagName('ISBNProdotto')->item(0)->nodeValue;

    if ($ISBN_prodotto === $domanda_isbn){

        $domandaDaRimuovere = $domanda_doc;
        $domandaDaRimuovere->parentNode->removeChild($domandaDaRimuovere);
    }
}


// Salva il documento XML aggiornato nel file
$document->save($pathXml);

$_SESSION['prodotto_eliminato'] = true;

header('Location:../../catalogo.php');

?>
