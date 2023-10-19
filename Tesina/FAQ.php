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

$numero = 0;

echo "<p class=\"titolo\">ECCO LE DOMANDE PIÙ RICHIESTE!</p>";

//una volta prese le domande, stampo tutte le domande con l'elemento FAQ a 1 e la risposta meglio valutata
foreach($domande as $domanda){

    if($domanda['FAQ'] == 1){
        
        echo "<div class=\"container\">";
            $FAQ_exists = true;
            
            $numero++;

            $contatore = 0; //mi serve per stampare la i-esima risposta, ovvero quella col punteggio migliore
            $contatore_migliore = 0; //in questa variabile mi metto il numero della risposta migliore

            $punteggio_migliore = 0;

            //ora devo capire quale risposta stampare, quella col migliore punteggio
            foreach($domanda['risposte'] as $risposta){
    
                $punteggio_corrente = 0; //qui abbiamo quindi il punteggio che prende una certa risposta
                $contatore++; //trattiamo la risposta i-esima
                
                foreach($risposta['votazioni'] as $votazione){
                    
                    $punteggio_corrente = $punteggio_corrente + ($votazione['supporto'] + $votazione['utilita']) * $votazione['reputazione'];
                }

                //vediamo se la risposta corrente è la migliore, se una domanda 
                if($punteggio_corrente > $punteggio_migliore){
                    $punteggio_migliore = $punteggio_corrente;
                    $contatore_migliore = $contatore;
                }
            }

            echo "<div class=\"domanda\">";

                echo"<div class=\"info-domanda\">";
                    echo"<p class=\"text\">DOMANDA</p>";
                    echo"<p class=\"numero\"> #". $numero ."</p>";
                echo"</div>";

                echo"<p class=\"testo-domanda\">" . $domanda['testoDom'] . "</p>";
                
            echo "</div>";

            $contatore_stampa = 0;

            foreach($domanda['risposte'] as $risposta_II){
                
                $contatore_stampa++;

                if($contatore_stampa == $contatore_migliore){

                    echo "<div class=\"risposta\">";
                        
                    echo"<div class=\"info-risposta\">";
                        echo"<p class=\"text\">RISPOSTA</p>";
                        echo"<p class=\"numero\"> #". $numero ."</p>";
                    echo"</div>";

                    echo "<p class=\"testo-risposta\">" . $risposta_II['testoRisp'] . "</p>";
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