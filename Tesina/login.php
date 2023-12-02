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
    <title>Entra</title>
</head>
    
<?php 
    $pagina_corrente = "login";
    include('res/PHP/navbar.php');
?>

<body>

<div class="container">

<img src="res/WEBSITE_MEDIA/doorbell_login.gif" alt="login_GIF" width="18%">

    <form id="loginForm" action="res/PHP/login.php" method="POST">

        <?php
            if(isset($_SESSION['errore']) && $_SESSION['errore'] == 'true'){//isset verifica se errore è settata
                echo "<h4>USERNAME O PASSWORD ERRATE!</h4>";
                unset($_SESSION['errore']);//la unsetto altrimenti rimarrebbe la scritta
            }

            if(isset($_SESSION['errore_v']) && $_SESSION['errore_v'] == 'true'){//isset verifica se errore è settata
                echo "<h4>ERRORE IN FASE DI LOGIN!</h4>";
                unset($_SESSION['errore']);
            }

            if(isset($_SESSION['registrazione_ok']) && $_SESSION['registrazione_ok'] == 'true'){//isset verifica se errore è settata
                echo "<h3 id=\"esito_positivo\">LA REGISTRAZIONE È ANDATA A BUON FINE!</h3>";
                unset($_SESSION['registrazione_ok']);
            }
        ?>

        <label for="username">USERNAME</label>
        <input type="text" name="username" id="username" placeholder="MarioBros" required>

        <label for="password">PASSWORD</label>
        <input type="password" name="password" id="password" placeholder="Password123!" required>

        <input type="submit" value="ACCEDI">
    
        <div class ="account">
            <a href="register.php">CREA UN ACCOUNT!</a>
        </div>

    </form>
</div>

</body>

<?php include('res/PHP/footer.php')?>


</html>