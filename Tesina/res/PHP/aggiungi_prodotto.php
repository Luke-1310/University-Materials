<?php

session_start();

//Recupero i file inviati tramite il form HTML in aggiungi_prodotto.php
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

//mi salvo tutto in delle variabili di sessione per poter ricompilare il form
$_SESSION['form_titolo_prodotto'] = $title;
$_SESSION['form_ISBN_prodotto'] = $ISBN;
$_SESSION['form_nome_autore'] = $name;
$_SESSION['form_cognome_autore'] = $surname;
$_SESSION['form_sinossi_prodotto'] = $description;
$_SESSION['form_lunghezza_prodotto'] = $lenght;
$_SESSION['form_prezzo_prodotto'] = $price;
$_SESSION['form_data_prodotto'] = $date;
$_SESSION['form_quantita_prodotto'] = $quantity;
$_SESSION['form_editrice_prodotto'] = $publisher;

//come prima cosa mi voglio ricavare il valore che andrà nel campo img del file XML
//controllo se l'estensione del file è di tipo .jpg
$dirDestinazione = "../WEBSITE_MEDIA/PRODUCT_MEDIA/"; //Assegna la cartella di destinazione delle immagini
$targetFile = $dirDestinazione . basename($_FILES["img"]["name"]);

// Verifica l'estensione del file per consentire solo immagini JPG
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
$allowedExtensions = array("jpg");

if (in_array($imageFileType, $allowedExtensions)) {
    
    $nuovoNomeImg = $ISBN; //Assegna il nuovo nome dell'immagine il quale corrisponde all'ISBN, mi sembra un buon criterio 

    //$_FILES["img"]["name"] contiene il nome originale del file caricato dall'utente tramite il campo di input con name="img"
    $ext = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION); //Ricava l'estensione del file originale, 
    $targetFile = $dirDestinazione . $nuovoNomeImg . "." . $ext; //Percorso completo con il nuovo nome

    //Sposta il file nella directory di destinazione con il nuovo nome
    move_uploaded_file($_FILES["img"]["tmp_name"], $targetFile);

    //Mi prendo il nome dell'immagine e la metto in una nuova variabile per chiarire meglio il codice
    $image = $nuovoNomeImg;  
} 
  
else {
    $_SESSION['errore_typeFile'] = true;
    header('Location: ../../aggiungi_prodotto.php');
    exit();     //buon accorgimento per evitare che il resto del codice venga eseguito
}

// Il punto (.) prima dell'uguale (=) indica che stiamo concatenando il valore alla variabile anziché sostituire completamente il valore presente in esso.
// La funzione trim() viene utilizzata per rimuovere eventuali spazi bianchi iniziali o finali dal contenuto di ogni riga del file XML prima di concatenarlo alla variabile $xmlstring.
$xmlfile = "../XML/catalogo.xml";  //Percorso del file XML
$xmlstring = "";

foreach(file($xmlfile) as $nodo){   //Leggo il contenuto del file XML

    $xmlstring.= trim($nodo); 
}

$documento = new DOMDocument();
$documento->loadXML($xmlstring);    //Carico il contenuto del file XML dentro $documento per poterlo manipolare tramite DOM

$manga = $documento->documentElement;   //Nodo radice del documento XML

$fumetto = $documento->createElement('fumetto'); //Creo un nuovo elemento book il quale è composto da una sequenza di elementi

$titolo = $documento->createElement('titolo');
$autore = $documento->createElement('autore');
$nome = $documento->createElement('nome');
$cognome = $documento->createElement('cognome');
$sinossi = $documento->createElement('sinossi');
$lunghezza = $documento->createElement('lunghezza');
$prezzo = $documento->createElement('prezzo');
$data = $documento->createElement('data');
$quantita = $documento->createElement('quantita');
$img = $documento->createElement('img');
$editore = $documento->createElement('editore');
$sconto_generico = $documento->createElement('sconto_generico');

$bonus = $documento->createElement('bonus');

$sconto = $documento->createElement('sconto');

$X = $documento->createElement('X');
$Y = $documento->createElement('Y');
$M = $documento->createElement('M');
$M_data = $documento->createElement('data_M');
$N = $documento->createElement('N');
$R = $documento->createElement('R');
$ha_acquistato = $documento->createElement('ha_acquistato');

$titolo->nodeValue = $title;//Assegno al nodo titolo il contenuto della variabile $title -> titolo preso dal form html 

//Controllo se il titolo inserito già esiste nel file.xml

$titleExists = false;

//Scansiona i nodi 'fumetto' esistenti nel documento XML
foreach ($manga->getElementsByTagName('fumetto') as $existingComics) {

    //Ottiengo il titolo del fumetto corrente
    //item(0) perché si presume ci sia solo un elemento 'titolo' && ->nodoValue restituisce il contenuto testuale all'interno dell'elemento titolo
    $existingTitle = $existingComics->getElementsByTagName('titolo')->item(0)->nodeValue;    

    // Confronta il titolo del fumetto corrente con quello fornito dal form
    if ($existingTitle === $title) {

        $titleExists = true;
        break; // Esci dal ciclo se il titolo esiste già
    }
}

if ($titleExists) {

    $_SESSION['errore_ag_titolo'] = true;
    header('Location: ../../aggiungi prodotto.php');
    exit();     //buon accorgimento per evitare che il resto del codice venga eseguito
} 

// Controllo se l'ISBN inserito già esiste nel file xml
$isbnExists = false;

// Scansiona i nodi 'fumetto' esistenti nel documento XML

foreach ($manga->getElementsByTagName('fumetto') as $existingComics1) {

    // Ottieni l'ISBN del libro corrente
    $existingISBN = $existingComics1->getAttribute('isbn');

    // Confronta l'ISBN del libro corrente con quello fornito dal form

    if ($existingISBN === $ISBN) {

        $isbnExists = true;
        break; // Esci dal ciclo se l'ISBN esiste già
    }
}

if ($isbnExists) {

    $_SESSION['errore_ag_isbn'] = true;
    header('Location: ../../aggiungi_prodotto.php');
    exit();     //buon accorgimento per evitare che il resto del codice venga eseguito
} 

$fumetto->setAttribute('isbn', $ISBN);    // Imposto l'attributo 'isbn' del nodo 'fumetto' con il valore ottenuto dal form

$fumetto->appendChild($titolo);   //Aggiungo il nodo $titolo come figlio del nodo book

//Faccio lo stesso per gli altri nodi figli del nodo fumetto
$nome->nodeValue = $name;
$cognome->nodeValue = $surname;
$autore->appendChild($nome);
$autore->appendChild($cognome);
$fumetto->appendChild($autore);

$sinossi->nodeValue = $description;
$fumetto->appendChild($sinossi);

$lunghezza->nodeValue = $lenght;
$fumetto->appendChild($lunghezza);

$prezzo->nodeValue = $price;
$fumetto->appendChild($prezzo);

$data->nodeValue = $date;
$fumetto->appendChild($data);

$quantita->nodeValue = $quantity;
$fumetto->appendChild($quantita);

$img->nodeValue = $image;
$fumetto->appendChild($img);

$editore->nodeValue = $publisher;
$fumetto->appendChild($editore);

$sconto_generico->nodeValue = "0";
$fumetto->appendChild($sconto_generico);

$X->nodeValue = "0";
$sconto->appendChild($X);
$Y->nodeValue = "10";
$sconto->appendChild($Y);
$M->nodeValue = "0";
$sconto->appendChild($M);
$M_data->nodeValue = "1999-01-01";
$sconto->appendChild($M_data);
$N->nodeValue = "0";
$sconto->appendChild($N);
$R->nodeValue = "0";
$sconto->appendChild($R);
$ha_acquistato->nodeValue = "0";
$sconto->appendChild($ha_acquistato);
$fumetto->appendChild($sconto);

$bonus->nodeValue = "0";
$fumetto->appendChild($bonus);

$manga->appendChild($fumetto);  //Aggiungo il nodo, ora completo di tutti i campi

$documento->formatOutput = true;    //Formatta il documento XML per renderlo più leggibile
$xml = $documento->saveXML();   //saveXML è un metodo che restituisce il documento XML come una stringa di testo (formattata)

file_put_contents($xmlfile, $xml);  //sovrascrive il contenuto del vecchio file XML con quello nuovo

//ora unsetto le variabili di sessione utilizzate per la permanenza
unset($_SESSION['form_titolo_prodotto']);
unset($_SESSION['form_ISBN_prodotto']);
unset($_SESSION['form_nome_autore']);
unset($_SESSION['form_cognome_autore']);
unset($_SESSION['form_sinossi_prodotto']);
unset($_SESSION['form_lunghezza_prodotto']);    
unset($_SESSION['form_prezzo_prodotto']);
unset($_SESSION['form_data_prodotto']);
unset($_SESSION['form_quantita_prodotto']);
unset($_SESSION['form_editrice_prodotto']);

$_SESSION['nuovoprodotto_ok'] = 'true';

header('Location:../../catalogo.php');

?>
