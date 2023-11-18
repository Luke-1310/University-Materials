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
    $pagina_corrente = "modifica_prodotto";
    include('res/PHP/navbar.php');
    include('res/PHP/funzioni.php');
?>

<body>
    <div class="container_external">
        <div class="container_reg">
            <form id="prodottoForm" action = "res/PHP/modifica_prodotto.php" method="POST" enctype="multipart/form-data">

            <!-- errori del form -->
                <?php
                    if(isset($_SESSION['errore_typeFile']) && $_SESSION['errore_typeFile'] == 'true'){//isset verifica se errore è settata
                        echo "<h4>IL FORMATO DEL FILE NON È CORRETTO, DEVE ESSERE .jpg!</h4>";
                        unset($_SESSION['errore_typeFile']);//la unsetto altrimenti rimarrebbe la scritta
                    }

                    if(isset($_SESSION['errore_upload']) && $_SESSION['errore_upload'] == 'true'){//isset verifica se errore è settata
                        echo "<h4>ERRORE DURANTE IL CARICAMENTO DELLA IMMAGINE!</h4>";
                        unset($_SESSION['errore_upload']);
                    }

                    if(isset($_SESSION['errore_ag_titolo']) && $_SESSION['errore_ag_titolo'] == 'true'){//isset verifica se errore è settata
                        echo "<h4>IL TITOLO GIÀ ESISTE!</h4>";
                        unset($_SESSION['errore_ag_titolo']);//la unsetto altrimenti rimarrebbe la scritta
                    }

                    if(isset($_SESSION['errore_ag_isbn']) && $_SESSION['errore_ag_isbn'] == 'true'){//isset verifica se errore è settata
                        echo "<h4>L'ISBN GIÀ ESISTE!</h4>";
                        unset($_SESSION['errore_ag_isbn']);//la unsetto altrimenti rimarrebbe la scritta
                    }

                    // Percorso del file XML
                    $xmlFile = "res/XML/catalogo.xml";

                    //ora mi prendo i dati del fumetto che voglio modificare per farsì che riempano il form
                    $fumetti = getFumetti($xmlFile);

                    //itero attraverso gli elementi 'fumetto'
                    foreach ($fumetti as $fumetto) {
                    
                        if($fumetto['titolo'] == $_SESSION['info_titolo']){
                            $fumetto_corrente = $fumetto;

                        }
                    
                    }
                ?>
                
                <h3>INFORMAZIONI PRODOTTO:</h3>
                <div class="form-row">
                    <label for="titolo">TITOLO</label>
                    <input type="text" id="titolo" name="titolo" value="<?php echo $fumetto_corrente['titolo']; ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="ISBN">ISBN</label>
                    <input type="text" pattern="[0-9]{13}" maxlength="13" id="ISBN" name="ISBN" value="<?php echo $fumetto_corrente['isbn']; ?>" required>
                </div>

                <div class="form-row">
                    <label for="editrice">CASA EDITRICE</label>
                    <input type="text" id="editrice" name="editrice" value="<?php echo $fumetto_corrente['editore']; ?>" required>
                </div>

                <div class="form-row">
                    <label for="nome">NOME AUTORE</label>
                    <input type="text" id="nome" name="nome" value="<?php echo $fumetto_corrente['nome_autore']; ?>" required>
                </div>

                <div class="form-row">
                    <label for="cognome">COGNOME AUTORE</label>
                    <input type="text" id="cognome" name="cognome" value="<?php echo $fumetto_corrente['cognome_autore']; ?>" required>
                </div>
                    
                <div class="form-row">
                    <label for="sinossi">SINOSSI</label>
                    <textarea id="sinossi" name="sinossi" rows="10" cols="60" required><?php echo $fumetto_corrente['sinossi']; ?></textarea>
                </div>

                <div class="form-row">
                    <label for="lunghezza">NR DI PAGINE</label>
                    <input type="number" pattern="[0-9]{1,4}" maxlength="4" id="lunghezza" name="lunghezza" value="<?php echo $fumetto_corrente['lunghezza']; ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="prezzo">PREZZO</label>
                    <input type="text" step="0.01" min="0" name="prezzo" value="<?php echo $fumetto_corrente['prezzo']; ?>" id="prezzo" required>
                </div>


                <div class="form-row">
                    <label for="quantita">QUANTITÀ</label>
                    <input type="integer" pattern="[0-9]{1,3}" maxlength="3" id="quantita" name="quantita" value="<?php echo $fumetto_corrente['quantita']; ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="data">DATA DI USCITA</label>
                    <input type="text" id="data" name="data" placeholder="1999-01-01" required pattern="\d{4}-\d{2}-\d{2}" value="<?php echo $fumetto_corrente['data']; ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="img">COPERTINA </label>
                    <input type="file" name="img" id="img">
                </div>

                <span class ="bottone"><input type="submit" value="INVIA"></span>
            </form>
        </div>
    </div>
</body>
    
<?php include('res/PHP/footer.php')?>

</html>