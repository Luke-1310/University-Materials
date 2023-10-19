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
?> 

<?php

$recensione = "SELECT* FROM recensione r, libro l, utente u WHERE r.utente_ID = u.id AND r.libro_ID = l.id";
$ris = mysqli_query($connessione, $recensione);

foreach($ris as $row){

    echo"<div class=\"grid\">";
        echo"<div class=\"container-1\">";
            echo"<table>";

                echo "<tr>";             
                echo"<th> Titolo originale </th>";      
                echo"<td>" . $row['titolo'] ."</td>";
                echo"</tr>";

                echo "<tr>";             
                echo"<th> ISBN-13 </th>";      
                echo"<td>" . $row['ISBN13'] ."</td>";
                echo"</tr>";

                echo "<tr>";             
                echo"<th> Numero di pagine </th>";      
                echo"<td>" . $row['lunghezza'] ."</td>";
                echo"</tr>";

                echo "<tr>";             
                echo"<th> Autore </th>";      
                echo"<td>" . $row['autore'] ."</td>";
                echo"</tr>";

                echo "<tr>";             
                echo"<th> Data di uscita </th>";      
                echo"<td>" . $row['data_uscita'] ."</td>";
                echo"</tr>";

                echo "<tr>";             
                echo"<th> Voto </th>";      
                echo"<td>" . $row['voto'] ."</td>";
                echo"</tr>";

            echo"</table>";
        echo"</div>";

    echo "<div class =\"container-2\">";
        echo "<p>" . $row['testo']. "<br/>" . "<br/>" . "<strong>" . "Scritta da: " .$row['username']. "</strong>" . "</p>";
    echo"</div>";

    echo "<div class =\"container-3\">";
    $img_bin = $row['immagine'];
    echo '<img src="data:image/jpeg;base64,' . $img_bin . '" alt="Immagine">';
    echo "</div>";
echo "</div>";
echo "<hr/>";

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