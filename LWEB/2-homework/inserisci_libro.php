<?php
    session_start();

    if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_lib_dark.css\" type=\"text/css\" />";
    }
    else{
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_lib.css\" type=\"text/css\" />";
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>La casa del libro: recensioni, letture... </title>
</head>

<body>
    <h1 class="titolo">INSERISCI LIBRO</h1>

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

<?php
        if(isset($_SESSION['errore_i']) && $_SESSION['errore_i'] == 'true'){//isset verifica se errore è settata
                echo "<h3>ISBN GIA' INSERITO!</h3>";
            unset($_SESSION['errore_i']);//la unsetto altrimenti rimarrebbe la scritta
        }

        if(isset($_SESSION['errore_t']) && $_SESSION['errore_t'] == 'true'){//isset verifica se errore è settata
                echo "<h3>TITOLO GIA' INSERITO!</h3>";
            unset($_SESSION['errore_t']);//la unsetto altrimenti rimarrebbe la scritta
        }
    ?>

<div class="container">

    <form action = "res/PHP/inserisci_libro.php" method="POST" enctype="multipart/form-data">

        <label for="titolo">Titolo</label>
        <input type="text" name="titolo" id="titolo" placeholder="I Promessi Sposi" required>

        <label for="ISBN">ISBN 13 Cifre</label>
        <input type="text" pattern="[0-9]{13}" maxlength="13" name="ISBN" id="ISBN" placeholder="9798431410840" required>

        <label for="lunghezza">Numero Pagine</label>
        <input type="text" pattern="[0-9]{1,4}" maxlength="4" name="lunghezza" id="lunghezza" placeholder="276" required>
    
        <label for="data">Data di uscita</label>
        <input type="date" name="data" id="data" required>
        
        <label for="autore">Autore</label>
        <input type="text" name="autore" id="autore" placeholder="Alessandro Manzoni" required>

        <div class = "info-img">
            <label for="img">Inserisci la copertina</label>
            <div class="information-icon">
                <span class="icon">i</span>
                <span class="tooltip">L'immagine da inserire deve essere un .jpg</span>
            </div>
        </div>
        <input type="file" name="img" id="img">

        <span class ="bottone"><input type="submit" value="Invia"></span>
        
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