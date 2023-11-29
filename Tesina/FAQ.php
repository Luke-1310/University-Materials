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
    include('res/PHP/connection.php');
?>

<body>

    <?php

        $xmlpath = "res/XML/Q&A.xml";

        $domande = getDomande($xmlpath);

        $FAQ_exists = false;

        $numero_dom = 0;

        $connessione = new mysqli($host, $user, $password, $db);

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

                    if(isset($_SESSION['loggato']) && $_SESSION['loggato'] = true){

                        //se l'utente è un GS/AM/SA allora ha delle funzionalità in più
                        $sql = "SELECT u.ruolo FROM utentemanganett u WHERE u.username = '{$_SESSION['nome']}' AND (u.ruolo = 'GS' OR u.ruolo = 'AM' OR u.ruolo = 'SA')";
                        $ris = mysqli_query($connessione, $sql);

                        if(mysqli_num_rows($ris) == 1){

                            echo"<form id=\"rimuoviForm\" action = \"res/PHP/rimuovi_FAQ.php\" method=\"POST\" >";

                                echo"<input type=\"hidden\" name=\"dataDom\" value=". $domanda['dataDom'] . ">";
                                echo"<input type=\"hidden\" name=\"IDDom\" value=". $domanda['IDDom'] . ">";

                                echo "<span class =\"bottone\"><input type=\"submit\" value=\"RIMUOVI FAQ\"></span>";
                        
                            echo "</form>";   
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