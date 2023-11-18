<?php
    session_start();

    if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_mod_prod_dark.css\" type=\"text/css\" />";
    }
    else{
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_mod_prod.css\" type=\"text/css\" />";
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
    <title>Aggiungi prodotto</title>
</head>

<?php 
    $pagina_corrente = "aggiungi_prodotto";
    include('res/PHP/navbar.php')
?>
<body>

    <div class="container_external">

        <div class="container_reg">

            <form id="prodottoForm" action = "res/PHP/aggiungi_prodotto.php" method="POST" enctype="multipart/form-data">

            <!-- errori del form -->
                <?php
                    if(isset($_SESSION['errore_typeFile']) && $_SESSION['errore_typeFile'] == 'true'){//isset verifica se errore è settata
                        echo "<h4>IL FORMATO DEL FILE NON È CORRETTO, DEVE ESSERE .jpg!</h4>";
                        unset($_SESSION['errore_typeFile']);//la unsetto altrimenti rimarrebbe la scritta
                    }

                    if(isset($_SESSION['errore_ag_titolo']) && $_SESSION['errore_ag_titolo'] == 'true'){//isset verifica se errore è settata
                        echo "<h4>IL TITOLO GIÀ ESISTE!</h4>";
                        unset($_SESSION['errore_ag_titolo']);//la unsetto altrimenti rimarrebbe la scritta
                    }

                    if(isset($_SESSION['errore_ag_isbn']) && $_SESSION['errore_ag_isbn'] == 'true'){//isset verifica se errore è settata
                        echo "<h4>L'ISBN GIÀ ESISTE!</h4>";
                        unset($_SESSION['errore_ag_isbn']);//la unsetto altrimenti rimarrebbe la scritta
                    }
                ?>
                
                <h3>INFORMAZIONI PRODOTTO:</h3>
                <div class="form-row">
                    <label for="titolo">TITOLO</label>
                    <input type="text" id="titolo" name="titolo" placeholder="SPY X FAMILY 1" required>   <!--Se settata (regist non corretta, la stampo)-->
                </div>
                
                <div class="form-row">
                    <label for="ISBN">ISBN</label>
                    <input type="text" pattern="[0-9]{13}" maxlength="13" id="ISBN" name="ISBN" placeholder="9798431410840" required>
                </div>

                <div class="form-row">
                    <label for="editrice">CASA EDITRICE</label>
                    <input type="text" id="editrice" name="editrice" placeholder="Planet Manga" required>
                </div>

                <div class="form-row">
                    <label for="nome">NOME AUTORE</label>
                    <input type="text" id="nome" name="nome" placeholder="Tastuya" required>
                </div>

                <div class="form-row">
                    <label for="cognome">COGNOME AUTORE</label>
                    <input type="text" id="cognome" name="cognome" placeholder="Endo" required>
                </div>
                
                <div class="form-row">
                    <label for="sinossi">SINOSSI</label>
                    <textarea id="sinossi" name="sinossi" rows="10" cols="60" placeholder="Questa storia parla di Twilight una spia che per compiere la sua missione..." required></textarea>
                </div>

                <div class="form-row">
                    <label for="pagine">NR DI PAGINE</label>
                    <input type="integer" pattern="[0-9]{1,4}" maxlength="4" name="lunghezza" id="lunghezza" placeholder="276" required>
                </div>

                <div class="form-row">
                    <label for="prezzo">PREZZO</label>
                    <input type="number" step="0.01" min="0" name="prezzo" id="prezzo" required placeholder="5.99">
                </div>


                <div class="form-row">
                    <label for="quantita">QUANTITÀ</label>
                    <input type="integer" pattern="[0-9]{1,3}" maxlength="3" id="quantita" name="quantita" placeholder="12" required>
                </div>
                
                <div class="form-row">
                    <label for="data">DATA DI USCITA</label>
                    <input type="text" id="data" name="data" placeholder="1999-01-01" required pattern="\d{4}-\d{2}-\d{2}">
                </div>

                <div class="form-row">
                    <label for="img">COPERTINA </label>
                    <input type="file" name="img" id="img">
                </div>
                
                <!-- <h3>SCONTO PARAMETRICO:</h3>
                <div class="form-row">
                    <label for="registrazione">MINIMO MESI DI REGISTRAZIONE</label>
                    <input type="integer" id="registrazione" name="registrazione_mesi" min="1" max="12" placeholder="1">
                </div>

                <div class="form-row">
                    <label for="registrazione">MINIMO ANNI DI REGISTRAZIONE</label>
                    <input type="integer" id="registrazione" name="registrazione_anni" min="0" max="20" placeholder="1">
                </div>
                
                <div class="form-row">
                    <label for="crediti_crediti_data">CREDITI SPESI DA UNA CERTA DATA</label>
                    <input type="integer" id="crediti_data" name="crediti_data" placeholder="100">
                </div>

                <div class="form-row">
                    <label for="crediti">DATA DA CUI PARTIRE PER LO SCONTO</label>
                    <input type="text" id="data" name="da_data" placeholder="1999-01-01" required pattern="\d{4}-\d{2}-\d{2}">
                </div>

                <div class="form-row">
                    <label for="crediti">MINIMO DI CREDITI SPESI IN TOTALE</label>
                    <input type="integer" id="crediti" name="crediti" placeholder="100">
                </div>

                <div class="form-row">
                    <label for="reputazione">MINIMO DI REPUTAZIONE</label>
                    <input type="integer" id="reputazione" name="reputazione" placeholder="2">
                </div>

                <h3>INFORMAZIONI SCONTO:</h3>
                <div class="form-row">
                    <label for="generico">SCONTO GENERICO (%)</label>
                    <input type="integer" id="generico" name="generico" placeholder="2">
                </div>

                <div class="form-row">
                    <label for="bonus">BONUS CREDITI</label>
                    <input type="integer" pattern="[0-9]{0,3}" maxlength="3" id="bonus" name="bonus" placeholder="28">
                </div> -->

                <span class ="bottone"><input type="submit" value="INVIA"></span>
            </form>
        </div>
    </div>

</body>

<?php include('res/PHP/footer.php')?>

</html>