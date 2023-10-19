<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_info_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_info.css\" type=\"text/css\" />";
   }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>La casa del libro: recensioni, letture... </title>
</head>

<body>
    <h1 class="titolo">INFORMAZIONI LIBRO</h1>

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

<div class="grid">

<?php
    
    //Percorso del file XML
    $xmlFile = "res/XML/libri.xml";

    //Carico il file XML prendendo come parametro il percorso del file XML voluto
    $xml = simplexml_load_file($xmlFile);
            
    $title = $_POST['titolo'];

    //Percorso del file XML
    $xmlFile = "res/XML/libri.xml";

    //Carico il file XML prendendo come parametro il percorso del file XML voluto
    $xml = simplexml_load_file($xmlFile);

    foreach($xml->book as $book){

        if($book->titolo == $title){
            
            //Mi prendo tutte le informazionid del libro  
            $titolo = (string)$book->titolo;
            $autoreNome = (string)$book->autore->nome;
            $autoreCognome = (string)$book->autore->cognome;
            $lunghezza = (string)$book->lunghezza;
            $data = (string)$book->data;

            $ISBN = (string)$book->attributes()->isbn;
            
            //Estensione dell'immagine la quale dovrebbe essere jpg
            $ext = ".jpg";

            //Compongo il nome completo dell'immagine da stampare
            $nomeImg = $ISBN . $ext;

            //Percorso dell'immagine
            $pathImg = "res/IMG_USER/";

            //Stampa del libro
            echo "<div class=\"info-left\">";
                
                echo "<ul>";
                echo "<li><strong> Titolo: </strong>" . $titolo . "</li>";
                echo "<li><strong> Autore: </strong>" . $autoreNome ." ". $autoreCognome . "</li>";
                echo "<li><strong> ISBN-13: </strong>" . $ISBN . "</li>";
                echo "<li><strong> Lunghezza: </strong>" . $lunghezza . " pagine" . "</li>";
                echo "<li><strong> Data di uscita: </strong>" . $data . "</li>";
                echo "</ul>";

            echo "</div>";

            echo "<div class=\"info-right\">";
                echo "<img src='" . $pathImg . $nomeImg . "' alt='Copertina.jpg'>";
            echo "</div>";
        }

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