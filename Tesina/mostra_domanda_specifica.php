<?php
    session_start();

    if (isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro") {
        echo '<link rel="stylesheet" href="res/CSS/external_domspec_dark.css" type="text/css" />';
    } else {
        echo '<link rel="stylesheet" href="res/CSS/external_domspec.css" type="text/css" />';
    }
?>

<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Domande su un prodotto</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <?php 
        $pagina_corrente = "mostra_domanda_specifica";
        include('res/PHP/connection.php');
        include('res/PHP/navbar.php');
        include('res/PHP/funzioni.php');
    ?>
</head>

<body>
    <?php
        // Percorso del file XML
        $xmlFile = "res/XML/Q&A.xml";
        $xmlFile2 = "res/XML/catalogo.xml";

        $domande = getDomande($xmlFile);
        $fumetti = getFumetti($xmlFile2);

        //importantissimo controllo perché se si cambia tema si perde ovviamente il valore preso tramite il post
        if (isset($_POST['titolo'])) {

            $_SESSION['titolo_dom_sp'] = $_POST["titolo"];
        }

        echo "<p class=\"titoletto\">DOMANDE RELATIVE A ". $_SESSION['titolo_dom_sp'] ."!</p>";

        //controllo errori
        if(isset($_SESSION['segnalazione_ok']) && $_SESSION['segnalazione_ok'] == true){
            echo "<h4 id=\"esito_positivo\">LA SEGNALAZIONE È ANDATA A BUON FINE!</h4>";
            unset($_SESSION['segnalazione_ok']);
        }

        //mi prendo l'isbn
        foreach($fumetti as $fumetto){

            if($fumetto['titolo'] == $_SESSION['titolo_dom_sp']){
                
                $ISBN = $fumetto['isbn'];
            }
        }

        $connessione = new mysqli($host, $user, $password, $db);
        
        $xmlPath = "res/XML/Q&A.xml";
        mostraDomande($ISBN, $xmlPath);
    ?>
</body>

<?php include('res/PHP/footer.php'); ?>

</html>
