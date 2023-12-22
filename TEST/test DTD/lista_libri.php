<?php

$xmlPath = "biblioteca.xml";

$DOM = new DOMDocument();
$DOM->load($xmlPath);

echo"<div>";
    echo "<a href=\"homepage.php\">HOMEPAGE</a>";
echo "</div>";

echo "<div>";
echo " ------------------------------------------------ ";
echo "</div>";

$books = $DOM->getElementsByTagName("book");

$contatore = 1;

foreach($books as $book){

    $titolo = $book->getElementsByTagName("titolo")->item(0)->nodeValue;
    $isbn = $book->getAttribute("isbn");
    $rating = $book->getAttribute("rating");
    $anno = $book->getElementsByTagName("anno")->item(0)->nodeValue;

    echo "STAMPA DEL LIBRO NUMERO " . $contatore;

    echo "<div>";
        echo "TITOLO: " . $titolo;
    echo "</div>";

    echo "<div>";
        echo "ISBN: " . $isbn;
    echo "</div>";

    echo "<div>";
        echo "RATING: " . $rating;
    echo "</div>";

    echo "<div>";
        echo "ANNO: " . $anno;
    echo"</div>";

    echo "<div>";
    
    echo"</div>";
    echo "<div>";
    
    echo"</div>";
    echo "<div>";
    
    echo"</div>";

    echo "<div>";
        echo " ------------------------------------------------ ";
    echo "</div>";

    $contatore++;
}


?>