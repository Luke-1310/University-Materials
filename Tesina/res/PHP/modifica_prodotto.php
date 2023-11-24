<?php

session_start();

//Recupero i dati inviati tramite il form HTML in aggiungi_prodotto.php
$title = $_POST['titolo'];
$ISBN = $_POST['ISBN'];
$name = $_POST['nome'];
$surname = $_POST['cognome'];
$description = $_POST['sinossi'];
$lenght = $_POST['lunghezza'];
$price = $_POST['prezzo'];
$date = $_POST['data'];
$quantity = $_POST['quantita'];
$publisher = $_POST['editrice'];

include('../PHP/funzioni.php');

$pathXml = "../XML/catalogo.xml";

$fumetti = getFumetti($pathXml);

//devo fare dei controlli, titolo e sul isbn

// Controllo se l'utente ha inserito un'immagine
if ($_FILES["img"]["size"] > 0) {
    
    // Verifica l'estensione del file per consentire solo immagini JPG
    $dirDestinazione = "../WEBSITE_MEDIA/PRODUCT_MEDIA/";

    $targetFile = $dirDestinazione . basename($_FILES["img"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedExtensions = array("jpg");

    if (!in_array($imageFileType, $allowedExtensions)) {
        $_SESSION['errore_typeFile'] = true;
        header('Location: ../../modifica_prodotto.php');
        exit();
    }

    $nuovoNomeImg = $ISBN;
    $ext = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
    $targetFile = $dirDestinazione . $nuovoNomeImg . "." . $ext;

    if (!move_uploaded_file($_FILES["img"]["tmp_name"], $targetFile)) {
        $_SESSION['errore_upload'] = true;
        header('Location: ../../modifica_prodotto.php');
        exit();
    }

    // Se l'utente ha caricato un'immagine, salva il nome dell'immagine nel tuo array
    $image = $nuovoNomeImg;
}
else{
    //voglio evitare che cambiando l'isbn si perda l'associazione con l'immagine e quindi la rinomino col isbn del post
    $dirDestinazione = "../WEBSITE_MEDIA/PRODUCT_MEDIA/";
    $ext = "jpg";

    $vecchioFile = $dirDestinazione . $_SESSION['vecchio_isbn'] . "." . $ext;
    $nuovoFile = $dirDestinazione . $ISBN . "." . $ext;

    if (file_exists($vecchioFile)) {

        rename($vecchioFile, $nuovoFile);
        $image = $ISBN;
    }
    else{
        $_SESSION['errore_img_nonEsiste'] = true;
    }
}


//i controlli sono andati a buon fine e per tale motivo procedo a modificare i dati del fumetto nell'array fumetti e poi sovrascrivo
foreach($fumetti as &$fumetto){

    //in $_SESSION['info_titolo'] ci dovrebbe essere il titolo del manga che si vuole modificare
    //prendo info_titolo perché quello del form potrebbe essere lo stesso titolo come non e per tale
    //motivo con info_titolo dovrei aver il corretto titolo e così modifico tutto
    if($fumetto['titolo'] == $_SESSION['info_titolo']){
        
        $fumetto['titolo'] = $title;
        $fumetto['isbn'] = $ISBN;
        $fumetto['nome_autore'] = $name;
        $fumetto['cognome_autore'] = $surname;
        $fumetto['sinossi'] = $description;
        $fumetto['lunghezza'] = $lenght;
        $fumetto['prezzo'] = $price;
        $fumetto['data'] = $date;
        $fumetto['quantita'] = $quantity;
        $fumetto['editore'] = $publisher;

        $fumetto['img'] = $ISBN;

        break;
    }
}

//bene, ora che l'array che contiene i fumetti è stato aggiornato bisogna aggiornare il file XML 
$document = new DOMDocument();
$document->load($pathXml);

//Mi prendo tutti gli elementi 'fumetto' nel documento
$fumetti_doc = $document->getElementsByTagName('fumetto');

    foreach ($fumetti_doc as $fumetto_doc) {
    // Ottieni l'attributo 'titolo' dell'elemento 'fumetto'

        $fumetto_titolo = $fumetto_doc->getElementsByTagName('titolo')->item(0)->nodeValue;

        if ($_SESSION['info_titolo'] === $fumetto_titolo) {

            $fumetto_doc->setAttribute('isbn', $fumetto['isbn']);

            $fumetto_doc->getElementsByTagName('titolo')->item(0)->nodeValue = $fumetto['titolo'];

            $autore = $fumetto_doc->getElementsByTagName('autore')->item(0);
            $fumetto_doc->getElementsByTagName('autore')->item(0)->getElementsByTagName('nome')->item(0)->nodeValue = $name;
            $fumetto_doc->getElementsByTagName('autore')->item(0)->getElementsByTagName('cognome')->item(0)->nodeValue = $surname;

            $fumetto_doc->getElementsByTagName('sinossi')->item(0)->nodeValue = $fumetto['sinossi'];
            $fumetto_doc->getElementsByTagName('lunghezza')->item(0)->nodeValue = $fumetto['lunghezza'];
            $fumetto_doc->getElementsByTagName('prezzo')->item(0)->nodeValue = $fumetto['prezzo'];
            $fumetto_doc->getElementsByTagName('data')->item(0)->nodeValue = $fumetto['data'];
            $fumetto_doc->getElementsByTagName('quantita')->item(0)->nodeValue = $fumetto['quantita'];
            $fumetto_doc->getElementsByTagName('editore')->item(0)->nodeValue = $fumetto['editore'];

            $fumetto_doc->getElementsByTagName('img')->item(0)->nodeValue = $fumetto['img'];

            break; 
        }
    }


// Salva il documento XML aggiornato nel file
$document->save($pathXml);

//La variabile di sessione va aggiornata per far si che non si crei un errore se si aggiorna il titolo del fumetto
$_SESSION['info_titolo'] = $title;

header('Location:../../prodotti_info.php');

?>
