<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_storico_acquisti_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_storico_acquisti.css\" type=\"text/css\" />";
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
    <title>Storico Acquisti</title>
</head>
    
<?php 
    $pagina_corrente = "storico_acquisti";
    include('res/PHP/navbar.php');
    require('res/PHP/funzioni.php');
    require('res/PHP/connection.php');

?>

<body>

    <?php

        if(isset($_SESSION['ordine completato']) && $_SESSION['ordine completato'] == true){
            echo "<h4 id=\"esito_positivo\">L'ORDINE È STATO COMPLETATO!</h4>";
            unset($_SESSION['ordine completato']);
        }

        $xmlpath = "res/XML/storico_acquisti.xml";
        $acquisti = getAcquisti($xmlpath);

        // Funzione di confronto per ordinare gli acquisti in base alla data dal più recente al più vecchio
        function confrontoData($acquistoA, $acquistoB) {
            return strtotime($acquistoB['data']) - strtotime($acquistoA['data']);
        }

        // Ordinamento degli acquisti utilizzando la funzione di confronto
        usort($acquisti, 'confrontoData');

        $connessione = new mysqli($host, $user, $password, $db);

        $isAcquisti = false;

        $pathImg = "res/WEBSITE_MEDIA/PRODUCT_MEDIA/";
        $ext = ".jpg";

        echo "<p id=\"titolo\">STORICO ACQUISTI</p>";

        //mi prendo l'id dell'utente corrente
        $query ="SELECT umn.id FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";
        $ris = $connessione->query($query);

        if(mysqli_num_rows($ris) == 1){
            $row = $ris->fetch_assoc();
            $id = $row['id'];
        }
        else{
            header('Location:../../homepage.php');
        }

        foreach($acquisti as $acquisto){

            if($acquisto['IDUtente'] == $id){

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
                        echo "<h4>EFFETTUATO IL</h4>";
                        echo $data . " ALLE " . $ora;
                    echo"</div>";
                    
                    echo "<div class=\"column\">";
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

                echo"</div>";

                $isAcquisti = true;
            }
        }
   
        if(!$isAcquisti){
            echo "<p id=\"no_response\">NESSUN ACQUISTO TROVATO... ¯\_(ツ)_/¯</p>";
        }

    ?>

</body>

<?php include('res/PHP/footer.php')?>


</html>