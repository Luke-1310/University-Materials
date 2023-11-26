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
            $scontoGenerico = $fumetto->getElementsByTagName('sconto_generico')->item(0)->nodeValue;
            
            //mi prendo i parametri dello sconto
            $sconto = $fumetto->getElementsByTagName('sconto')->item(0);

            $sconto_mesi = $sconto->getElementsByTagName('X')->item(0)->nodeValue;
            $sconto_anni = $sconto->getElementsByTagName('Y')->item(0)->nodeValue;
            $sconto_cr_data = $sconto->getElementsByTagName('M')->item(0)->nodeValue;
            $sconto_da_data = $sconto->getElementsByTagName('data_M')->item(0)->nodeValue;
            $sconto_crediti = $sconto->getElementsByTagName('N')->item(0)->nodeValue;
            $sconto_reputazione = $sconto->getElementsByTagName('R')->item(0)->nodeValue;
            $ha_acquistato = $sconto->getElementsByTagName('ha_acquistato')->item(0)->nodeValue;

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

                $votazioniNodes = $recensioneNode->getElementsByTagName('votazione_recensione');

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
                'sconto_generico' => $scontoGenerico,
                'bonus' => $bonus,
                
                'img' => $ISBN,

                'X' => $sconto_mesi,
                'Y' => $sconto_anni,
                'M' => $sconto_cr_data,
                'data_M' => $sconto_da_data,
                'N' => $sconto_crediti,
                'R' => $sconto_reputazione,
                'ha_acquistato' => $ha_acquistato,

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

    //funzione con lo scopo di caricare le domande e risposte su un certo prodotto da fixare
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

    //funzione per caricare tutti gli acquisti salvati
    function getAcquisti($xmlFile){

        $acquisti = [];

        $acquisti_doc = new DOMDocument();
        $acquisti_doc->load($xmlFile);

        $storico = $acquisti_doc->getElementsByTagName('acquisto');

        foreach($storico as $acquisto){

            $IDUtente = $acquisto->getElementsByTagName('IDUtente')->item(0)->nodeValue;
            $data = $acquisto->getElementsByTagName('data')->item(0)->nodeValue;
            $bonus = $acquisto->getElementsByTagName('bonus')->item(0)->nodeValue;

            $fumetti = $acquisto->getElementsByTagName('fumetto');

            $fumetti_acquistati = [];

            foreach($fumetti as $fumetto){

                $titolo = $fumetto->getElementsByTagName('titolo')->item(0)->nodeValue;
                $isbn = $fumetto->getElementsByTagName('isbn')->item(0)->nodeValue;
                $quantita = $fumetto->getElementsByTagName('quantita')->item(0)->nodeValue;
                $prezzo = $fumetto->getElementsByTagName('prezzo')->item(0)->nodeValue;

                $fumetti_acquistati[] = [
                    'titolo' =>$titolo,
                    'isbn' =>$isbn,
                    'quantita' =>$quantita,
                    'prezzo' =>$prezzo,
                ];
            }

            $acquisti[] = [
                'IDUtente' => $IDUtente,
                'data' => $data,
                'bonus' => $bonus,
                'fumetti' => $fumetti_acquistati,
            ];
        }

        return $acquisti;
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

    //funzione per stampare le recensioni, in essa troviamo anche il form per inserirne una nuova
    function mostraRecensioni($xmlPath){
    
        include('connection.php');

        $connessione = new mysqli($host, $user, $password, $db);

        $fumetti = getFumetti($xmlPath);

        $isRecensione = false;

        foreach($fumetti as $fumetto){
            
            if(isset($_SESSION['info_titolo']) && $_SESSION['info_titolo'] == $fumetto['titolo']){
                
                foreach($fumetto['recensione'] as $recensione){

                    $isRecensione = true;

                    $parti_rec = explode("T", $recensione['dataRecensione']);
                    $data_rec = $parti_rec[0];
                    $ora_rec = $parti_rec[1];
                    
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

                        echo "<div class=\"recensione\">";

                        echo"<div class=\"info-recensione\">";
                            echo"<p class=\"utente\">$username_recensore</p>";
                            echo"<p class=\"data\">" . $data_rec . " ". $ora_rec ."</p>";
                            echo "<p class=\"data\">RECENSIONE</p>";
                        echo"</div>";

                        echo"<p class=\"testo-recensione\">" . $recensione['testoRecensione'] . "</p>";

                        if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){
                            
                            $query = "SELECT umn.ban FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";
                            $ris = $connessione->query($query);

                            if ($ris) {

                                $row = $ris->fetch_assoc();
                                $ban = $row['ban'];
                            }
                            else{
                                exit(1);
                            }

                            if($ban == 0){

                                $query_v = "SELECT umn.id FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";
                                $ris_v = $connessione->query($query_v);

                                if ($ris_v) {
                                    $row_v = $ris_v->fetch_assoc();
                                    $id_valutante = $row_v['id']; 
                                }
                                else{
                                    exit(1);
                                }

                                $ha_votato = false;

                                if (isset($recensione['votazioni'])) {
                                
                                    foreach($recensione['votazioni'] as $votazione) {
                                        
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
                                            echo"<input type=\"hidden\" name=\"ID\" value=". $recensione['IDRecensore'] .">";
                                            echo"<input type=\"hidden\" name=\"data\" value=". $recensione['dataRecensione'] .">";
                                            echo"<input type=\"hidden\" name=\"tipo\" value=\"recensione\">";

                                        echo "</div>";
                                
                                        echo "<span class=\"bottone\"><input type=\"submit\" value=\"INVIA\"></span>";

                                    echo "</form>";
                                }

                                //form AGGIUNGI RECENSIONE
                                echo"<form id=\"rispostaForm\" action=\"res/PHP/aggiungi_recensione.php\" method=\"POST\" >";

                                    echo"<div class=\"form-row\">";
                                        echo"<label for=\"risposta\">AGGIUNGI UNA RECENSIONE...</label>";
                                        echo "<textarea id=\"risposta\" name=\"recensione\" rows=\"10\" cols=\"40\" placeholder=\"Inserisci qui la tua recensione....\" required></textarea>";
                                    echo"</div>";

                                    echo"<input type=\"hidden\" name=\"ID\" value=". $recensione['IDRecensore'] . ">";
                                    echo "<span class =\"bottone\"><input type=\"submit\" value=\"INVIA\"></span>";

                                echo "</form>";

                                echo"<form id=\"bottoniForm\" action = \"res/PHP/segnala_contributo.php?from=recensione\" method=\"POST\" >";

                                    echo"<input type=\"hidden\" name=\"data\" value=". $recensione['dataRecensione'] . ">";
                                    echo"<input type=\"hidden\" name=\"ID\" value=". $recensione['IDRecensore'] . ">";
                                    echo "<span class =\"bottone\"><input type=\"submit\" value=\"SEGNALA\"></span>";
                                
                                echo "</form>";
                            }

                            else{
                                echo"<p id=\"new_question\">OPS... RISULTI BANNATO!</p>";
                            }
                        }
                        else{
                            echo"<p id=\"new_question\"><a href=\"login.php\">LOGGATI PER INSERIRE UNA NUOVA RECENSIONE!</a></p>";
                        }

                    echo "</div>";
                }
            }
        }

        //controllo se c'è almeno una domanda
        if(!$isRecensione){

            echo "<div class=\"container_sp\">";

                echo"<div class=\"domanda\">";

                    echo"<p id=\"no_question\">NON SEMBRANO ESSERCI RECENSIONI QUI!</p>";

                    if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){
                        echo"<p id=\"new_question\"><a href=\"aggiungi_domanda_prodotto.php\">CLICCAMI PER INSERIRE UNA NUOVA DOMANDA!</a></p>";
                    }
                    else{
                        echo"<p id=\"new_question\"><a href=\"login.php\">LOGGATI PER INSERIRE UNA NUOVA RECENSIONE!</a></p>";
                    }
                    
                echo "</div>";

            echo "</div>";
        }
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
    function getCreditiSpesiTotCurr($xmlPath){

        if (isset($_SESSION['nome'])) {

            include("connection.php");
            $connessione = new mysqli($host, $user, $password, $db);

            $acquisti = getAcquisti($xmlPath);

            $spesi_utente = 0;

            $query = "SELECT umn.id FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.username = '{$_SESSION['nome']}'";
            $result = $connessione->query($query);
    
            if ($result) {
                $row = $result->fetch_assoc();
                $id_loggato = $row['id'];
            } 

            foreach($acquisti as $acquisto){

                if($id_loggato == $acquisto['IDUtente']){

                    foreach($acquisto['fumetti'] as $fumetto){

                        $spesi_utente += $fumetto['prezzo'];
                    }
                }
            }

            return $spesi_utente;
        }
    }

    //funzione utile per calcolare la reputazione
    function calcolaReputazione($id, $xmlpath, $xmlpath_fumetti){

        include("connection.php");
        $connessione = new mysqli($host, $user, $password, $db);

        $domande = getDomande($xmlpath);
        $fumetti = getFumetti($xmlpath_fumetti);

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

        //infine valuto le valutazioni dei fumetti
        foreach($fumetti as $fumetto){

            foreach($fumetto['recensione'] as $recensione){

                if($recensione['IDRecensore'] == $id){

                    foreach($recensione['votazioni'] as $votazione){

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

    //funzione per sommare la quantita di crediti bonus in caso di completamento dell'acquisto
    function calcolaBonusAcquisto(){

        if (isset($_SESSION['carrello'])) {

            $somma_totale = 0;
            
            foreach ($_SESSION['carrello'] as $fumetto_carrello) {

                $somma_totale += $fumetto_carrello['bonus']*$fumetto_carrello['quantita'];

            }
        }

        return $somma_totale;
    }

    //funzione per sommare la quantita di crediti per completare l'acquisto
    function calcolaSpesaNoSconto(){

        if (isset($_SESSION['carrello'])) {

            $somma_totale = 0;
            
            foreach ($_SESSION['carrello'] as $fumetto_carrello) {
        
                $quantita = $fumetto_carrello['quantita'];
                $prezzo = $fumetto_carrello['prezzo'];

                $somma_totale += $prezzo * $quantita;
            }
        }

        //in questo modo non mi approssima i numeri => ex 19.13 in 19.1
        $somma_totale = number_format($somma_totale, 2, '.', '');
        return $somma_totale;
    }

    //funzione per calcolare il prezzo di un fumetto scontato
    function calcolaScontoFumetto($xmlpath_fumetti,$isbnFumetto, $prezzoFumetto){
        
        $prezzoFinale = 0;

        if (isset($_SESSION['carrello'])) {

            include('connection.php');

            $connessione = new mysqli($host, $user, $password, $db);

            $fumetti_documento = getFumetti($xmlpath_fumetti);

            $sconto_percentuale = 0;
            
            foreach($fumetti_documento as $fumetto_documento){

                if($fumetto_documento['isbn'] == $isbnFumetto){
                        
                    $sc_X = $fumetto_documento['X'];
                    $sc_Y = $fumetto_documento['Y'];
                    $sc_M = $fumetto_documento['M'];
                    $sc_data_M = $fumetto_documento['data_M'];
                    $sc_N = $fumetto_documento['N'];
                    $sc_R = $fumetto_documento['R'];
                    $sc_ha_acquistato = $fumetto_documento['ha_acquistato'];
    
                    $sc_generico = $fumetto_documento['sconto_generico'];
    
                    //parto con i parametri X e Y
                    
                    //1)inizio calcolandomi la data completa X mesi + Y anni 
                    $XY = $sc_Y*12 + $sc_X;

                    $dataMinimaRegistrazione = new DateTime(); 
                    $dataMinimaRegistrazione->sub(new DateInterval("P{$XY}M"));

                    //2)ora devo prendermi la data di registrazione e controllare se la data di registrazione > di $data rispetto a quella attuale
                    $query ="SELECT umn.data_registrazione FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";
                    $ris = $connessione->query($query);
    
                    if(mysqli_num_rows($ris) == 1){
                        $row = $ris->fetch_assoc();
                        $data_registrazione = $row['data_registrazione'];
                    }
                    
                    //converto la data in formato DataTime per poter fare la differenza
                    $data_formattata_registrazione = DateTime::createFromFormat('Y-m-d', $data_registrazione);

                    // echo $dataMinimaRegistrazione->format('Y-m-d');
                    // echo $data_formattata_registrazione->format('Y-m-d');                  

                    if ($data_formattata_registrazione <= $dataMinimaRegistrazione) {
                        $X_Y_check = true;
                    }
                    else{
                        $X_Y_check = false;
                    }
    
                    //ora devo gestirmi i parametri M e data_M
    
                    //1)mi preparo il necessario ovvero l'id dell'utente loggato e mi carico gli acquisti
                    $xmlpath_acquisti = "res/XML/storico_acquisti.xml";
                    $acquisti = getAcquisti($xmlpath_acquisti);
    
                    $query ="SELECT umn.id FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";
                    $ris = $connessione->query($query);
    
                    if(mysqli_num_rows($ris) == 1){
                        $row = $ris->fetch_assoc();
                        $id_loggato = $row['id'];
                    }
    
                    $spesa_totale_entro_data = 0;
    
                    //2)ora faccio il controllo se si è spesa un certo ammontare di crediti entro una certa data
                    foreach($acquisti as $acquisto){
    
                        if($acquisto['IDUtente'] == $id_loggato){
    
                            //ora che mi trovo nell'ordine corretto, devo vedere se è stato fatto entro una certa data
                            if($acquisto['data'] <= $sc_data_M){
    
                                foreach($acquisto['fumetti'] as $fumetto){
                                    
                                    $spesa_totale_entro_data += $fumetto['prezzo'];
                                }
                            }
                        }
                    }
    
                    //3)controllo se la quantita spesa entro una certa data è almeno uguale a quella dello sconto parametrico
                    if($spesa_totale_entro_data >= $sc_M){
                        $M_data_da_M_check = true;
                    }
                    else{
                        $M_data_da_M_check = false;
                    }
    
                    $spesa_totale = 0;
    
                    //ora mi devo occupare del parametro N, ovvero se sono stati spesi un certo ammontare di crediti in totale
                    foreach($acquisti as $acquisto){
    
                        if($acquisto['IDUtente'] == $id_loggato){
    
                            foreach($acquisto['fumetti'] as $fumetto){
                                
                                $spesa_totale += $fumetto['prezzo'];
                            }
                        
                        }
                    }
    
                    if($spesa_totale >= $sc_N){
                        $N_check = true;
                    }
                    else{
                        $N_check = false;
                    }
    
                    //ora controllo che il cliente loggato abbia una certa reputazione, ovvero il paramentro R
                    $query ="SELECT umn.reputazione FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";
                    $ris = $connessione->query($query);
    
                    if(mysqli_num_rows($ris) == 1){
                        $row = $ris->fetch_assoc();
                        $reputazione_loggato = $row['reputazione'];
                    }
    
                    if($reputazione_loggato >= $sc_R){
                        $R_check = true;
                    }
                    else{
                        $R_check = false;
                    }
    
                    //come ultimo controllo dello sconto parametrico devo vedere se l'utente ha già compranto un certo fumetto
                    
                    $ha_acquistato_check = false;
                    
                    foreach($acquisti as $acquisto){
    
                        if($acquisto['IDUtente'] == $id_loggato){
    
                            if($sc_ha_acquistato == "0000000000000"){
                                $ha_acquistato_check = true;
                                break;
                            }

                            foreach($acquisto['fumetti'] as $fumetto){
                                
                                if($sc_ha_acquistato == $fumetto['isbn']){
                                    $ha_acquistato_check = true;
                                    break;
                                }
                            }
                        }
                    }
                    
                    // echo $row['data_registrazione'];
                    // echo "X =>". $X_Y_check . "M =>". $M_data_da_M_check . "N =>". $N_check . "R => " . $R_check . "hacq =>". $ha_acquistato_check;
                    
                    //ora ho controllato TUTTI i parametri, se tutte le variabili booleane sono a true aggiungo un 5% come sconto
                    if($X_Y_check && $M_data_da_M_check && $N_check && $R_check && $ha_acquistato_check){
                        $sconto_percentuale += 5;
                        $_SESSION['sconto parametrico'] = true;
                    }
    
                    //ora ci sommo anche lo sconto generico se diverso da 0
                    if($sc_generico > 0){
                        $sconto_percentuale += $sc_generico;
                        $_SESSION['sconto generico'] = true;
                    }
                }
            }
        }

        $quantitaPercentuale = $prezzoFumetto * ($sconto_percentuale / 100);

        $prezzoFinale = $prezzoFumetto - $quantitaPercentuale;

        //in questo modo non mi approssima i numeri => ex 19.1 in 19.13
        $prezzoFinale = number_format($prezzoFinale, 2, '.', '');

        //se non mi è stato modificato il prezzo con quello scontato metto quello originale
        if($prezzoFinale == 0){
            $prezzoFinale == $prezzoFumetto;
        }

        return $prezzoFinale;
    }
    

?>