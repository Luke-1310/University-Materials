<?php
session_start();

if (isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro") {
    echo "<link rel=\"stylesheet\" href=\"res/CSS/external_info_dark.css\" type=\"text/css\" />";
} else {
    echo "<link rel=\"stylesheet\" href=\"res/CSS/external_info.css\" type=\"text/css\" />";
}
?>

<?php
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<head>
    <title>Informazioni prodotto</title>
</head>

<?php
$pagina_corrente = "prodotti_info";
include('res/PHP/connection.php');
include('res/PHP/navbar.php');
include('res/PHP/funzioni.php');
?>

<body>

    <?php
    if (isset($_POST['titolo'])) {
        $titolo = $_POST["titolo"];
    }
    //metto il titolo dentro una variabile di sessione perché se cambio il tema nella pagina delle info mi da errore
    //preso il titolo stampo tutte le informazioni
    // Verifica se $titolo non è vuoto prima di aggiornare la variabile di sessione
    if (!empty($titolo)) {
        $_SESSION['info_titolo'] = $titolo;
    }

    //prendo il percorso dell'immagine
    $pathImg = "res/WEBSITE_MEDIA/PRODUCT_MEDIA/";

    //Estensione dell'immagine la quale dovrebbe essere jpg
    $ext = ".jpg";

    //prendo il percorso del file xml
    $pathXml = "res/XML/catalogo.xml";

    $fumetti = getFumetti($pathXml);

    //itero attraverso gli elementi 'fumetto'
    foreach ($fumetti as $fumetto) {

        if ($fumetto['titolo'] == $_SESSION['info_titolo']) {

            //ci troviamo nel fumetto corretto, mi prendo tutti i dati
            $autoreNome = $fumetto['nome_autore'];
            $autoreCognome = $fumetto['cognome_autore'];
            $sinossi = $fumetto['sinossi'];
            $pagine = $fumetto['lunghezza'];
            $prezzo = $fumetto['prezzo'];
            $uscita = $fumetto['data'];
            $editore = $fumetto['editore'];
            $ISBN = $fumetto['isbn'];
            $bonus = $fumetto['bonus'];
                
            $mesi = $fumetto['X'];
            $crediti = $fumetto['N'];
            $reputazione = $fumetto['R'];
        
        }
    }

    //ora mi devo prendere anche i parametri dell'utente per vedere se rientra nello sconto
    $connessione = new mysqli($host, $user, $password, $db);

    $reputazione_utente = getReputazioneCurr();

    $mesi_trascorsi = getDataRegistrazioneCurr();

    $spesi_utente = getCreditiSpesiCurr();

    //Compongo il nome completo dell'immagine da stampare
    $nomeImg = $ISBN . $ext;
    ?>

    <div class="container">
        <div class="img-text">
            <?php echo "<img src='" . $pathImg . $nomeImg . "' alt=\"Copertina.jpg\">";
          
            echo "<div class=\"button-row\">";

                //faccio una query per controllare se l'utente è un GS o AM, se sì allora stampo il bottone
                if (isset($_SESSION['nome'])) {
                    $query = "SELECT um.ruolo FROM  utenteMangaNett um  WHERE um.username = '{$_SESSION['nome']}' AND (um.ruolo = 'AM' || um.ruolo = 'GS')";
                    $ris = mysqli_query($connessione, $query);

                    if (mysqli_num_rows($ris) == 1) {
                        echo "<form id=\"modifica_prod\" action=\"modifica_prodotto.php\" method=\"POST\">";
                        echo "<input type=\"hidden\" name=prodotto value='{$_SESSION['info_titolo']}'>";
                        echo "<span class=\"bottone\"><button type=\"submit\" name=\"info\">MODIFICA PRODOTTO</button></span>";
                        echo "</form>";
                    }
                }
            echo"</div>";
            ?>

        </div>
        <div class="info-libro">

            <h4>INFORMAZIONI PRODOTTO</h4>

            <div class="info-field">
                <span class="field-label">TITOLO:</span>
                <span class="field-value"><?php echo $_SESSION['info_titolo']; ?></span>
            </div>

            <div class="info-field">
                <span class="field-label">ISBN:</span>
                <span class="field-value"><?php echo $ISBN; ?></span>
            </div>

            <div class="info-field">
                <span class="field-label">AUTORE:</span>
                <span class="field-value"><?php echo $autoreNome . " " . $autoreCognome; ?></span>
            </div>

            <div class="info-field">
                <span class="field-label">NUMERO DI PAGINE:</span>
                <span class="field-value"><?php echo $pagine; ?></span>
            </div>

            <div class="info-field">
                <span class="field-label">DATA DI USCITA:</span>
                <span class="field-value"><?php echo $uscita; ?></span>
            </div>

            <div class="info-field">
                <span class="field-label">CASA EDITRICE:</span>
                <span class="field-value"><?php echo $editore; ?></span>
            </div>

            <div class="info-prezzo">

                <div class="info-field">
                    <span class="field-label">PREZZO:</span>
                    <span class="field-value"><?php echo $prezzo . " CREDITI"; ?></span>
                </div>
            </div>

            <div class="info-sconto">
                <?php
                //se c'è il bonus, lo stampo
                if ($bonus != "0") {
                    echo "<div class=\"info-field\">";
                        echo "<span class=\"field-label\">BONUS: </span>";
                        echo "<span class=\"field-value\">" . $bonus . " CREDITI </span>";
                    echo "</div>";
                }

                if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){

                    //se TUTTI i parametri sono uguali a 0 allora questo prodotto non è soggetto a sconto
                    if($mesi != 0 || $crediti != 0 || $reputazione != 0){

                        //se l'utente fa rientra nei parametri dello sconto allora glielo faccio sapere
                        if($mesi < $mesi_trascorsi && $crediti < $spesi_utente && $reputazione < $reputazione_utente){
                            echo "<div class=\"info-field\">";
                                echo "<span class=\"field-label\">SCONTO: </span>";
                                echo "<span class=\"field-value\">SEI ELEGIBILE PER UNO SCONTO ADDIZIONALE!!</span>";
                            echo "</div>";
                        }
                    }
                }
                    
                ?>
            </div>

            <div class="descrizione-libro">
                <h4>SINOSSI</h4>
                <p><?php echo $sinossi; ?></p>
            </div>

        </div>

    </div>
        
    <?php 
    
    $xmlPath = "res/XML/Q&A.xml";
    $domande = getDomande($xmlPath);
    
    echo "<p class=\"titolo-review\">INSERISCI UNA DOMANDA SUL PRODOTTO!</p>";  

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
                    echo"</div>";

                    echo"<p class=\"testo-domanda\">" . $domanda['testoDom'] . "</p>";

                    if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){
                        
                        //form AGGIUNGI RISPOSTA
                        echo"<form id=\"rispostaForm\" action = \"res/PHP/mostra_domanda_specifica.php\" method=\"POST\" >";

                            echo"<div class=\"form-row\">";
                                echo"<label for=\"risposta\">AGGIUNGI UNA RISPOSTA...</label>";
                                echo "<textarea id=\"risposta\" name=\"risposta\" rows=\"10\" cols=\"40\" placeholder=\"Inserisci qui la tua risposta....\" required></textarea>";
                            echo"</div>";

                            //mi invio la data della domanda
                            echo"<input type=\"hidden\" name=\"data\" value=". $domanda['dataDom'] . ">";

                            //mi invio anche l'ISBN 
                            echo"<input type=\"hidden\" name=\"isbn\" value=$ISBN>";
                                echo "<span class =\"bottone\"><input type=\"submit\" value=\"INVIA\"></span>";
                        echo "</form>";
                    }
                    else{
                        echo"<p id=\"new_question\"><a href=\"login.php\">LOGGATI PER INSERIRE UNA NUOVA RISPOSTA!</a></p>";
                    }

                    //l'utente è loggato? se la rispsota è no darebbe un messaggio di errore all'utente non registrato
                    if(isset($_SESSION['nome'])){
                        $nome = $_SESSION['nome'];
                    }
                    else{
                        $nome = "";
                    }

                    //faccio questo controllo perché solo l'admin può elevare a FAQ una domanda
                    $sql_am = "SELECT u.ruolo FROM utentemanganett u WHERE u.username = '{$nome}' AND u.ruolo = 'AM'";
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
                        
                        echo "</div>";
                        
                        echo "<p class=\"testo-risposta\">" . $risposta['testoRisp'] . "</p>";
                        
                        //se l'utente ha già votato allora devo impedirgli di votare ancora
                        if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){

                            //mi devo prendere il nome utente corrispettivo del domandante se loggato
                            $query_v = "SELECT umn.id FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";

                            $ris_v = $connessione->query($query_v);

                            //Verifico se la query ha restituito risultati
                            if ($ris_v) {

                                //Estraggo il risultato come un array associativo
                                $row_v = $ris_v->fetch_assoc();
                                $id_valutante = $row_v['id']; 
                            }
                            else{
                                exit(1);
                            }
                        
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

                                        //mi invio l'id di chi ha fatto la risposta per fare dei controlli
                                        echo"<input type=\"hidden\" name=\"IDRisp\" value=". $risposta['IDRisp'] .">";
                                        
                                        //mi invio la data del rispondente per fare dei controlli
                                        echo"<input type=\"hidden\" name=\"dataRisp\" value=". $risposta['dataRisp'] .">";

                                    echo "</div>";
                                    
                                    echo "<span class=\"bottone\"><input type=\"submit\" value=\"INVIA\"></span>";

                                echo "</form>";
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

    ?>

</body>

<?php include('res/PHP/footer.php') ?>

</html>
