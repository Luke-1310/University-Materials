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
            break;

            $immagineDaEliminare = "../WEBSITE_MEDIA/PRODUCT_MEDIA/{$ISBN_prodotto}.jpg";

            if (file_exists($immagineDaEliminare)) {
                unlink($immagineDaEliminare);
            }
        }
    }


// Salva il documento XML aggiornato nel file
$document->save($pathXml);

// header('Location:../../prodotti_info.php');

?>
