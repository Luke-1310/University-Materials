<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_lista_acquisti_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_lista_acquisti.css\" type=\"text/css\" />";
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
    <title>Lista Acquisti Utenti</title>
</head>
    
<?php 
    $pagina_corrente = "lista_acquisti_utenti";
    include('res/PHP/navbar.php');
    include('res/PHP/connection.php');
    include('res/PHP/funzioni.php');
?>

<body>

    <?php
        $connessione = new mysqli($host, $user, $password, $db);

        echo "<p class=\"titolo\">LISTA ACQUISTI UTENTI</p>";

        $pathImg = "res/WEBSITE_MEDIA/PRODUCT_MEDIA/";
        $ext = ".jpg";

        $xmlPath = "res/XML/storico_acquisti.xml";
        $acquisti = getAcquisti($xmlPath);

        //Funzione di confronto per ordinare in base all'IDUtente in modo crescente
        /*
          Questa funzione di confronto deve restituire:
            Un numero negativo se $a dovrebbe essere ordinato prima di $b.
            Zero se $a e $b sono considerati equivalenti nell'ordinamento.
            Un numero positivo se $a dovrebbe essere ordinato dopo $b.
        */

        function confrontoIDUtente($a, $b) {
            return $a['IDUtente'] - $b['IDUtente'];
        }

        // Ordina l'array $acquisti in base all'IDUtente utilizzando la funzione di confronto
        usort($acquisti, 'confrontoIDUtente');

        foreach($acquisti as $acquisto){

            //mi faccio due query per ricavarmi nomeUtente e reputazione con quell'ID
            //username
            $query_username ="SELECT umn.username FROM utenteMangaNett umn WHERE umn.id = '{$acquisto['IDUtente']}'";
            $ris_username = $connessione->query($query_username);
    
            if(mysqli_num_rows($ris_username) == 1){
                $row = $ris_username->fetch_assoc();
                $username= $row['username'];
            }
            else{
                exit(1);
            }

            $query_repu ="SELECT umn.reputazione FROM utenteMangaNett umn WHERE umn.id = '{$acquisto['IDUtente']}'";
            $ris_repu = $connessione->query($query_repu);
    
            if(mysqli_num_rows($ris_repu) == 1){
                $row = $ris_repu->fetch_assoc();
                $reputazione= $row['reputazione'];
            }
            else{
                exit(1);
            }

            $somma_pagata = 0;

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
                        echo "<h4>PAGATO</h4>";
                    echo"</div>";

                    foreach($acquisto['fumetti'] as $fumetto){

                        $nomeImg = $fumetto['isbn'] . $ext;

                        echo "<div class=\"column\">";
                            echo "<img src='" . $pathImg . $nomeImg . "' alt=\"Copertina.jpg\" onerror=\"this.onerror=null; this.src='res/WEBSITE_MEDIA/PRODUCT_MEDIA/default.jpg';\" />";
                        echo"</div>";
                        
                        echo "<div class=\"column\">";
                            echo $fumetto['titolo'];
                        echo"</div>";

                        echo "<div class=\"column\">";
                            echo $fumetto['quantita'];
                        echo"</div>";

                        $prezzoArrotondato = number_format($fumetto['prezzo'], 2, '.', '');

                        echo "<div class=\"column\">";
                            echo $prezzoArrotondato . " CR";
                        echo"</div>";

                        $somma_pagata += $fumetto['prezzo'];
                    }
                    
                    $parti = explode("T", $acquisto['data']);
        
                    //$parti[0] conterrà la data (parte prima di T) e $parti[1] conterrà l'ora (parte dopo di T)
                    $data = $parti[0];
                    $ora = $parti[1];

                    echo "<div class=\"column\">";
                        echo "<h4>EFFETTUATO DA </h4>" . $username;
                    echo"</div>";
                    
                    echo "<div class=\"column\">";
                        echo "<h4>EFFETTUATO IL</h4>";
                        echo $data;
                    echo"</div>";

                    echo "<div class=\"column\">";
                        echo "<h4>BONUS</h4>";
                        echo $acquisto['bonus'] . " CR";
                    echo"</div>";
                    
                    $somma_pagata = number_format($somma_pagata, 2, '.', '');

                    echo "<div class=\"column\">";
                        echo "<h4>TOTALE PAGATO</h4>";
                        echo $somma_pagata . " CR";
                    echo"</div>";

                    echo "<div class=\"column\">";
                        echo "<h4>LIVELLO DI REPUTAZIONE </h4>" . $reputazione;
                    echo"</div>";

                echo"</div>";

                $isAcquisti = true;
        }
    ?>

</body>

<?php include('res/PHP/footer.php')?>


</html>