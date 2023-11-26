<?php

session_start();

$IDValutante = $_POST['IDValutante'];
$ID = $_POST['ID'];
$isbn = $_POST['isbn'];
$data = $_POST['data'];
$tipologia = $_POST['tipo'];
$utilita = $_POST['utilita'];
$supporto = $_POST['supporto'];

include('connection.php');
include('funzioni.php');

//siccome se il valutante ha acquistato il prodotto avrà un peso maggiore, posso aggiungerci un +1 sia ad utilità che supporto se risulta vero l'acquisto
$xmlFile = "../XML/storico_acquisti.xml";
$acquisti = getAcquisti($xmlFile);

foreach($acquisti as $acquisto){

    foreach($acquisto['fumetti'] as $fumetto){

        if($fumetto['isbn'] == $isbn){
            $utilita = $utilita + 1;
            $supporto = $supporto + 1;
        }
    }
}


$connessione = new mysqli($host, $user, $password, $db);

$query ="SELECT umn.reputazione FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";

$ris = $connessione->query($query);

if(mysqli_num_rows($ris) == 1){
    $row = $ris->fetch_assoc();
    $reputazione = $row['reputazione'];
}
else{
    header('Location:../../homepage.php');
}

//devo capire se la valutazione è di una recensione o di una domanda/risposta
if($tipologia == "recensione"){

    //bene, ora bisogna aggiornare il file XML 
    $xmlpath ="../XML/catalogo.xml";
    $xmlstring = "";

    foreach(file($xmlpath) as $nodo){   //Leggo il contenuto del file XML

        $xmlstring.= trim($nodo); 
    }

    $document = new DOMDocument();
    $document->loadXML($xmlstring);

    $manga = $document->getElementsByTagName('fumetto');

    foreach($manga as $fumetto){

        $titolo = $fumetto->getElementsByTagName('titolo')->item(0)->nodeValue;

        if($titolo == $_SESSION['info_titolo']){
            $recensioni = $fumetto->getElementsByTagName('recensione');
            
            foreach($recensioni as $recensione){

                $ID_current = $recensione->getElementsByTagName('IDRecensore')->item(0)->nodeValue;
                $date_current = $recensione->getElementsByTagName('dataRecensione')->item(0)->nodeValue;

                if($ID_current == $ID && $date_current == $data){

                    $votazione = $document->createElement('votazione_recensione');

                    $IDV = $document->createElement('IDValutante');
                    $rep = $document->createElement('reputazione');
                    $uti = $document->createElement('utilita');
                    $sup = $document->createElement('supporto');
    
                    $IDV->nodeValue = $IDValutante;
                    $votazione->appendChild($IDV);
    
                    $rep->nodeValue = $reputazione;
                    $votazione->appendChild($rep);
    
                    $uti->nodeValue = $utilita;
                    $votazione->appendChild($uti);
    
                    $sup->nodeValue = $supporto;
                    $votazione->appendChild($sup);
    
                    $recensione->appendChild($votazione);
                }
            }

        }
    }

    $document->formatOutput = true; 
    $xml = $document->saveXML();

    file_put_contents($xmlpath, $xml); 

}
else{

    //bene, ora bisogna aggiornare il file XML 
    $xmlpath ="../XML/Q&A.xml";
    $xmlstring = "";

    foreach(file($xmlpath) as $nodo){   //Leggo il contenuto del file XML

        $xmlstring.= trim($nodo); 
    }

    $document = new DOMDocument();
    $document->loadXML($xmlstring);

    $elenco = $document->getElementsByTagName('domanda');

    foreach($elenco as $domanda){

        if($tipologia == "domanda"){

            $ID_current = $domanda->getElementsByTagName('IDDom')->item(0)->nodeValue;
            $date_current = $domanda->getElementsByTagName('dataDom')->item(0)->nodeValue;

            //condizione per trovare la giusta domanda nella quale mettere la valutazione
            if($ID_current == $ID && $date_current == $data){

                $votazione = $document->createElement('votazione_domanda');

                $IDV = $document->createElement('IDValutante');
                $rep = $document->createElement('reputazione');
                $uti = $document->createElement('utilita');
                $sup = $document->createElement('supporto');

                $IDV->nodeValue = $IDValutante;
                $votazione->appendChild($IDV);

                $rep->nodeValue = $reputazione;
                $votazione->appendChild($rep);

                $uti->nodeValue = $utilita;
                $votazione->appendChild($uti);

                $sup->nodeValue = $supporto;
                $votazione->appendChild($sup);

                $domanda->appendChild($votazione);
            }
        }

        $risposte = $domanda->getElementsByTagName('risposta');

        if($tipologia == "risposta"){

            foreach($risposte as $risposta){
                
                $ID_current = $risposta->getElementsByTagName('IDRisp')->item(0)->nodeValue;
                $date_current = $risposta->getElementsByTagName('dataRisp')->item(0)->nodeValue;

                //condizione per trovare la giusta risposta nella quale mettere la valutazione
                if($ID_current == $ID && $date_current == $data){

                    $votazione = $document->createElement('votazione_risposta');

                    $IDV = $document->createElement('IDValutante');
                    $rep = $document->createElement('reputazione');
                    $uti = $document->createElement('utilita');
                    $sup = $document->createElement('supporto');

                    $IDV->nodeValue = $IDValutante;
                    $votazione->appendChild($IDV);

                    $rep->nodeValue = $reputazione;
                    $votazione->appendChild($rep);

                    $uti->nodeValue = $utilita;
                    $votazione->appendChild($uti);

                    $sup->nodeValue = $supporto;
                    $votazione->appendChild($sup);

                    $risposta->appendChild($votazione);
                }
            }
        }
    }

    $document->formatOutput = true; 
    $xml = $document->saveXML();

    file_put_contents($xmlpath, $xml); 
}

$_SESSION['richiesta_ok'] = true;

if(isset($_SESSION['provenienza_valutazione']) && $_SESSION['provenienza_valutazione'] == "prodotti_info.php"){
    unset($_SESSION['provenienza_valutazione']);
    header('Location: ../../prodotti_info.php');
}
else if(isset($_SESSION['provenienza_valutazione']) && $_SESSION['provenienza_valutazione'] == "mostra_domanda_specifica.php"){
    unset($_SESSION['provenienza_valutazione']);
    header('Location: ../../mostra_domanda_specifica.php');
}
?>