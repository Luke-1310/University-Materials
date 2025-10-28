<?php
    session_start();

    if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_rec_dark.css\" type=\"text/css\" />";
    }
    else{
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_rec.css\" type=\"text/css\" />";
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>La casa del libro: recensioni, letture... </title>
    
</head>

<body>
    <h1 class="titolo">RECENSIONI! </h1>

<?php
    if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
        echo "<div class=\"home\">";
        echo "<a href = \"homepage.php\"><img src = \"res/IMG_GIF/home2.png\" alt=\"home.png\" width=\"10%\"/></a>";
        echo "</div>";
    }
    else{
        echo "<div class=\"home\">";
        echo "<a href = \"homepage.php\"><img src = \"res/IMG_GIF/home.png\" alt=\"home.png\" width=\"10%\"/></a>";
        echo "</div>";
    }
?> 

<div class="container">
    
    <form action = "res/PHP/recensione.php" method="POST">

    <?php
            if(isset($_SESSION['errore_uu']) && $_SESSION['errore_uu'] == 'true'){//isset verifica se errore è settata
                echo "<h3>NOME DEL UTENTE NON TROVATO!</h3>";
                unset($_SESSION['errore_uu']);//la unsetto altrimenti rimarrebbe la scritta
            }

            if(isset($_SESSION['errore_tt']) && $_SESSION['errore_tt'] == 'true'){//isset verifica se errore è settata
                echo "<h3>TITOLO DEL LIBRO NON TROVATO!</h3>";
                unset($_SESSION['errore_tt']);//la unsetto altrimenti rimarrebbe la scritta
            }
    ?>

        <label for="titolo">Inserisci il titolo che vuoi recensire</label>
        <input type="text" name="titolo" id="titolo" required>
    
        <label for="testo">Inserisci recensione</label>
        <textarea name="testo" id="testo" required></textarea>

        <label for="voto">Inserisci il voto</label>
        <input type="number" step="0.5" min="1" max="5" name="voto" id="voto" required>

        <span class ="bottone"><input type="submit" value="Invia">
        </span>

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