<?php
    session_start();

    if (isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro") {
        echo '<link rel="stylesheet" href="res/CSS/external_mostradom_dark.css" type="text/css" />';
    } else {
        echo '<link rel="stylesheet" href="res/CSS/external_mostradom.css" type="text/css" />';
    }
?>

<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Domande su un prodotto</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <?php 
        $pagina_corrente = "mostra_domande_prodotto";
        include('res/PHP/navbar.php');
        include('res/PHP/funzioni.php');
    ?>
</head>

<body>
    
    <?php

        if(isset($_SESSION['richiesta_ok']) && $_SESSION['richiesta_ok'] = true){
            echo "<h4 id=\"esito_positivo\">L'OPERAZIONE È ANDATA A BUON FINE!</h4>";
            unset($_SESSION['richiesta_ok']);
        }

        if(isset($_SESSION['segnalazione_ok']) && $_SESSION['segnalazione_ok'] == true){
            echo "<h4 id=\"esito_positivo\">LA SEGNALAZIONE È ANDATA A BUON FINE!</h4>";
            unset($_SESSION['segnalazione_ok']);
        }

        // Percorso del file XML
        $xmlFile = "res/XML/Q&A.xml";
        $xmlFile2 = "res/XML/catalogo.xml";

        $domande = getDomande($xmlFile);
        $fumetti = getFumetti($xmlFile2);

        //in questo modo stampo tutti i fumetti che hanno almeno una domanda VISIBILE, qualora ci fossero
        
        if(empty($domande)){

            echo "<p class=\"titoletto_noFumetti\">SEMBRA CHE NON CI SIANO DOMANDE!</p>";
        }
        else{

            echo "<p class=\"titoletto\">PRODOTTI CHE HANNO GIÀ DELLE DOMANDE!</p>";
            
            foreach($fumetti as $fumetto){

                $contatore = 0;
    
                foreach($domande as $domanda){
    
                    if($contatore == 0 && $domanda['ISBNProdotto'] == $fumetto['isbn'] && $domanda['segnalazione'] != 0){
    
                        echo "<div class='titolo-fumetto'>"; 
                            echo "<form id=\"prod_info\" action=\"mostra_domanda_specifica.php\" method=\"POST\">";
                            echo "<span class=\"bottone\"><h5><input type=\"submit\" name=\"titolo\" value= '" .$fumetto['titolo'] . "'></h5></span>";
                            echo "</form>";
                        echo "</div>";
                        $contatore++;
                    }
                }
            }
        }
    ?>
</body>

<?php include('res/PHP/footer.php'); ?>

</html>
