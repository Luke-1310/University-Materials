<?php

session_start();

include("funzioni.php");
include("connection.php");

//mi prendo i dati tramite POST
$ID = $_POST['ID'];
$data = $_POST['data'];

//siccome devo inserire l'ID del segnalatore, ovvero dell'utente corrente devo ricavarmelo
$connessione = new mysqli($host, $user, $password, $db);

$query = "SELECT umn.id FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.username = '{$_SESSION['nome']}'";
$result = $connessione->query($query);

//Verifico se la query ha restituito risultati
if ($result) {

    $row = $result->fetch_assoc();
    $idSegn = $row['id'];
} 

else {
    echo "Errore nella query: " . $connessione->error;
}

//caso recensioni
if(isset($_GET['from']) && $_GET['from'] == "recensione"){

    $xmlpath = "../XML/catalogo.xml";
    $fumetti = getFumetti($xmlpath);

    //devo caricarmi anche il file originale in modo tale da sovrascriverlo successivamente
    $document = new DOMDocument();
    $document->load($xmlpath);

    $fumetti_doc = $document->getElementsByTagName('fumetto');
    
    foreach($fumetti_doc as $fumetto_doc){

        $fumetto_titolo = $fumetto_doc->getElementsByTagName('titolo')->item(0)->nodeValue;
        
        if(isset($_SESSION['info_titolo'])){

            if($fumetto_titolo == $_SESSION['info_titolo']){

                $recensioni = $fumetto_doc->getElementsByTagName('recensione');
                
                foreach($recensioni as $recensione){
                
                    $ID_recensore = $recensione->getElementsByTagName('IDRecensore')->item(0)->nodeValue;
                    $data_recensore = $recensione->getElementsByTagName('dataRecensione')->item(0)->nodeValue;

                    if($ID_recensore == $ID && $data_recensore == $data){
                        
                        $recensione->getElementsByTagName('segnalazione')->item(0)->nodeValue = 1;
                        $recensione->getElementsByTagName('IDSegnalatore')->item(0)->nodeValue = $idSegn;
                        break;
                    }
                }
            }
        }
        else{
            $_SESSION['richiesta_ko'] = true;
            header('Location: ../../homepage.php');
        }
    }
    
    $document->save($xmlpath);

    $_SESSION['segnalazione_ok'] = true;
    header('Location:../../prodotti_info.php');
    exit(1);
}

$xmlpath = "../XML/Q&A.xml";
$domande = getDomande($xmlpath);

//devo caricarmi anche il file originale in modo tale da sovrascriverlo successivamente
$document = new DOMDocument();
$document->load($xmlpath);

$domande_doc = $document->getElementsByTagName('domanda');

//controllo da dove è partito il collegamento tramite GET
//ci troviamo nel caso di segnalazione di una domanda
if(isset($_GET['from']) && $_GET['from'] == "domanda"){
    
    foreach($domande_doc as $domanda_doc){

        $domanda_IDDom = $domanda_doc->getElementsByTagName('IDDom')->item(0)->nodeValue;
        $domanda_dataDom = $domanda_doc->getElementsByTagName('dataDom')->item(0)->nodeValue;
        
        foreach($domande as $domanda){
    
            if($domanda_IDDom == $ID && $domanda_dataDom == $data){

                $domanda_doc->getElementsByTagName('segnalazione')->item(0)->nodeValue = 1;
                $domanda_doc->getElementsByTagName('IDSegnalatore')->item(0)->nodeValue = $idSegn;
                break;
            }
        }
    }
    
    $document->save($xmlpath);
    
    $_SESSION['segnalazione_ok'] = true;
    header('Location:../../mostra_domande_prodotto.php');
    exit(1);
}

//ci troviamo nel caso di segnalazione di una risposta
else if(isset($_GET['from']) && $_GET['from'] == "risposta"){

    foreach($domande_doc as $domanda_doc){

        $risposteNodes = $domanda_doc->getElementsByTagName('risposta');
                
        foreach ($risposteNodes as $rispostaNode) {
    
            $IDRisp = $rispostaNode->getElementsByTagName('IDRisp')->item(0)->nodeValue;
            $dataRisp = $rispostaNode->getElementsByTagName('dataRisp')->item(0)->nodeValue;
    
            if($IDRisp == $ID && $dataRisp == $data){

                $rispostaNode->getElementsByTagName('segnalazione')->item(0)->nodeValue = 1;
                $rispostaNode->getElementsByTagName('IDSegnalatore')->item(0)->nodeValue = $idSegn;
                break;
            }
        }
    }

    $document->save($xmlpath);
    
    $_SESSION['segnalazione_ok'] = true;
    header('Location:../../mostra_domande_prodotto.php');
    exit(1);
}
?>