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

            //mi prendo le recensioni
            $recensioni = [];
            $recensioneNodes = $fumetto->getElementsByTagName('recensione');

            foreach($recensioneNodes as $recensioneNode){

                $IDRecensore = $recensioneNode->getElementsByTagName('IDRecensore')->item(0)->nodeValue;
                $dataRecensione = $recensioneNode->getElementsByTagName('dataRecensione')->item(0)->nodeValue;
                $testoRecensione = $recensioneNode->getElementsByTagName('testoRecensione')->item(0)->nodeValue;
                $reputazioneRecensore = $recensioneNode->getElementsByTagName('reputazioneRecensore')->item(0)->nodeValue;
                $segnalazione_rec = $recensioneNode->getElementsByTagName('segnalazione')->item(0)->nodeValue;
                $IDSegnalatore_rec = $recensioneNode->getElementsByTagName('IDSegnalatore')->item(0)->nodeValue;

                $votazioni_recensione = [];

                $votazioniNodes = $recensioneNode->getElementsByTagName('votazione_recensore');

                foreach($votazioniNodes as $votazioneNode){
                    
                    $IDValutante = $votazioneNode->getElementsByTagName('IDValutante')->item(0)->nodeValue;
                    $reputazione = $votazioneNode->getElementsByTagName('reputazione')->item(0)->nodeValue;
                    $utilita = $votazioneNode->getElementsByTagName('utilita')->item(0)->nodeValue;
                    $supporto = $votazioneNode->getElementsByTagName('supporto')->item(0)->nodeValue;

                    $votazioni_recensione[] = [
                        'IDValutante' =>$IDValutante,
                        'reputazione' =>$reputazione,
                        'utilita' =>$utilita,
                        'supporto' =>$supporto,
                    ];
                }

                $recensioni[] =[
                    'IDRecensore' => $IDRecensore,
                    'dataRecensione' => $dataRecensione,
                    'testoRecensione' => $testoRecensione,
                    'reputazioneRecensore' => $reputazioneRecensore,
                    'segnalazione' => $segnalazione_rec,
                    'IDSegnalatore' => $IDSegnalatore_rec,
                    'votazioni' => $votazioni_recensione,
                ];
            }

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

                'recensione' => $recensioni,
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
            $segnalazione = $domanda->getElementsByTagName('segnalazione')->item(0)->nodeValue;
            $IDSegnalatore_dom = $domanda->getElementsByTagName('IDSegnalatore')->item(0)->nodeValue;
            $IDDom = $domanda->getElementsByTagName('IDDom')->item(0)->nodeValue;
            $testoDom = $domanda->getElementsByTagName('testoDom')->item(0)->nodeValue;
            $dataDom = $domanda->getElementsByTagName('dataDom')->item(0)->nodeValue;
    
            $votazioni_domanda = [];

            $votazioniNodes = $domanda->getElementsByTagName('votazione_domanda');

            foreach($votazioniNodes as $votazioneNode){
                $IDValutante = $votazioneNode->getElementsByTagName('IDValutante')->item(0)->nodeValue;
                $reputazione = $votazioneNode->getElementsByTagName('reputazione')->item(0)->nodeValue;
                $utilita = $votazioneNode->getElementsByTagName('utilita')->item(0)->nodeValue;
                $supporto = $votazioneNode->getElementsByTagName('supporto')->item(0)->nodeValue;

                $votazioni_domanda[] = [
                    'IDValutante' =>$IDValutante,
                    'reputazione' =>$reputazione,
                    'utilita' =>$utilita,
                    'supporto' =>$supporto,
                ];
            }

            $risposte = [];
    
            $risposteNodes = $domanda->getElementsByTagName('risposta');
            
            foreach ($risposteNodes as $rispostaNode) {

                $IDRisp = $rispostaNode->getElementsByTagName('IDRisp')->item(0)->nodeValue;
                $FAQ_risposta = $rispostaNode->getElementsByTagName('FAQ')->item(0)->nodeValue;
                $segnalazione_risposta = $rispostaNode->getElementsByTagName('segnalazione')->item(0)->nodeValue;
                $IDSegnalatore_ris = $rispostaNode->getElementsByTagName('IDSegnalatore')->item(0)->nodeValue;
                $dataRisp = $rispostaNode->getElementsByTagName('dataRisp')->item(0)->nodeValue;
                $testoRisp = $rispostaNode->getElementsByTagName('testoRisp')->item(0)->nodeValue;

                $votazioni_risposta = [];

                $votazioniNodes = $rispostaNode->getElementsByTagName('votazione_risposta');

                foreach($votazioniNodes as $votazioneNode){
                    $IDValutante = $votazioneNode->getElementsByTagName('IDValutante')->item(0)->nodeValue;
                    $reputazione = $votazioneNode->getElementsByTagName('reputazione')->item(0)->nodeValue;
                    $utilita = $votazioneNode->getElementsByTagName('utilita')->item(0)->nodeValue;
                    $supporto = $votazioneNode->getElementsByTagName('supporto')->item(0)->nodeValue;

                    $votazioni_risposta[] = [
                        'IDValutante' =>$IDValutante,
                        'reputazione' =>$reputazione,
                        'utilita' =>$utilita,
                        'supporto' =>$supporto,
                    ];
                }

                $risposte[] = [
                    'IDRisp' => $IDRisp,
                    'FAQ' => $FAQ_risposta,
                    'segnalazione' => $segnalazione_risposta,
                    'IDSegnalatore' => $IDSegnalatore_ris,
                    'dataRisp' => $dataRisp,
                    'testoRisp' => $testoRisp,
                    'votazioni' => $votazioni_risposta,
                ];
            }
    
            $domande[] = [
                'ISBNProdotto' => $ISBNProdotto,
                'FAQ' => $FAQ,
                'segnalazione' => $segnalazione,
                'IDSegnalatore' => $IDSegnalatore_dom,
                'IDDom' => $IDDom,
                'testoDom' => $testoDom,
                'dataDom' => $dataDom,
                'risposte' => $risposte,
                'votazioni' => $votazioni_domanda,
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

    //funzione per ricavare la data di registrazione dell'utente loggato
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

    //funzione per ricavare la somma spesa dell'utente loggato
    function getCreditiSpesiCurr(){

        if (isset($_SESSION['nome'])) {

            //mi devo controllare gli ordini e ricavarmi i crediti spesi, al momento lascio un valore standard di 100
            $spesi_utente = 100;
            
            return $spesi_utente;
        }
    }

    //funzione per mostrare domande e risposte di un certo prodotto
    function mostraDomande($ISBN, $xmlPath){

        include('connection.php');

        $connessione = new mysqli($host, $user, $password, $db);

        $domande = getDomande($xmlPath);

        $isDomanda = false;

        foreach($domande as $domanda){

            if($domanda['ISBNProdotto'] == $ISBN){
                
                $isDomanda = true;

                echo "<div class=\"container_sp\">";

                    //mi devo prendere il nome utente corrispettivo del domandante
                    $query = "SELECT umn.username FROM utenteMangaNett umn WHERE umn.id = {$domanda['IDDom']}";

                    $ris = $connessione->query($query);

                    //Verifico se la query ha restituito risultati
                    if ($ris) {

                        //Estraggo il risultato come un array associativo
                        $row = $ris->fetch_assoc();
                        $username = $row['username']; 
                    }
                    else{
                        exit(1);
                    }

                    $parti = explode("T", $domanda['dataDom']);

                    //$parti[0] conterrà la data (parte prima di T) e $parti[1] conterrà l'ora (parte dopo di T)
                    $data = $parti[0];
                    $ora = $parti[1];

                    echo "<div class=\"domanda\">";

                        echo"<div class=\"info-domanda\">";
                            echo"<p class=\"utente\">$username</p>";
                            echo"<p class=\"data\">" . $data . " ". $ora ."</p>";
                            echo "<p class=\"data\">DOMANDA</p>";
                        echo"</div>";

                        echo"<p class=\"testo-domanda\">" . $domanda['testoDom'] . "</p>";

                        if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){
                            
                            //se l'utente corrente è bannato allora devo impedirgli di commentare
                            $query = "SELECT umn.ban FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";

                            $ris = $connessione->query($query);

                            //Verifico se la query ha restituito risultati
                            if ($ris) {

                                //Estraggo il risultato come un array associativo
                                $row = $ris->fetch_assoc();
                                $ban = $row['ban'];
                            }
                            else{
                                exit(1);
                            }

                            if($ban == 0){

                                //mi devo prendere il nome utente corrispettivo dell'utente corrente se loggato
                                $query_v = "SELECT umn.id FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";

                                $ris_v = $connessione->query($query_v);

                                if ($ris_v) {

                                    //Estraggo il risultato come un array associativo
                                    $row_v = $ris_v->fetch_assoc();
                                    $id_valutante = $row_v['id']; 
                                }
                                else{
                                    exit(1);
                                }

                                $ha_votato = false;

                                if (isset($domanda['votazioni'])) {

                                    foreach ($domanda['votazioni'] as $votazione) {

                                        if ($votazione['IDValutante'] == $id_valutante) {
                                            $ha_votato = true;
                                            break;
                                        }
                                    }
                                }

                                if($ha_votato){
                                    echo "<p id=\"ha_votato\">HAI GIÀ VOTATO QUESTO CONTRIBUTO... ¯\_(ツ)_/¯</p>";
                                }
                                else{

                                    echo "<form id=\"valutazioneForm\" action=\"res/PHP/aggiungi_valutazione_specifica.php\" method=\"POST\">";

                                        echo "<div class=\"form-row\">";

                                            echo "<label for=\"utilita\">UTILITÀ:</label>";
                                            echo "<select name=\"utilita\" id=\"utilita\">";
                                        
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo "<option value=\"$i\">$i</option>";
                                                }

                                            echo "</select>";

                                        echo "</div>";

                                        echo "<div class=\"form-row\">";

                                            echo "<label for=\"supporto\">SUPPORTO:</label>";
                                            echo "<select name=\"supporto\" id=\"supporto\">";

                                                for ($i = 1; $i <= 3; $i++) {
                                                    echo "<option value=\"$i\">$i</option>";
                                                }
                                                
                                            echo "</select>";
                                            
                                            echo"<input type=\"hidden\" name=\"IDValutante\" value=".  $id_valutante .">";
                                            echo"<input type=\"hidden\" name=\"ID\" value=". $domanda['IDDom'] .">";
                                            echo"<input type=\"hidden\" name=\"data\" value=". $domanda['dataDom'] .">";
                                            echo"<input type=\"hidden\" name=\"tipo\" value=\"domanda\">";

                                        echo "</div>";
                                
                                        echo "<span class=\"bottone\"><input type=\"submit\" value=\"INVIA\"></span>";

                                    echo "</form>";

                                }

                                //form AGGIUNGI RISPOSTA
                                echo"<form id=\"rispostaForm\" action=\"res/PHP/mostra_domanda_specifica.php\" method=\"POST\" >";

                                    echo"<div class=\"form-row\">";
                                        echo"<label for=\"risposta\">AGGIUNGI UNA RISPOSTA...</label>";
                                        echo "<textarea id=\"risposta\" name=\"risposta\" rows=\"10\" cols=\"40\" placeholder=\"Inserisci qui la tua risposta....\" required></textarea>";
                                    echo"</div>";

                                    echo"<input type=\"hidden\" name=\"data\" value=". $domanda['dataDom'] . ">";
                                    echo"<input type=\"hidden\" name=\"isbn\" value=$ISBN>";
                                    echo "<span class =\"bottone\"><input type=\"submit\" value=\"INVIA\"></span>";

                                echo "</form>";

                                //faccio questo controllo perché solo l'admin può elevare a FAQ una domanda
                                $sql_am = "SELECT u.ruolo FROM utentemanganett u WHERE u.username = '{$_SESSION['nome']}' AND (u.ruolo = 'AM' OR u.ruolo = 'SA')";
                                $ris_am = mysqli_query($connessione, $sql_am);

                                if(mysqli_num_rows($ris_am) == 1){

                                    echo"<form id=\"rispostaForm\" action = \"res/PHP/eleva_a_FAQ.php\" method=\"POST\" >";

                                        //mi invio la  data della domanda
                                        echo"<input type=\"hidden\" name=\"dataDom\" value=". $domanda['dataDom'] . ">";

                                        //mi invio l'id della domanda
                                        echo"<input type=\"hidden\" name=\"IDDom\" value=". $domanda['IDDom'] . ">";
                                        echo "<span class =\"bottone\"><input type=\"submit\" value=\"ELEVA A FAQ\"></span>";
                                    
                                    echo "</form>";
                                }

                                echo"<form id=\"bottoniForm\" action = \"res/PHP/segnala_contributo.php?from=domanda\" method=\"POST\" >";

                                    echo"<input type=\"hidden\" name=\"data\" value=". $domanda['dataDom'] . ">";
                                    echo"<input type=\"hidden\" name=\"ID\" value=". $domanda['IDDom'] . ">";
                                    echo "<span class =\"bottone\"><input type=\"submit\" value=\"SEGNALA\"></span>";
                                
                                echo "</form>";
                            }

                            else{
                                echo"<p id=\"new_question\">OPS... RISULTI BANNATO!</p>";
                            }
                        
                        }
                        else{
                            echo"<p id=\"new_question\"><a href=\"login.php\">LOGGATI PER INSERIRE UNA NUOVA RISPOSTA!</a></p>";
                        }
                        
                    echo"</div>";

                    //ora devo stampare le risposte se ci sono
                    foreach($domanda['risposte'] as $risposta){
                        
                        //mi devo prendere il nome utente corrispettivo del domandante
                        $query_r = "SELECT umn.username FROM utenteMangaNett umn WHERE umn.id = {$risposta['IDRisp']}";

                        $ris_r = $connessione->query($query_r);

                        //Verifico se la query ha restituito risultati
                        if ($ris_r) {

                            //Estraggo il risultato come un array associativo
                            $row_r = $ris_r->fetch_assoc();
                            $username_r = $row_r['username']; 
                        }
                        else{
                            exit(1);
                        }

                        $parti_r = explode("T", $risposta['dataRisp']);

                        $data_r = $parti_r[0];
                        $ora_r = $parti_r[1];

                        echo "<div class=\"risposta\">";

                            echo"<div class=\"info-risposta\">";

                                echo "<p class=\"utente\">$username_r</p>";
                                echo "<p class=\"data\">" . $data_r . " ". $ora_r . "</p>";
                                echo "<p class=\"data\">RISPOSTA</p>";
                            
                            echo "</div>";
                            
                            echo "<p class=\"testo-risposta\">" . $risposta['testoRisp'] . "</p>";
                            
                            //se l'utente ha già votato allora devo impedirgli di votare ancora
                            if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){

                                //se l'utente è bannato devo impedirgli di votare e segnalare
                                if($ban == 0){
                                
                                    $ha_votato = false;

                                    if (isset($risposta['votazioni'])) {

                                        foreach ($risposta['votazioni'] as $votazione) {

                                            if ($votazione['IDValutante'] == $id_valutante) {
                                                $ha_votato = true;
                                                break;
                                            }
                                        }
                                    }

                                    if($ha_votato){
                                        echo "<p id=\"ha_votato\">HAI GIÀ VOTATO QUESTO CONTRIBUTO... ¯\_(ツ)_/¯</p>";
                                    }
                                    else{

                                        //form valutazione UTILITÀ e SUPPORTO
                                        echo "<form id=\"valutazioneForm\" action=\"res/PHP/aggiungi_valutazione_specifica.php\" method=\"POST\">";

                                            echo "<div class=\"form-row\">";
                                                echo "<label for=\"utilita\">UTILITÀ:</label>";
                                                echo "<select name=\"utilita\" id=\"utilita\">";
                                            
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        echo "<option value=\"$i\">$i</option>";
                                                    }

                                                echo "</select>";
                                            echo "</div>";

                                            echo "<div class=\"form-row\">";

                                                echo "<label for=\"supporto\">SUPPORTO:</label>";
                                                echo "<select name=\"supporto\" id=\"supporto\">";

                                                    for ($i = 1; $i <= 3; $i++) {
                                                        echo "<option value=\"$i\">$i</option>";
                                                    }
                                                    
                                                echo "</select>";
                                                
                                                //mi invio l'id del valutante
                                                echo"<input type=\"hidden\" name=\"IDValutante\" value=".  $id_valutante .">";

                                                echo"<input type=\"hidden\" name=\"ID\" value=". $risposta['IDRisp'] .">";
                                                echo"<input type=\"hidden\" name=\"data\" value=". $risposta['dataRisp'] .">";
                                                echo"<input type=\"hidden\" name=\"tipo\" value=\"risposta\">";

                                            echo "</div>";
                                            
                                            echo "<span class=\"bottone\"><input type=\"submit\" value=\"INVIA\"></span>";

                                        echo "</form>";
                                    }

                                    $sql_am = "SELECT u.ruolo FROM utentemanganett u WHERE u.username = '{$_SESSION['nome']}' AND (u.ruolo = 'AM' OR u.ruolo = 'SA')";
                                    $ris_am = mysqli_query($connessione, $sql_am);

                                    if(mysqli_num_rows($ris_am) == 1){

                                        echo"<form id=\"rispostaForm\" action = \"res/PHP/eleva_a_rispostaFAQ.php\" method=\"POST\" >";

                                            echo"<input type=\"hidden\" name=\"dataRisp\" value=". $risposta['dataRisp'] . ">";
                                            echo"<input type=\"hidden\" name=\"IDRisp\" value=". $risposta['IDRisp'] . ">";
                                            echo "<span class =\"bottone\"><input type=\"submit\" value=\"ELEVA A FAQ\"></span>";
                                        
                                        echo "</form>";
                                    }

                                    echo"<form id=\"bottoniForm\" action = \"res/PHP/segnala_contributo.php?from=risposta\" method=\"POST\" >";

                                        //mi invio la data della risposta
                                        echo"<input type=\"hidden\" name=\"data\" value=". $risposta['dataRisp'] . ">";

                                        //mi invio l'id del rispondente
                                        echo"<input type=\"hidden\" name=\"ID\" value=". $risposta['IDRisp'] . ">";
                                        echo "<span class =\"bottone\"><input type=\"submit\" value=\"SEGNALA\"></span>";
                                
                                    echo "</form>";
                                }
                                else{
                                    echo"<p id=\"new_question\">OPS... RISULTI BANNATO!</p>";
                                }        
                            }
                            else{
                                echo"<p id=\"new_question\"><a href=\"login.php\">LOGGATI PER VALUTARE LA RISPOSTA!</a></p>";
                            }

                        echo "</div>";                           
                    }
                    
                echo "</div>";
            }
        }

        //controllo se c'è almeno una domanda
        if(!$isDomanda){

            echo "<div class=\"container_sp\">";

                echo"<div class=\"domanda\">";

                    echo"<p id=\"no_question\">NON SEMBRANO ESSERCI DOMANDE QUI!</p>";

                    if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){
                        echo"<p id=\"new_question\"><a href=\"aggiungi_domanda_prodotto.php\">CLICCAMI PER INSERIRE UNA NUOVA DOMANDA!</a></p>";
                    }
                    else{
                        echo"<p id=\"new_question\"><a href=\"login.php\">LOGGATI PER INSERIRE UNA NUOVA DOMANDA!</a></p>";
                    }
                    
                echo "</div>";

            echo "</div>";
        }

    }    

    //funzione utile per calcolare la reputazione
    function calcolaReputazione($id, $xmlpath){

        include("connection.php");
        $connessione = new mysqli($host, $user, $password, $db);

        //ora dovrei prendere tutte le risposte di questo utente ed applicare la formula di ciascun voto
        $domande = getDomande($xmlpath);

        $temp_num = 0;  //Qui ci metto i calcoli da fare per il numeratore della formula
        $temp_den = 0;  //Qui ci metto i calcoli da fare per il denominatore della formula
        $temp_norm = 10/8; //Qui ci metto la costante per effettuare la normalizzazione

        $reputazione_def = 0;

        //prima controllo le valutazioni delle domande
        foreach ($domande as $domanda) {

            if($domanda['IDDom'] == $id){

                foreach($domanda['votazioni'] as $votazione){

                    $temp_num = $temp_num + (($votazione['supporto'] + $votazione['utilita']) * $votazione['reputazione']);
                    $temp_den = $temp_den + $votazione['reputazione'];
                }
            }
        }

        //poi le valutazioni delle risposte
        foreach($domande as $domanda){

            foreach ($domanda['risposte'] as $risposta) {

                if ($risposta['IDRisp'] == $id) {
    
                    foreach ($risposta['votazioni'] as $votazione) {
    
                        // Calcola la reputazione per questa risposta e la aggiunge al totale
                        $temp_num = $temp_num + (($votazione['supporto'] + $votazione['utilita']) * $votazione['reputazione']);
                        $temp_den = $temp_den + $votazione['reputazione'];
                    }
                }
            }
        }

        //devo controllare se $temp_den è diverso da 0 altrimenti darebbe errore in quanto si farebbe una divisone per 0
        if($temp_den == 0){
            $reputazione_def = 1;
        }
        else{
            $reputazione_def = ($temp_norm * $temp_num)/$temp_den; //calcolo finale per la reputazione
        }
            

        //faccio un controllo, se la reputazione supera 10 allora deve essere arrotondato a 10
        if($reputazione_def > 10){
            $reputazione_def = 10;
        }

        $reputazione_difetto_def = floor($reputazione_def); //arrotonda per difetto

        // echo $reputazione_difetto_def;
        //una volta calcolata devo aggiornare il campo corrispettivo nel DB
        $query = "UPDATE utenteMangaNett 
                        SET reputazione = '$reputazione_difetto_def'
                                WHERE id IN (SELECT id FROM utenteMangaNett WHERE username = '{$_SESSION['nome']}')";

        $result = $connessione->query($query);

        //Verifico se la query ha restituito risultati
        if (!$result) {
            echo "Errore nella query: " . $connessione->error;
            exit(1);
        }

        return $reputazione_difetto_def;
    }

    //funzione per stampare le recensioni, in essa troviamo anche il form per inserirne una nuova
    function mostraRecensioni($xmlPath){
        
        include('connection.php');

        $connessione = new mysqli($host, $user, $password, $db);

        $fumetti = getFumetti($xmlPath);

        foreach($fumetti as $fumetto){
            
            if(isset($_SESSION['info_titolo']) && $_SESSION['info_titolo'] == $fumetto['titolo']){
                
                foreach($fumetto['recensione'] as $recensione){

                    //dataRec
                    $parti_rec = explode("T", $recensione['dataRecensione']);

                    $data_rec = $parti_rec[0];
                    $ora_rec = $parti_rec[1];
                    
                    //nome del recensore
                    $query = "SELECT umn.username FROM utenteMangaNett umn WHERE umn.id = {$recensione['IDRecensore']}";
                    $ris = $connessione->query($query);

                    if ($ris) {
                        $row = $ris->fetch_assoc();
                        $username_recensore = $row['username']; 
                    }
                    else{
                        exit(1);
                    }

                    echo "<div class=\"container_sp\">";

                        echo "<div class=\"domanda\">";

                        echo"<div class=\"info-domanda\">";
                            echo"<p class=\"utente\">$username_recensore</p>";
                            echo"<p class=\"data\">" . $data_rec . " ". $ora_rec ."</p>";
                            echo "<p class=\"data\">RECENSIONE</p>";
                        echo"</div>";

                        echo"<p class=\"testo-domanda\">" . $recensione['testoRecensione'] . "</p>";

                    echo "</div>";

                }
            }
        }
    }
?>