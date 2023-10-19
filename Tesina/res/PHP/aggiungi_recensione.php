<?php
    session_start();

    include("connection.php");

    $connessione = new mysqli($host, $user, $password, $db);

    $recensione_testo = $_POST['recensione'];

    //presa la recensione tramite $_POST procediamo con l'inserimento(una volta che avremmo recuperato tutti i dati)
    //i dati da recuperare sono l'ID dell'utente registrato,
    $xmlfile = "../XML/catalogo.xml";  //Percorso del file XML
    
    //tramite funzioni.php mi carico i fumetti, cerco quello corretto e inserisco la recensione
    include('funzioni.php');

    $fumetti = getFumetti($xmlfile);

    //mi prendo innanzitutto l'ID dell'utente loggato
    $query = "SELECT umn.id FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.username = '{$_SESSION["nome"]}'";

    $result = $connessione->query($query);

    //Verifico se la query ha restituito risultati
    if ($result) {

        //Estraggo il risultato come un array associativo
        $row = $result->fetch_assoc();
        $id = $row['id'];
    } 
    
    else {
        echo "Errore nella query: " . $connessione->error;
    }
        
    //mi prendo la reputazione dell'utente
    $query_repu = "SELECT umn.reputazione FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.username = '{$_SESSION["nome"]}'";

    $result_repu = $connessione->query($query);

    //Verifico se la query ha restituito risultati
    if ($result_repu) {

        //Estraggo il risultato come un array associativo
        $row = $result_repu->fetch_assoc();
        $repu = $row['reputazione'];
    } 
    
    else {
        echo "Errore nella query: " . $connessione->error;
    }

    //mi prendo la data e l'ora, ora ho tutto, ID, recensione, data e ora e reputazione
    $dataRec = date('Y-m-d\TH:i:s');

    $recensione = [
        'utenteID' => $id,
        'rec' => $recensione_testo,
        'dataRec' => $dataRec,
        'reputazionevotante' => $repu,
    ];
        
    //bene, ora che l'array che contiene i fumetti è stato aggiornato bisogna aggiornare il file XML 
    $document = new DOMDocument();
    $document->load($xmlfile);

    //Mi prendo tutti gli elementi 'fumetto' nel documento
    $fumetti_doc = $document->getElementsByTagName('fumetto');

    foreach ($fumetti_doc as $fumetto_doc) {
        //Ottieni l'attributo 'titolo' dell'elemento 'fumetto'
        $fumetto_titolo = $fumetto_doc->getElementsByTagName('titolo')->item(0)->nodeValue;

        //Ora devo inserire la recensione nel fumetto corretto
        if ($_SESSION['info_titolo'] === $fumetto_titolo) {

            // Crea un nuovo elemento recensione
            $recensioneElement = $document->createElement('recensione');
            
            // Aggiungi l'ID dell'utente
            $utenteIDElement = $document->createElement('utenteID', $id);
            $recensioneElement->appendChild($utenteIDElement);

            // Aggiungi il testo della recensione
            $recElement = $document->createElement('rec', $recensione_testo);
            $recensioneElement->appendChild($recElement);

            // Aggiungi la data della recensione
            $dataRecElement = $document->createElement('dataRec', $dataRec);
            $recensioneElement->appendChild($dataRecElement);

            // Aggiungi la reputazione del votante
            $reputazioneVotanteElement = $document->createElement('reputazionevotante', $repu);
            $recensioneElement->appendChild($reputazioneVotanteElement);

            // Aggiungi l'elemento recensione al fumetto
            $fumetto_doc->appendChild($recensioneElement);

            break; 
        }
    }

    // Salva il documento XML aggiornato nel file
    $document->save($xmlfile);


header('Location:../../prodotti_info.php');
?>