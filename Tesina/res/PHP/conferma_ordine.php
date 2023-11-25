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
$document = new DOMDocument();
$document->load($pathXml);

$fumetti_doc = $document->getElementsByTagName('fumetto');

foreach($_SESSION['carrello'] as $fumetto_carrello){

    foreach ($fumetti_doc as $fumetto_doc) {

        $fumetto_isbn = $fumetto_doc->getAttribute('isbn')->item(0)->nodeValue;
        
        if($fumetto_carrello['isbn'] == $fumetto_isbn){

            $fumetto_quantita = $fumetto_doc->getElementsByTagName('quantita')->item(0)->nodeValue;

            if($fumetto_carrello['quantita'] >= $fumetto_quantita){

                $fumetto_quantita -= $fumetto_carrello['quantita'];
                $fumetto_doc->getElementsByTagName('quantita')->item(0)->nodeValue = $fumetto_quantita;
            }
            else{

                $_SESSION['quantita_insufficienti'] = $fumetto_quantita;
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

//ora devo aggiornare lo storico acquisti

//DA FINIRE


$_SESSION['ordine completato'] = true;

header('Location: ../../catalogo.php');
?>