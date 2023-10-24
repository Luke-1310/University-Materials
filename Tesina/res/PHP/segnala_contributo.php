<?php

session_start();

include("funzioni.php");

//mi prendo i dati tramite POST
$ID = $_POST['ID'];
$data = $_POST['data'];

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

                $domanda_doc->getElementsByTagName('segnalazione')->item(0)->nodeValue = 0;
                break;
            }
        }
    }
    
    $document->save($xmlpath);
    
    //bene ora devo capire dove reinderizzare l'utente in base alla pagina di partenza
    if(isset($_GET['departed_from']) && $_GET['departed_from'] == "prodotti_info"){

        $_SESSION['segnalazione_ok'] = true;
        header('Location:../../prodotti_info.php');
        exit(1);
    }
    else if(isset($_GET['departed_from']) && $_GET['departed_from'] == "domanda_specifica"){

        $_SESSION['segnalazione_ok'] = true;
        header('Location:../../mostra_domanda_specifica.php');
        exit(1);
    }
}

//ci troviamo nel caso di segnalazione di una risposta
else if(isset($_GET['from']) && $_GET['from'] == "risposta"){

    foreach($domande_doc as $domanda_doc){

        $risposteNodes = $domanda_doc->getElementsByTagName('risposta');
                
        foreach ($risposteNodes as $rispostaNode) {
    
            $IDRisp = $rispostaNode->getElementsByTagName('IDRisp')->item(0)->nodeValue;
            $dataRisp = $rispostaNode->getElementsByTagName('dataRisp')->item(0)->nodeValue;
    
            if($IDRisp == $ID && $data_POST == $data){

                $rispostaNode->getElementsByTagName('segnalazione')->item(0)->nodeValue = 0;
                break;
            }
        }
    }

    $document->save($xmlpath);
    
    //bene ora devo capire dove reinderizzare l'utente in base alla pagina di partenza
    if(isset($_GET['departed_from']) && $_GET['departed_from'] == "prodotti_info"){

        $_SESSION['segnalazione_ok'] = true;
        header('Location:../../prodotti_info.php');
        exit(1);
    }

    else if(isset($_GET['departed_from']) && $_GET['departed_from'] == "domanda_specifica"){

        $_SESSION['segnalazione_ok'] = true;
        header('Location:../../mostra_domanda_specifica.php');
        exit(1);
    }
}

?>