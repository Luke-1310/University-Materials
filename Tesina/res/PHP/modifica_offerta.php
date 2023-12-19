<?php

session_start();

$titolo = $_POST['titolo'];

$sc_X = $_POST['registrazione_mesi'];
$sc_Y = $_POST['registrazione_anni'];
$sc_M = $_POST['crediti_data'];
$sc_data_M = $_POST['da_data'];
$sc_N = $_POST['crediti'];
$sc_R = $_POST['reputazione'];
$sc_ha_acquistato = $_POST['ha_acquistato'];

$sc_bonus = $_POST['bonus'];
$sc_generico = $_POST['generico'];

$xmlPath = "../XML/catalogo.xml";

$document = new DOMDocument();
$document->load($xmlPath);    //Carico il contenuto del file XML dentro $documento per poterlo manipolare tramite DOM

$fumetti = $document->getElementsByTagName("fumetto");

foreach($fumetti as $fumetto){

    $titolo_documento = $fumetto->getElementsByTagName("titolo")->item(0)->nodeValue;

    if($titolo_documento == $titolo){

        //ci troviamo nel fumetto da modificare
        $sconto = $fumetto->getElementsByTagName('sconto')->item(0);
        $sconto->getElementsByTagName('X')->item(0)->nodeValue = $sc_X;
        $sconto->getElementsByTagName('Y')->item(0)->nodeValue = $sc_Y;
        $sconto->getElementsByTagName('M')->item(0)->nodeValue = $sc_M;
        $sconto->getElementsByTagName('data_M')->item(0)->nodeValue = $sc_data_M;
        $sconto->getElementsByTagName('N')->item(0)->nodeValue = $sc_N;
        $sconto->getElementsByTagName('R')->item(0)->nodeValue = $sc_R;
        $sconto->getElementsByTagName('ha_acquistato')->item(0)->nodeValue = $sc_ha_acquistato;    

        $fumetto->getElementsByTagName('bonus')->item(0)->nodeValue = $sc_bonus;
        $fumetto->getElementsByTagName('sconto_generico')->item(0)->nodeValue = $sc_generico;

        break; 
    }
}

// Salva il documento XML aggiornato nel file
$document->save($xmlPath);

$_SESSION['richiesta_ok'] = true;

//aggiorno tutte le variabili di sessione utilizzati prima visto che il form è andato a buon fine

$_SESSION['form_off_X'] = $sc_X;
$_SESSION['form_off_Y'] = $sc_Y;
$_SESSION['form_off_M'] = $sc_M;
$_SESSION['form_off_data_M'] = $sc_data_M;
$_SESSION['form_off_N'] = $sc_N;
$_SESSION['form_off_R'] = $sc_R;
$_SESSION['form_off_ha_acquistato'] = $sc_ha_acquistato;

$_SESSION['form_off_generico'] = $sc_generico;
$_SESSION['form_off_bonus'] = $sc_bonus;

header('Location:../../modifica_offerta.php');




?>