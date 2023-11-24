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
    //metto il titolo dentro una variabile di sessione perché se cambio il tema nella pagina delle info mi da errore, si perdono i dati passati col POST
    // Verifica se $titolo non è vuoto prima di aggiornare la variabile di sessione
    if (!empty($titolo)) {
        $_SESSION['info_titolo'] = $titolo;
    }

    //controllo errori
    if(isset($_SESSION['segnalazione_ok']) && $_SESSION['segnalazione_ok'] == true){
        echo "<h4 id=\"esito_positivo\">LA SEGNALAZIONE È ANDATA A BUON FINE!</h4>";
        unset($_SESSION['segnalazione_ok']);
    }

    if(isset($_SESSION['richiesta_ok']) && $_SESSION['richiesta_ok'] = true){
        echo "<h4 id=\"esito_positivo\">L'OPERAZIONE È ANDATA A BUON FINE!</h4>";
        unset($_SESSION['richiesta_ok']);
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

        if(isset($_SESSION['info_titolo']) && $_SESSION['info_titolo'] == $fumetto['titolo']){

            //ci troviamo nel fumetto corretto, mi prendo tutti i dati
            $autoreNome = $fumetto['nome_autore'];
            $autoreCognome = $fumetto['cognome_autore'];
            $sinossi = $fumetto['sinossi'];
            $pagine = $fumetto['lunghezza'];
            $prezzo = $fumetto['prezzo'];
            $uscita = $fumetto['data'];
            $editore = $fumetto['editore'];
            $quantita = $fumetto['quantita'];
            $ISBN = $fumetto['isbn'];
            $bonus = $fumetto['bonus'];

            //mancano parametri    
            $mesi = $fumetto['X'];
            $crediti = $fumetto['N'];
            $reputazione = $fumetto['R'];
        
        }
    }

    //ora mi devo prendere anche i parametri dell'utente per vedere se rientra nello sconto
    $connessione = new mysqli($host, $user, $password, $db);

    // $reputazione_utente = getReputazioneCurr();

    // $mesi_trascorsi = getDataRegistrazioneCurr();

    // $spesi_utente = getCreditiSpesiCurr();

    //Compongo il nome completo dell'immagine da stampare
    $nomeImg = $ISBN . $ext;
    ?>

    <div class="container">
        <div class="img-text">
            <?php echo "<img src='" . $pathImg . $nomeImg . "' alt=\"Copertina.jpg\">";
          
            echo "<div class=\"button-row\">";

                //faccio una query per controllare se l'utente è un GS o AM, se sì allora stampo il bottone
                if (isset($_SESSION['nome'])) {
                    $query = "SELECT um.ruolo FROM  utenteMangaNett um  WHERE um.username = '{$_SESSION['nome']}' AND (um.ruolo = 'SA' || um.ruolo = 'AM' || um.ruolo = 'GS')";
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

            <div class="descrizione-libro">
                <h4>SINOSSI</h4>
                <p><?php echo $sinossi; ?></p>
            </div>

            <div class="info-prezzo">

                <div class="info-field">
                    <span class="field-label">PREZZO:</span>
                    <span class="field-value"><?php echo $prezzo . " CREDITI"; ?></span>
                </div>
            </div>

            <?php

                echo "<div class=\"info-sconto\">";

                    echo "<div class=\"info-field\">";
                        echo "<span class=\"field-label\">BONUS: </span>";
                        echo "<span class=\"field-value\">" . $bonus . " CREDITI </span>";
                    echo "</div>";

                echo "</div>";
                
                echo "<div class=\"info-bottone\">";

                    if($quantita != 0){

                        echo "<form action=\"res/PHP/carrello.php\" method=\"POST\">";
                            echo "<input type=\"hidden\" name=\"isbn\" value='" . $ISBN . "'>";
                            echo "<input type=\"hidden\" name=\"prezzo\" value='" . $prezzo . "'>";
                            echo "<input type=\"hidden\" name=\"bonus\" value='" . $bonus . "'>";
                            echo "<span class=\"bottone_carrello\"><h5><input type=\"submit\" name=\"Disponibile\" value=\"AGGIUNGI AL CARRELLO\"></h5></span>";
                        echo "</form>";
                    }
                    else{
                        echo "<span class=\"bottone_carrello\"><h5><input type=\"button\" name=\"nonDisponibile\" value=\"NON DISPONIBILE\"></h5></span>";
                    }

                echo "</div>";
            ?>

        </div>

    </div>

    <p class="titolo-review">DOMANDE E RISPOSTE</p>

    <?php

        $xmlPath = "res/XML/Q&A.xml";

        //variabile di sessione col fine di capire da dove proviene la richiesta
        $_SESSION['provenienza_valutazione'] = "prodotti_info.php";
        mostraDomande($ISBN, $xmlPath);

        echo "<p class=\"titolo-review\">RECENSIONI</p>";
        mostraRecensioni($pathXml);
    ?>

</body>

<?php include('res/PHP/footer.php') ?>

</html>
