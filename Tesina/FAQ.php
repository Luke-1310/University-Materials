<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_FAQ_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_FAQ.css\" type=\"text/css\" />";
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
    <title>FAQ - Frequently Asked Questions</title>
</head>
    
<?php 
    $pagina_corrente = "FAQ";
    include('res/PHP/navbar.php');
    include('res/PHP/funzioni.php');
?>

<body>

<?php

$xmlpath = "res/XML/Q&A.xml";

$domande = getDomande($xmlpath);

$FAQ_exists = false;

$numero_dom = 0;


echo "<p class=\"titolo\">ECCO LE DOMANDE PIÙ RICHIESTE!</p>";

//una volta prese le domande, stampo tutte le domande con l'elemento FAQ a 1 e la risposta meglio valutata
foreach($domande as $domanda){

    if($domanda['FAQ'] == 1){

        $numero_dom++;
        $numero_risp = 0;

        $FAQ_exists = true;

        echo "<div class=\"container\">";

            echo "<div class=\"domanda\">";

                echo"<div class=\"info-domanda\">";
                    echo"<p class=\"text\">DOMANDA</p>";
                    echo"<p class=\"numero\"> #". $numero_dom ."</p>";
                echo"</div>";

                echo"<p class=\"testo-domanda\">" . $domanda['testoDom'] . "</p>";
                
            echo "</div>";

            //ora devo capire quale risposta stampare, quella col migliore punteggio
            foreach($domanda['risposte'] as $risposta){

                $numero_risp++;

                if($risposta['FAQ'] == 1){

                    echo "<div class=\"risposta\">";
                        
                        echo"<div class=\"info-risposta\">";
                            echo"<p class=\"text\">RISPOSTA</p>";
                            echo"<p class=\"numero\"> #". $numero_risp ."</p>";
                        echo"</div>";

                        echo "<p class=\"testo-risposta\">" . $risposta['testoRisp'] . "</p>";
                    echo "</div>";
                }
            }
        
        echo"</div>";
    }
}

if(!$FAQ_exists){

    echo "<div class=\"container\">";
        echo "<div class=\"domanda\">";
            echo"<p class=\"testo-noFAQ\">EHM, COME DIRE, NON SEMBRANO ESSERCI DELLE FAQ AL MOMENTO...</p>";
        echo "</div>";
    echo "</div>";
}

?>

</body>

<?php include('res/PHP/footer.php')?>

</html>