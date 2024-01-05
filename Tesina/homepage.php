<?php
    session_start();

    if (isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro") {
        echo '<link rel="stylesheet" href="res/CSS/external_hmp_dark.css" type="text/css" />';
    } else {
        echo '<link rel="stylesheet" href="res/CSS/external_hmp.css" type="text/css" />';
    }
?>

<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>MangaNett: manga & fumetti</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
</head>

<body>

    <?php
        $pagina_corrente = "homepage";
        include('res/PHP/navbar.php');
        include('res/PHP/funzioni.php');
        include('res/PHP/connection.php');

        //gestione errore
        if(isset($_SESSION['richiesta_ko']) && $_SESSION['richiesta_ko'] == true){
            echo "<h4 id=\"esito_negativo\">SI È VERIFICATO UN ERRORE IMPREVISTO!</h4>";
            unset($_SESSION['richiesta_ko']);
        }

        echo "<p class=\"novita\"><a href=\"catalogo.php\">LE ULTIME NOVITÀ!</a></p>";

        echo "<div class=\"container\">";

            // Percorso del file XML
            $xmlFile = "res/XML/catalogo.xml";

            //Assegno a $fumetti il risultato della funzione getFumetti
            $fumetti = getFumetti($xmlFile);

            //Mi ordino i fumetti in base alla data di uscita, l'idea è quella di stampare solo i manga recenti
            usort($fumetti, function ($a, $b) {
                //creo un formato in modo tale che l'ordinamento segua giorno/mese/anno
                $dataA = DateTime::createFromFormat('Y-m-d', $a['data']);
                $dataB = DateTime::createFromFormat('Y-m-d', $b['data']);
        
                if ($dataA === false || $dataB === false) {
                    //Caso in cui il formato della data non è valido
                    return 0;
                }
                //dal più nuovo al più vecchio
                return $dataB->getTimestamp() - $dataA->getTimestamp()  ;
            });
            
            //stampo i primi 4 e basta per indicare le nuove uscite
            $contatore = 0;

            foreach ($fumetti as $fumetto) {

                // Estrai i dati del fumetto
                $ISBN = $fumetto['isbn'];
                $titolo = $fumetto['titolo'];
                $prezzo = $fumetto['prezzo'];
        
                echo "<div class=\"cell\">";
    
                    //Estensione dell'immagine (dovrebbe essere jpg)
                    $ext = ".jpg";
    
                    //Compongo il nome completo dell'immagine da stampare
                    $nomeImg = $ISBN . $ext;
    
                    //Percorso dell'immagine
                    $pathImg = "res/WEBSITE_MEDIA/PRODUCT_MEDIA/";
    
                    echo "<img src='" . $pathImg . $nomeImg . "' alt=\"Copertina.jpg\" />";

                    echo "<div class=\"prod_info\">";
                        echo "<form action=\"prodotti_info.php\" method=\"post\">";
                            echo "<span class=\"bottone\">";
                                echo "<label><input type=\"submit\" name=\"titolo\" value=\"$titolo\" /></label>";
                            echo "</span>"; 
                        echo "</form>";
                    echo "</div>";

                    echo $prezzo . " CR";
    
                echo "</div>";

                $contatore++;

                if($contatore == 4){
                    break;
                }
            }
        echo "</div>";

        //controllo se c'è almeno un fumetto con un bonus diverso da 0
        $bonusSi = false;

        foreach($fumetti as $fumetto){
            
            if($fumetto['bonus'] > 0){
                $bonusSi = true;
                break;
            }
        }

        if($bonusSi){
            echo "<p class=\"novita\"><a href=\"catalogo.php\">VOGLIA DI BONUS!?</a></p>";

            //apro un secondo container
            echo "<div class=\"container\">";

                $contatore = 0;
                //$fumetto è una variabile temporanea
                foreach ($fumetti as $fumetto) {

                    if($fumetto['bonus'] != 0){
                        // Estrai i dati del fumetto
                        $ISBN = $fumetto['isbn'];
                        $titolo = $fumetto['titolo'];
                        $prezzo = $fumetto['prezzo'];
                
                        echo "<div class=\"cell\">";
            
                            //Estensione dell'immagine (dovrebbe essere jpg)
                            $ext = ".jpg";
            
                            //Compongo il nome completo dell'immagine da stampare
                            $nomeImg = $ISBN . $ext;
            
                            //Percorso dell'immagine
                            $pathImg = "res/WEBSITE_MEDIA/PRODUCT_MEDIA/";
            
                            echo "<img src='" . $pathImg . $nomeImg . "' alt=\"Copertina.jpg\" />";
            
                            echo "<div class=\"prod_info\">";
                                echo "<form action=\"prodotti_info.php\" method=\"post\">";
                                    echo "<span class=\"bottone\"><h5><input type=\"submit\" name=\"titolo\" value=\"$titolo\"></h5></span>";
                                echo "</form>";
                            echo "</div>";
            
                            echo $prezzo . " CR";
            
                        echo "</div>";

                        $contatore++;
                    }
                    if($contatore == 4){
                        break;
                    }
                }

            echo "</div>";
        }
        
    ?>

<!-- <p><a href ="validator.php">validator</a></p> -->
    
</body>

<?php include('res/PHP/footer.php'); ?>

</html>
