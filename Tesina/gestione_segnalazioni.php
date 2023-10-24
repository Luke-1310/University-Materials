<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_storico_cr_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_storico_cr.css\" type=\"text/css\" />";
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
    <title>Gestione Segnalazioni</title>
</head>
    
<?php 
    $pagina_corrente = "gestione_segnalazioni";
    include('res/PHP/navbar.php');
    include('res/PHP/funzioni.php');
    require('res/PHP/connection.php');
?>
<body>
   
<?php   

$xmlpath = "res/XML/Q&A.xml";
$domande = getDomande($xmlpath);

$iSSegnalazioni = false;

foreach($domande as $domanda){  

    if($domanda['segnalazione'] == 1){
        $iSSegnalazioni = true;
    }

    foreach($domanda['risposte'] as $risposta){

        if($risposta['segnalazione'] == 1){
            $iSSegnalazioni = true;
        }
    }
}

if($iSSegnalazioni){

    echo"<div class=\"container-storico\">";

        foreach($domande as $domanda){  

            if($domanda['segnalazione'] == 1){
                echo "<div class=\"column\">";
                    echo $domanda['testoDom'];
                echo"</div>"; 
            }
        
            foreach($domanda['risposte'] as $risposta){
        
                if($risposta['segnalazione'] == 1){
                    echo "<div class=\"column\">";
                        echo $risposta['testoRisp'];
                    echo"</div>"; 
                }
            }
        }
    echo "</div>";
}

else{
    echo "<p id=\"no_response\">NESSUNA SEGNALAZIONE TROVATA... ¯\_(ツ)_/¯</p>";
}

?>

</body>

<?php include('res/PHP/footer.php')?>


</html>