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

        require("res/PHP/connection.php");

        $connessione = new mysqli($host, $user, $password, $db);
        
        $query = "SELECT* FROM libro";
        $result = mysqli_query($connessione, $query);

        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {
                
                echo"<div class = row>";
                    echo"<div class = item>";
                        echo "<p>" . $row['titolo'] . "<br/>" . "<br/>" . "</p>";
                        $img_bin = $row['immagine'];
                        echo '<img src="data:image/jpeg;base64,' . $img_bin . '" alt="Immagine">';
                    echo"</div>";
                echo"</div>";
            }
        } 
        
        else {
            echo "Nessun record trovato";
        }

        mysqli_close($connessione);
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