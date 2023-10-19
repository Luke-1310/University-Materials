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
    <h1 class="titolo">PAGINA DI REGISTRAZIONE</h1>

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

<div class="container_reg">

    <form action = "res/PHP/register.php" method="POST">

        <?php
            if(isset($_SESSION['errore']) && $_SESSION['errore'] == 'true'){//isset verifica se errore è settata
                echo "<h3>USERNAME GIÀ INSERITO!</h3>";
                unset($_SESSION['errore']);//la unsetto altrimenti rimarrebbe la scritta
            }

            if(isset($_SESSION['errore_e']) && $_SESSION['errore_e'] == 'true'){//isset verifica se errore è settata
                echo "<h3>EMAIL GIÀ ESISTENTE!</h3>";
                unset($_SESSION['errore_e']);//la unsetto altrimenti rimarrebbe la scritta
            }
        
            if(isset($_SESSION['errore_p']) && $_SESSION['errore_p'] == 'true'){
                echo "<h3>LE PASSWORD NON SONO UGUALI!</h3>";
                unset($_SESSION['errore_p']);
            }
        ?>

        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
    
        <label for="password">Conferma password</label>
        <input type="password" name="password2" id="password2" required>

        <span class ="bottone"><input type="submit" value="Invia">
        </span>

        <p>Sei già registrato? Fai il <a href="login.php">login</p></a>
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