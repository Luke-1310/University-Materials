<?php
    session_start();

    if (isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro") {
        echo '<link rel="stylesheet" href="res/CSS/external_domp_dark.css" type="text/css" />';
    } else {
        echo '<link rel="stylesheet" href="res/CSS/external_domp.css" type="text/css" />';
    }
?>

<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Inserisci una nuova domanda</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <?php 
        $pagina_corrente = "aggiungi_domanda_prodotto";
        include('res/PHP/navbar.php');
        include('res/PHP/connection.php');
        include('res/PHP/funzioni.php');
    ?>
</head>

<body>
    <div class="container_external">

        <div class="container_reg">

            <form id="prodottoForm" action = "res/PHP/aggiungi_domanda_prodotto.php" method="POST">

                <?php
                    $xmlfile = "res/XML/catalogo.xml";

                    $fumetti = getFumetti($xmlfile);

                    //mi ordino i fumetti in base a A-Z
                    usort($fumetti, function ($a, $b) {                 //usort mi ordina i fumetti nell'array in base al titolo
                        return strcmp($a['titolo'], $b['titolo']);      //strcmp mi compara le due stringhe e mi fornisce un valore negativo/postivo se la
                    });                                                 //prima stringa è minore/maggiore della seconda, zero se sono uguali

                    echo"<h3>COMPILA IL FORM!</h3>";

                    echo"<div class=\"form-row\">";
                        
                        echo"<label for=\"titolo\">SELEZIONA IL TITOLO: </label>";
                        echo"<select name=\"titolo\" id=\"titolo\">";
                        foreach ($fumetti as $fumetto) {
                            echo "<option value='" .$fumetto['titolo'] . "'>" . $fumetto['titolo']. "</option>";
                        }
                        echo"</select>";

                        //mi invio la data in quel preciso momento
                        $dataCorrente = date('Y-m-d\TH:i:s');
                        echo"<input type=\"hidden\" name=\"data\" value=$dataCorrente>";
                    echo"</div>";
                ?>

                <div class="form-row">
                    <label for="testo">TESTO:</label>
                    <textarea id="testo" name="testo" rows="10" cols="60" placeholder="Inserisci qui la domanda..." required></textarea>
                </div>

                <span class ="bottone"><input type="submit" value="INVIA"></span>
            </form>
        </div>
    </div>
</body>

<?php include('res/PHP/footer.php'); ?>

</html>
