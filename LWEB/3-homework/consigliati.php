<?php
    require("res/PHP/connection.php");

    $connessione = new mysqli($host, $user, $password, $db);

    session_start();

    if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_con_dark.css\" type=\"text/css\" />";
    }

    else{
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_con.css\" type=\"text/css\" />";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Recensioni... </title>
</head>

<body>
    <h1 class="titolo">RECENSIONI&#x1F4A1;</h1>

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

    $xmlFile = "res/XML/libri.xml";

    $xml = simplexml_load_file($xmlFile);

    foreach($xml->book as $book){

        foreach($book->recensione as $recensione) {
            
            //Queste sono le informazioni della recensione
            $utente = $recensione->utente;
            $testoRecensione = $recensione->rec;
            $rating = $recensione->rating;
            
            //Queste sono le informazione del libro
            $titolo = $book->titolo;
            $nome = $book->autore->nome;
            $cognome = $book->autore->cognome;
            $pagine = $book->lunghezza;
            $data = $book->data;

            //Questo è l'attributo ISBN di book
            $ISBN = (string)$book->attributes()->isbn;

            //Estensione della copertina del libro
            $estensione = ".jpg";

            //Ricavo il nome della copertina corrispondente
            $nomeImmagine = $ISBN . $estensione;

            //Metto il percorso della cartella contenente le immagini
            $percorso = "res/IMG_USER/";

            //Procedo alla stampa della recensione corrente
            echo"<div class=\"grid\">";
                echo"<div class=\"container-1\">";
                    echo"<table>";
                    echo "<tr>";             
                    echo"<th> Titolo originale </th>";      
                    echo"<td>" . $titolo ."</td>";
                    echo"</tr>";
    
                    echo "<tr>";             
                    echo"<th> ISBN-13 </th>";      
                    echo"<td>" . $ISBN ."</td>";
                    echo"</tr>";
    
                    echo "<tr>";             
                    echo"<th> Numero di pagine </th>";      
                    echo"<td>" . $pagine ."</td>";
                    echo"</tr>";
    
                    echo "<tr>";             
                    echo"<th> Nome autore </th>";      
                    echo"<td>" . $nome ."</td>";
                    echo"</tr>";

                    echo "<tr>";             
                    echo"<th> Cognome autore </th>";      
                    echo"<td>" . $cognome ."</td>";
                    echo"</tr>";

                    echo "<tr>";             
                    echo"<th> Data di uscita </th>";      
                    echo"<td>" . $data ."</td>";
                    echo"</tr>";
    
                    echo "<tr>";             
                    echo"<th> Voto </th>";      
                    echo"<td>" . $rating ."</td>";
                    echo"</tr>";
    
                echo"</table>";
            echo"</div>";
    
        echo "<div class =\"container-2\">";
            echo "<p>" . $testoRecensione . "<br/>" . "<br/>" . "<strong>" . "Scritta da: " . $utente . "</strong>" . "</p>";
        echo"</div>";
    
        echo "<div class =\"container-3\">";
            echo "<img src ='" . $percorso . $nomeImmagine . "' alt= 'Copertina.jpg'>";
        echo "</div>";
        
    echo "</div>";

    echo "<hr/>";

        }  
    }

?> 

<div class="crediti">
    <p>Responsabili del sito: 
    <a href="mailto:privitera.1938225@studenti.uniroma1.it">privitera.1938225@studenti.uniroma1.it</a>    
    <a href="mailto:coluzzi.1912970@studenti.uniroma1.it">coluzzi.1912970@studenti.uniroma1.it</a>    
    </p>
</div>

</body>
</html>