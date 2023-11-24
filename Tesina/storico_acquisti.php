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
?>

<body>

    <?php
        echo "<p id=\"titolo\">STORICO ACQUISTI</p>";
        
        echo "<div class=\"container\">";

           

            

        echo"</div>";
    ?>
</body>

<?php include('res/PHP/footer.php')?>


</html>