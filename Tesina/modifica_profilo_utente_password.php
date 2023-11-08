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
    <title>Modifica password utente</title>
</head>
    
<?php 
    $pagina_corrente = "modifica_profilo_utente_password";
    include('res/PHP/navbar.php');
?>

<body>

<div class="container">

    <img src="res/WEBSITE_MEDIA/pokemon-detective-pikachu.gif" alt="login_GIF" width="13.5%">

    <form id="loginForm" action="res/PHP/modifica_profilo_utente_password.php" method="POST">

        <?php
            if(isset($_SESSION['errore_p']) && $_SESSION['errore_p'] == 'true'){
                echo "<h4>LE PASSWORD NON SONO UGUALI!</h4>";
                unset($_SESSION['errore_p']);
            }

            if(isset($_SESSION['errore_preg']) && $_SESSION['errore_preg'] == 'true'){
                echo "<h4>LA PASSWORD NON RISPETTA I CRITERI DI SICUREZZA!</h4>";
                unset($_SESSION['errore_preg']);
            }
        ?>
        <div class="titoletto">

        <div class="tooltip">
            <span class="tooltiptext">LA PASSWORD DEVE SODDISFARE I SEGUENTI REQUISITI:
                <ul>
                    <li>DEVE ESSERE LUNGA ALMENO 8 CARATTERI.</li>
                    <li>DEVE CONTENERE ALMENO UNA LETTERA MAIUSCOLA E UNA MINUSCOLA</li>
                    <li>DEVE CONTENERE ALMENO UN NUMERO.</li>
                    <li>DEVE CONTENERE ALMENO UN SIMBOLO SPECIALE.</li>
                </ul>       
            </span>
            <i id="info" class="material-icons">info</i>
        </div>

        <h3>CREDENZIALI DI ACCESSO:</h3>

        </div>

        <label for="password">PASSWORD</label>
        <input type="password" name="password" id="password" placeholder="Password123!" required>
        
        <label for="password">CONFERMA PASSWORD</label>
        <input type="password" name="password2" id="password2" placeholder="Password123!" required>
        
        <input type="submit" value="INVIA">

    </form>
</div>

</body>

<?php include('res/PHP/footer.php')?>


</html>