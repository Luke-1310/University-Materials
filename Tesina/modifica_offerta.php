<?php
    session_start();

    if (isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro") {
        echo '<link rel="stylesheet" href="res/CSS/external_modifica_offerta_dark.css" type="text/css" />';
    } else {
        echo '<link rel="stylesheet" href="res/CSS/external_modifica_offerta.css" type="text/css" />';
    }
?>

<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Modifica offerta</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <?php 
        $pagina_corrente = "modifica_offerta";
        include('res/PHP/navbar.php');
        include('res/PHP/connection.php');
        include('res/PHP/funzioni.php');
    ?>
</head>

<script>
    //reindirizza il browser e codifica l'invio del titolo attraverso l'URL per gestire i parametri speciali etc...
    function inviaTitolo(titolo) {
        window.location.href = "res/PHP/gestione_titolo_offerta.php?titolo=" + encodeURIComponent(titolo);
    }
</script>

<body>
    <div class="container_external">

        <div class="container_reg">

            <form id="prodottoForm" action = "res/PHP/modifica_offerta.php" method="POST">

                <?php
                    // $_SESSION['richiesta_ok'] = true;
                    if(isset($_SESSION['richiesta_ok']) && $_SESSION['richiesta_ok'] = true){
                        echo "<h4 id=\"esito_positivo\">L'OPERAZIONE È ANDATA A BUON FINE!</h4>";
                        unset($_SESSION['richiesta_ok']);
                    }
                    
                    $xmlfile = "res/XML/catalogo.xml";

                    $fumetti = getFumetti($xmlfile);

                    //mi ordino i fumetti in base a A-Z
                    usort($fumetti, function ($a, $b) {                 //usort mi ordina i fumetti nell'array in base al titolo
                        return strcmp($a['titolo'], $b['titolo']);      //strcmp mi compara le due stringhe e mi fornisce un valore negativo/postivo se la
                    });                                                 //prima stringa è minore/maggiore della seconda, zero se sono uguali

                    echo"<h3>COMPILA IL FORM!</h3>";

                    echo"<div class=\"form-row\">";
                        
                        echo"<label for=\"titolo\">SELEZIONA IL TITOLO: </label>";
                        echo"<select name=\"titolo\" id=\"titolo\" onchange=\"inviaTitolo(this.value)\">";

                        foreach ($fumetti as $fumetto) {
                            $titolo_select = $fumetto['titolo'];
                            $isSelected = ($_SESSION['titolo_selezionato'] === $titolo_select) ? 'selected' : '';
                            echo "<option value='$titolo_select' $isSelected>$titolo_select</option>";
                        }

                        echo"</select>";
                        ewfuibebiufwebui
                        $dataCorrente = date('Y-m-d\TH:i:s');
                        echo"<input type=\"hidden\" name=\"data\" value=$dataCorrente>";
                    echo"</div>";
                ?>

                <div class="titoletto">

                    <div class="tooltip">
                        <span class="tooltiptext">INFORMAZIONI UTILI:
                        <ul>
                            <li>NEL PARAMETRO "HA GIÀ ACQUISTATO" DEVI INSERIRE L'ISBN DI UN FUMETTO CHE L'UTENTE DEVE AVER ACQUISTATO PER FARSÌ CHE SI ATTIVI L'OFFERTA</li>
                            <li>NEL CASO IN CUI QUALCHE PARAMETRO NON SIA UTILE AL FINE DELL'IMPLEMENTAZIONE DELLO SCONTO, RIEMPIRE IL CAMPO CON UNO ZERO O PIÙ ZERI</li>
                            <li>IN GENERALE, SE SI VUOLE ELIMINARE LO SCONTO PARAMETRICO BASTA METTERE VALORI NON COERENTI COME AD ESEMPIO "NUMERO MINIMO DI ANNI DI REGISTRAZIONE" PARI A 10</li>
                            <li>LO SCONTO PARAMETRICO COMPORTA UNO SCONTO DEL 5% SUL TOTALE.</li>
                        </ul>    

                        </span>
                        <i id="info" class="material-icons">info</i>
                    </div>

                    <h3>SCONTO PARAMETRICO:</h3>
                </div>
                               
                <!-- per non inserire troppi campi, suppongo che lo sconto parametrico sia sempre pari al 5%, se attivato in base ai parametri-->
                <div class="form-row">
                    <label for="registrazione">MINIMO MESI DI REGISTRAZIONE</label>
                    <input type="integer" id="registrazione" name="registrazione_mesi" min="1" max="12" placeholder="1" 
                        value="<?php  if(isset($_SESSION['form_off_X'])) echo $_SESSION['form_off_X']; ?>" required>
                </div>

                <div class="form-row">
                    <label for="registrazione">MINIMO ANNI DI REGISTRAZIONE</label>
                    <input type="integer" id="registrazione" name="registrazione_anni" min="0" max="20" placeholder="1" 
                        value="<?php  if(isset($_SESSION['form_off_Y'])) echo $_SESSION['form_off_Y']; ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="crediti_crediti_data">CREDITI SPESI DA UNA CERTA DATA</label>
                    <input type="integer" id="crediti_data" name="crediti_data" placeholder="100" 
                        value="<?php  if(isset($_SESSION['form_off_M'])) echo $_SESSION['form_off_M']; ?>" required>
                </div>

                <div class="form-row">
                    <label for="crediti">DATA DA CUI PARTIRE PER LO SCONTO</label>
                    <input type="text" id="data" name="da_data" placeholder="1999-01-01" 
                        value="<?php  if(isset($_SESSION['form_off_data_M'])) echo $_SESSION['form_off_data_M']; ?>" required pattern="\d{4}-\d{2}-\d{2}">
                </div>

                <div class="form-row">
                    <label for="crediti">MINIMO DI CREDITI SPESI IN TOTALE</label>
                    <input type="integer" id="crediti" name="crediti" placeholder="100" 
                        value="<?php  if(isset($_SESSION['form_off_N'])) echo $_SESSION['form_off_N']; ?>" required>
                </div>

                <div class="form-row">
                    <label for="reputazione">MINIMO DI REPUTAZIONE</label>
                    <input type="integer" id="reputazione" name="reputazione" placeholder="2" 
                        value="<?php  if(isset($_SESSION['form_off_R'])) echo $_SESSION['form_off_R']; ?>" required>
                </div>

                <div class="form-row">
                    <label for="ha_acquistato">HA GIÀ ACQUISTATO</label>
                    <input type="text" pattern="[0-9]{13}" maxlength="13" id="ISBN" name="ha_acquistato" placeholder="9798431410840" 
                        value="<?php  if(isset($_SESSION['form_off_ha_acquistato'])) echo $_SESSION['form_off_ha_acquistato']; ?>" required>
                </div>
                
                <h3>INFORMAZIONI SCONTO:</h3>
                <div class="form-row">
                    <label for="generico">SCONTO GENERICO (%)</label>
                    <input type="number" id="generico" name="generico" placeholder="2" min="0" max="30" 
                        value="<?php  if(isset($_SESSION['form_off_generico'])) echo $_SESSION['form_off_generico']; ?>" required>
                </div>

                <div class="form-row">
                    <label for="bonus">BONUS CREDITI</label>
                    <input type="number" id="bonus" name="bonus" placeholder="2" min="0" max="5"
                        value="<?php  if(isset($_SESSION['form_off_bonus'])) echo $_SESSION['form_off_bonus']; ?>" required>
                </div>

                <span class ="bottone"><input type="submit" value="INVIA"></span>
            </form>
        </div>
    </div>
</body>

<?php include('res/PHP/footer.php'); ?>

</html>
