<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_lis_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_lis.css\" type=\"text/css\" />";
   }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>La casa del libro: recensioni, letture... </title>
</head>

<body>
    <h1 class="titolo">CATALOGO LIBRI</h1>

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

<?php
    
    //Percorso del file XML
    $xmlFile = "res/XML/libri.xml";

    //Carico il file XML prendendo come parametro il percorso del file XML voluto
    $xml = simplexml_load_file($xmlFile);

    //Ciclo per ciascun elemento book
    foreach ($xml->book as $book) {
        
        echo"<div class = row>";
            
            echo"<div class = item>";
            
                //Mi prendo il titolo del libro corrente 
                $titolo = (string)$book->titolo;

                //Ricavo l'ISBN del libro, il quale corrisponde al nome dell'immagine del libro
                $ISBN = (string)$book->attributes()->isbn;

                //Estensione dell'immagine la quale dovrebbe essere jpg
                $ext = ".jpg";

                //Compongo il nome completo dell'immagine da stampare
                $nomeImg = $ISBN . $ext;

                //Percorso dell'immagine
                $pathImg = "res/IMG_USER/";

                //Stampa del libro: titolo + immagine
                echo "<form action=\"libri_info.php\" method=\"POST\">";
                echo "<span class=\"bottone\"><input type=\"submit\" name=\"titolo\" value=\"$titolo\"></span>";
                echo "</form>";

                echo "<img src='" . $pathImg . $nomeImg . "' alt='Copertina.jpg'>";

            echo"</div>";

        echo"</div>";
    }
?>

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