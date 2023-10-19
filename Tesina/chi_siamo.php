<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_chi_siamo_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_chi_siamo.css\" type=\"text/css\" />";
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
    <title>Chi siamo</title>
</head>
    
<?php 
    $pagina_corrente = "chi_siamo";
    include('res/PHP/navbar.php');
?>

<body>

<?php

echo "<p id=\"titolo\"> CHI SIAMO?</p>";

echo "<div class=\"container\">";
        
    echo"<p>Benvenuti su <strong>MangaNett</strong>, la vostra destinazione online per tutto ciò che riguarda il magico mondo dei manga e anime giapponesi. 
        In MangaNett, ci impegniamo a fornire ai nostri appassionati clienti la migliore selezione di fumetti con un servizio straordinario.</p>";

    echo "<p>La nostra passione per il manga e l'animazione giapponese ci guida costantemente nella ricerca delle opere più interessanti, dei titoli classici e delle ultime uscite. 
    Il nostro team è composto da appassionati ed esperti nel settore, pronti ad offrire raccomandazioni personalizzate e a condividere le loro conoscenze con voi.</p>";

    echo "<p><strong>Crediamo che il mondo dei manga sia un universo vasto e affascinante in cui c'è qualcosa per tutti!</strong> Che siate fan di avventure epiche, storie romantiche, o comicità sfrenata, 
    troverete una vasta gamma di titoli per soddisfare i vostri gusti. 
    Inoltre, MangaNett è sempre aggiornato con le ultime uscite e le serie più popolari.</p>";

    echo "<p>Presso MangaNett, non offriamo solo una vasta selezione di prodotti di alta qualità, ma <strong>ci impegniamo anche a fornire un servizio clienti eccellente</strong>. La vostra soddisfazione è la nostra priorità, 
    e siamo qui per rispondere alle vostre domande, aiutarvi a trovare il manga perfetto o assistervi con qualsiasi richiesta.</p>";

echo "</div>"

?>

</body>

<?php include('res/PHP/footer.php')?>


</html>