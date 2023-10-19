<?php
    session_start();

    if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_hmp_dark.css\" type=\"text/css\" />";
    }
    else{
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_hmp.css\" type=\"text/css\" />";
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>La casa del libro: recensioni, letture... </title>
    
</head>

<body>
    <h1 class="titolo">LA CASA DEL LIBRO</h1>

   <ul class="menu">
    <li><a href="importante_leggere.php">Perché è importante leggere?</a></li>
    <li><a href="consigliati.php">Recensioni dei libri</a></li>
    <li><a href="lista_libri.php">I libri presenti sul sito</a></li>
    <li><a href="https://www.lafeltrinelli.it/">Noi di solito compriamo i libri qui</a></li>
    <li><a href="https://www.qlibri.it/">Per altre recensioni clicca qui</a></li>

    <?php   
        echo "<form action = \"res/PHP/dark.php\" method='POST'>";
        echo "<button name=\"bottone_c\" type=\"submit\" value= \"chiaro\">";
        echo "<img src = \"res/IMG_GIF/light.png\" alt=\"sun.png\" width=\"80%\"/></img></button>";
        echo "<button name=\"bottone_s\" type=\"submit\" value= \"scuro\">";
        echo "<img src = \"res/IMG_GIF/dark.png\" alt=\"moon.png\" width=\"80%\"/></img></button>";
        echo "</form>";

        if (isset($_SESSION['loggato']) && $_SESSION['loggato'] === 'true') {
            echo "<hr/>". "<strong>" . $_SESSION['nome']. "&#x1F464;" . "</strong>". "<br/>";

            echo "<hr/>" . "<li><a href=\"inserisci_libro.php\">Aggiungi un libro</a></li>";
            echo "<li><a href=\"recensione.php\">Scrivi una recensione</a></li>";
            echo "<li><a href=\"res/PHP/logout.php\">Logout</a></li>";

        } else {
            echo "<li><a href=\"login.php\">Login&#x1F464;</a></li>";
        }
        
    ?>
  </ul>

<div class="main">
    <h2>Benvenuti nella casa del libro!&#x1F4D6;</h2>
    <p>Ciao a tutti, siamo Luca ed Alessandra, se vi piace leggere allora siete nel posto giusto! Condivideremo con voi la nostra passione per la lettura.</p>
    <h2>Che tipo di lettori siamo?</h2>

</div> 

<div class="lista">
    <ul><li><strong>Luca</strong><hr/></li>
        <li>I miei generi preferiti sono:</li>
        <li>Giallo</li>
        <li>Horror</li>
        <li>Storico</li>

        <li>I miei autori preferiti sono:</li>
        <li>Stephen King</li>
        <li>Tatsuya Endo</li>
        <li>Ken Follett</li>
    </ul>
    
    <ul><li><strong>Alessandra</strong><hr/></li>
        <li>I miei generi preferiti sono:</li>
        <li>Romanzo Storico/Filosofico </li>
        <li>Narrativa</li>
        <li>Biografia</li>

        <li>I miei autori preferiti sono:</li>
        <li>Fedor Dostoevskij</li>
        <li>Alessandro Baricco</li>
        <li>Ken Follett</li>
    </ul>
</div>

<div class="main">

    <h2>Cos'è per noi leggere?&#x1F4DA;</h2>
    <p>
        "Interrogo i libri e mi rispondono. Alcuni mi portano il riso sulle labbra o la consolazione nel cuore.
        Altri mi insegnano a conoscere me stesso" -Francesco Petrarca
    </p>

    <p>
        "Dimmi ciò che leggi e ti dirò chi sei' è vero; ma ti conoscerei meglio se mi dicessi quello che rileggi" -François Mauriac
    </p>
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