<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_carrello_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_carrello.css\" type=\"text/css\" />";
   }
?>
<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- titolo della scheda -->
<head>
    <title>Carrello</title>
</head>
    
<?php 
    $pagina_corrente = "carrello";
    include('res/PHP/navbar.php');
    include('res/PHP/connection.php');
    include('res/PHP/funzioni.php');
?>

<body>

    <?php

        $xmlPath = "res/XML/catalogo.xml";
        $fumetti = getFumetti($xmlPath);

        $pathImg = "res/WEBSITE_MEDIA/PRODUCT_MEDIA/";
        $ext = ".jpg";

        $prezzoFinale = 0;

        if(isset($_SESSION['rimuovi_prodotto_carrello_ok']) && $_SESSION['rimuovi_prodotto_carrello_ok'] == true){
            echo "<h4 id=\"esito_positivo\">IL PRODOTTO È STATO RIMOSSO DAL CARRELLO!</h4>";
            unset($_SESSION['rimuovi_prodotto_carrello_ok']);
        }

        //unset($_SESSION['carrello']);
        if(isset($_SESSION['carrello']) && !empty($_SESSION['carrello'])){

            echo "<div class=\"container\">";

                echo "<div class=\"column\">";
                    echo "<h4>PRODOTTO</h4>";
                echo"</div>";

                echo "<div class=\"column\">";
                    echo "<h4>TITOLO</h4>";
                echo"</div>";

                echo "<div class=\"column\">";
                    echo "<h4>QUANTITÀ</h4>";
                echo"</div>";

                echo "<div class=\"column\">";
                    echo "<h4>BONUS</h4>";
                echo"</div>";

                echo "<div class=\"column\">";
                    echo "<h4>PREZZO</h4>";
                echo"</div>";

                echo "<div class=\"column\">";
                    echo "<h4>PREZZO SCONTATO</h4>";
                echo"</div>";

                foreach($_SESSION['carrello'] as $fumetto_carrello) {
                    
                    foreach($fumetti as $fumetto){

                        if($fumetto['isbn'] == $fumetto_carrello['isbn']){

                            $nomeImg = $fumetto_carrello['isbn'] . $ext;

                            echo "<div class=\"column\">";
                                echo "<img src='" . $pathImg . $nomeImg . "' alt=\"Copertina.jpg\">";
                            echo"</div>";

                            echo "<div class=\"column\">";

                                echo $fumetto['titolo'];

                                echo "<div class=\"bottone_rimuovi\">";
                                
                                    echo "<form id=\"form-delete\" action=\"res/PHP/rimuovi_prodotto_carrello.php\" method=\"POST\">";
                                        echo "<input type=\"hidden\" name=\"isbn\" value=\"" . $fumetto_carrello['isbn'] . "\">";
                                        echo "<button name=\"bottone_elimina\" type=\"submit\">";
                                        echo "<i id=\"decrementa\" class=\"material-icons\">delete</i></button>";
                                    echo "</form>";

                                echo"</div>";

                            echo"</div>";

                            echo "<div class=\"column\">";

                                echo "<div class=\"bottoni_quantita\">";
                                
                                    echo "<form action=\"res/PHP/aggiorna_quantita_prodotto.php\" method=\"POST\">";
                                        echo "<input type=\"hidden\" name=\"isbn\" value=\"" . $fumetto_carrello['isbn'] . "\">";
                                        echo "<button name=\"bottone_aumenta\" type=\"submit\">";
                                        echo "<i id=\"aumenta\" class=\"material-icons\">add</i></button>";
                                    echo "</form>";
                                    
                                    echo $fumetto_carrello['quantita'];
                                
                                    echo "<form action=\"res/PHP/aggiorna_quantita_prodotto.php\" method=\"POST\">";
                                        echo "<input type=\"hidden\" name=\"isbn\" value=\"" . $fumetto_carrello['isbn'] . "\">";
                                        echo "<button name=\"bottone_decrementa\" type=\"submit\">";
                                        echo "<i id=\"decrementa\" class=\"material-icons\">remove</i></button>";
                                    echo "</form>";

                                echo "</div>";

                            echo "</div>";

                            echo "<div class=\"column\">";
                                echo $fumetto_carrello['bonus'] . " CR";
                            echo"</div>";

                            echo "<div class=\"column\">";
                                echo $fumetto_carrello['prezzo'] ." CR";
                            echo"</div>";

                            $xmlPathFumetti = "res/XML/catalogo.xml";
                            $prezzoScontato = calcolaScontoFumetto($xmlPathFumetti, $fumetto_carrello['isbn'], $fumetto_carrello['prezzo']);
                            
                            $fumetto_carrello['prezzo_scontato'] = $prezzoScontato;
                            $prezzoScontato = $prezzoScontato * $fumetto_carrello['quantita'];
                            $prezzoFinale = $prezzoFinale + $prezzoScontato;

                            echo "<div class=\"column\">";
                                echo $fumetto_carrello['prezzo_scontato'] . " CR";
                            echo"</div>";

                            
                        }
                    }
                }

                echo "<div class=\"column\"></div>";
                echo "<div class=\"column\"></div>";
                echo "<div class=\"column\"></div>";
                
                echo "<div class=\"column\">";
                    echo "<h4>CREDITI TOTALI</h4>";
                echo"</div>";

                echo "<div class=\"column\">";
                    echo "<h4>PREZZO TOTALE</h4>";
                echo"</div>";

                echo "<div class=\"column\">";
                    echo "<h4>PREZZO FINALE</h4>";
                echo"</div>";

                echo "<div class=\"column\"></div>";
                echo "<div class=\"column\"></div>";
                echo "<div class=\"column\"></div>";
                
                echo "<div class=\"column\">";
                    $sommaTotaleBonus = calcolaBonusAcquisto();
                    echo $sommaTotaleBonus ." CR";
                echo"</div>";

                echo "<div class=\"column\">";
                    $prezzoTotaleNoSconto = calcolaSpesaNoSconto();
                    echo $prezzoTotaleNoSconto ." CR";
                echo"</div>";

                echo "<div class=\"column\">";
                    echo $prezzoFinale ." CR";
                echo"</div>";

                echo "<div class=\"column\"></div>";
                echo "<div class=\"column\"></div>";
                echo "<div class=\"column\"></div>";
                echo "<div class=\"column\"></div>";
                echo "<div class=\"column\"></div>";

                echo "<div class=\"column\">";

                    echo "<form action=\"res/PHP/conferma_ordine.php\" method=\"POST\">";
                        echo "<input type=\"hidden\" name=\"daPagare\" value='" . $prezzoFinale . "'>";
                        echo "<input type=\"hidden\" name=\"totaleBonus\" value='" . $sommaTotaleBonus . "'>";
                        echo "<span class=\"carrello\"><h5><input type=\"submit\" name=\"Disponibile\" value=\"CONFERMA ORDINE\"></h5></span>";
                    echo "</form>";

                echo"</div>";
                

            echo "</div>";
        }

        else{
            echo "<p id=\"no_response\">NESSUN PRODOTTO AGGIUNTO... ¯\_(ツ)_/¯</p>";
        }
    ?>

</body>

<?php include('res/PHP/footer.php')?>


</html>