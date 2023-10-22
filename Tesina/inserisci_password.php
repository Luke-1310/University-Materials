<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_log_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_log.css\" type=\"text/css\" />";
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
    <title>Non sarai mica un impostore?</title>
</head>
    
<?php 
    $pagina_corrente = "inserisci_password";
    include('res/PHP/navbar.php')
?>

<body>

<div class="container">

<img src="res/WEBSITE_MEDIA/pokemon-detective-pikachu.gif" alt="login_GIF" width="20%">

    <form id="loginForm" action="res/PHP/inserisci_password.php" method="POST">

        <?php
                if(isset($_SESSION['errore_mp']) && $_SESSION['errore_mp'] == 'true'){//isset verifica se errore è settata
                echo "<h4>PASSWORD NON CORRETTA!</h4>";
                unset($_SESSION['errore_mp']);//la unsetto altrimenti rimarrebbe la scritta
            }

            if (isset($_GET['azione'])) {

                $azione = $_GET['azione'];
            } 
            
            //metto il nick utente dentro una variabile di sessione perché se cambio il tema nella pagina delle info mi da errore (giustamente)
            if (!empty($azione)) {
                $_SESSION['azione'] = $azione;
            }
        ?>

        <label for="password">PASSWORD</label>
        <input type="password" name="password" id="password" placeholder="Password123!" required>

        <input type="submit" value="INVIA">

    </form>
</div>

</body>

<?php include('res/PHP/footer.php')?>


</html>