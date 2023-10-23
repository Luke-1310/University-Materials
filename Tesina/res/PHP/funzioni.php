<?php

    //funzione per caricare i fumetti dal catalogo
    function getFumetti($xmlFile) {

        //array dove poi metterò i fumetti presi dal file catalogo.xml
        $fumetti = [];

        // Crea un nuovo oggetto DOMDocument e carica il file XML
        $dom = new DOMDocument();
        $dom->load($xmlFile);

        $manga = $dom->getElementsByTagName('fumetto');

        foreach ($manga as $fumetto) {

            $ISBN = $fumetto->getAttribute('isbn');
            $titolo = $fumetto->getElementsByTagName('titolo')->item(0)->nodeValue;

            $sinossi = $fumetto->getElementsByTagName('sinossi')->item(0)->nodeValue;
            $lunghezza = $fumetto->getElementsByTagName('lunghezza')->item(0)->nodeValue;
            $prezzo = $fumetto->getElementsByTagName('prezzo')->item(0)->nodeValue;
            $dataUscita = $fumetto->getElementsByTagName('data')->item(0)->nodeValue;
            $quantita = $fumetto->getElementsByTagName('quantita')->item(0)->nodeValue;
            $casaEditrice = $fumetto->getElementsByTagName('editore')->item(0)->nodeValue;

            
            $bonus = $fumetto->getElementsByTagName('bonus')->item(0)->nodeValue;
            
            //mi prendo i parametri dello sconto
            $sconto = $fumetto->getElementsByTagName('sconto')->item(0);

            $sconto_mesi = $sconto->getElementsByTagName('X')->item(0)->nodeValue;
            $sconto_crediti = $sconto->getElementsByTagName('N')->item(0)->nodeValue;
            $sconto_reputazione = $sconto->getElementsByTagName('R')->item(0)->nodeValue;

            //mi prendo il nome e cognome dell'autore
            $autore = $fumetto->getElementsByTagName('autore')->item(0);

            $nome_autore = $autore->getElementsByTagName('nome')->item(0)->nodeValue;
            $cognome_autore = $autore->getElementsByTagName('cognome')->item(0)->nodeValue;

            //mi prendo tutti i campi tranne le recensioni (al momento)
            $fumetti[] = [
                'isbn' => $ISBN,
                'titolo' => $titolo,
                'sinossi' => $sinossi,
                'lunghezza' => $lunghezza,
                'prezzo' => $prezzo,
                'data' => $dataUscita,
                'quantita' => $quantita,
                'editore' => $casaEditrice,
                'bonus' => $bonus,
                
                'img' => $ISBN,

                'X' => $sconto_mesi,
                'N' => $sconto_crediti,
                'R' => $sconto_reputazione,

                'nome_autore' => $nome_autore,
                'cognome_autore' => $cognome_autore,
            ];
        }

        return $fumetti;
    }

    //funzione per caricare le richieste crediti
    function getRichiesteCr($xmlFile){

        $richieste = [];

        $dom = new DOMDocument();
        $dom->load($xmlFile);

        $storico = $dom->getElementsByTagName('richiesta');

        foreach($storico as $richiesta){
            $IDUtente = $richiesta->getElementsByTagName('IDUtente')->item(0)->nodeValue;
            $quantita = $richiesta->getElementsByTagName('quantita')->item(0)->nodeValue;
            $dataRichiesta = $richiesta->getElementsByTagName('dataRichiesta')->item(0)->nodeValue;
            $risposta = $richiesta->getElementsByTagName('risposta')->item(0)->nodeValue;

            $richieste[] = [
                'IDUtente' => $IDUtente,
                'quantita' => $quantita,
                'dataRichiesta' => $dataRichiesta,
                'risposta' => $risposta,
            ];
        }

        return $richieste;
    }

    //funzione per caricare le domande e risposte su un certo prodotto da fixare
    function getDomande($xmlFile) {
        $domande = [];
    
        $dom = new DOMDocument();
        $dom->load($xmlFile);
    
        $elenco = $dom->getElementsByTagName('domanda');
    
        foreach ($elenco as $domanda) {
            $ISBNProdotto = $domanda->getElementsByTagName('ISBNProdotto')->item(0)->nodeValue;
            $FAQ = $domanda->getElementsByTagName('FAQ')->item(0)->nodeValue;
            $IDDom = $domanda->getElementsByTagName('IDDom')->item(0)->nodeValue;
            $testoDom = $domanda->getElementsByTagName('testoDom')->item(0)->nodeValue;
            $dataDom = $domanda->getElementsByTagName('dataDom')->item(0)->nodeValue;
    
            $risposte = [];
    
            $risposteNodes = $domanda->getElementsByTagName('risposta');
            
            foreach ($risposteNodes as $rispostaNode) {
                $IDRisp = $rispostaNode->getElementsByTagName('IDRisp')->item(0)->nodeValue;
                $dataRisp = $rispostaNode->getElementsByTagName('dataRisp')->item(0)->nodeValue;
                $testoRisp = $rispostaNode->getElementsByTagName('testoRisp')->item(0)->nodeValue;

                $votazioni = [];

                $votazioniNodes = $rispostaNode->getElementsByTagName('votazione');

                foreach($votazioniNodes as $votazioneNode){
                    $IDValutante = $votazioneNode->getElementsByTagName('IDValutante')->item(0)->nodeValue;
                    $reputazione = $votazioneNode->getElementsByTagName('reputazione')->item(0)->nodeValue;
                    $utilita = $votazioneNode->getElementsByTagName('utilita')->item(0)->nodeValue;
                    $supporto = $votazioneNode->getElementsByTagName('supporto')->item(0)->nodeValue;

                    $votazioni[] = [
                        'IDValutante' =>$IDValutante,
                        'reputazione' =>$reputazione,
                        'utilita' =>$utilita,
                        'supporto' =>$supporto,
                    ];
                }

                $risposte[] = [
                    'IDRisp' => $IDRisp,
                    'dataRisp' => $dataRisp,
                    'testoRisp' => $testoRisp,
                    'votazioni' => $votazioni,
                ];
            }
    
            $domande[] = [
                'ISBNProdotto' => $ISBNProdotto,
                'FAQ' => $FAQ,
                'IDDom' => $IDDom,
                'testoDom' => $testoDom,
                'dataDom' => $dataDom,
                'risposte' => $risposte,
            ];
        }
    
        return $domande;
    }
    
    //funzione per ricavare la reputazione dell'utente loggato
    function getReputazioneCurr(){

        include('connection.php');
        $connessione = new mysqli($host, $user, $password, $db);

        if (isset($_SESSION['nome'])) {
            $query = "SELECT um.reputazione FROM  utenteMangaNett um  WHERE um.username = '{$_SESSION['nome']}'";
            $ris = mysqli_query($connessione, $query);
    
            if (mysqli_num_rows($ris) == 1) {
                $row = $ris->fetch_assoc();
                $reputazione_utente = $row['reputazione'];
            }

            return $reputazione_utente;
        }
    }

    function getDataRegistrazioneCurr(){

        include('connection.php');
        $connessione = new mysqli($host, $user, $password, $db);

        if (isset($_SESSION['nome'])) {

            $query = "SELECT um.data_registrazione FROM  utenteMangaNett um  WHERE um.username = '{$_SESSION['nome']}'";
            $ris = mysqli_query($connessione, $query);
    
            if (mysqli_num_rows($ris) == 1) {
                $row = $ris->fetch_assoc();
                $dataregistrazione_utente = $row['data_registrazione'];
    
                //ora mi devo fare la differenza
                $data_corrente = date("Y-m-d");
    
                $differenza_data = date_diff(date_create($dataregistrazione_utente), date_create($data_corrente));
    
                //$differenza_data->y restituisce il numero di anni nella differenza tra le date.
                //$differenza_data->m restituisce il numero di mesi nella differenza tra le date.
                $mesi_trascorsi = $differenza_data->y * 12 + $differenza_data->m;
            }    

            return $mesi_trascorsi;
        }
    }

    function getCreditiSpesiCurr(){

        if (isset($_SESSION['nome'])) {

            //mi devo controllare gli ordini e ricavarmi i crediti spesi, al momento lascio un valore standard di 100
            $spesi_utente = 100;
            
            return $spesi_utente;
        }
    }

    

?>