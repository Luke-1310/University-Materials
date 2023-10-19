<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_log_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_log.css\" type=\"text/css\" />";
   }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>La casa del libro: recensioni, letture... </title>
</head>

<body>
    <h1 class="titolo">PAGINA DI LOGIN</h1>

<?php
    if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
        echo "<div class=\"rotating-figure\">";
        echo "<img src = \"res/IMG_GIF/locked_book_dark.png\" alt=\"locked_book_dark.png\"/></img>";
        echo "</div>";

        echo "<div class=\"home\">";
        echo "<a href = \"homepage.php\"><img src = \"res/IMG_GIF/home2.png\" alt=\"home.png\" width=\"10%\"/></a>";
        echo "</div>";
    }
    else{
        echo "<div class=\"rotating-figure\">";
        echo "<img src = \"res/IMG_GIF/locked_book.png\" alt=\"locked_book.png\"/></img>";
        echo "</div>";

        echo "<div class=\"home\">";
        echo "<a href = \"homepage.php\"><img src = \"res/IMG_GIF/home.png\" alt=\"home.png\" width=\"10%\"/></a>";
        echo "</div>";
    }
?> 

<div class="container">

<form action = "res/PHP/login.php" method="POST">
    
    <?php
        if(isset($_SESSION['errore']) && $_SESSION['errore'] == 'true'){//isset verifica se errore è settata
            echo "<h3>USERNAME O PASSWORD ERRATE!</h3>";
            unset($_SESSION['errore']);//la unsetto altrimenti rimarrebbe la scritta
        }

        if(isset($_SESSION['errore_v']) && $_SESSION['errore_v'] == 'true'){//isset verifica se errore è settata
            echo "<h3>ERRORE IN FASE DI LOGIN!</h3>";
            unset($_SESSION['errore']);
        }
    ?>

    <label for="username">Username</label>
    <input type="text" name="username" id="username" required>

    <label for="password">Password</label>
    <input type="password" name="password" id="password" required>

    <span class ="bottone"><input type="submit" value="Invia"></span>

    <p>Non sei registrato? Fai la <a href="register.php">registrazione</p></a>
</form>
</div>

<hr/>
<div class="crediti">
    <p>Responsabili del sito: 
    <a href="mailto:privitera.1938225@studenti.uniroma1.it">privitera.1938225@studenti.uniroma1.it</a>    
    <a href="mailto:coluzzi.1912970@studenti.uniroma1.it">coluzzi.1912970@studenti.uniroma1.it</a>    
    </p>
</div>

</body>
</html>