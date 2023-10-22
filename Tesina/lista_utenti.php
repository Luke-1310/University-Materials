<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_lista_utenti_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_lista_utenti.css\" type=\"text/css\" />";
   }
?>
<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- titolo della scheda -->
<head>
    <title>Lista utenti</title>
</head>
    
<?php 
    $pagina_corrente = "lista_utenti";
    include('res/PHP/navbar.php');
    include('res/PHP/funzioni.php');
    include('res/PHP/connection.php');
?>

<body>

<?php

echo "<p class=\"titolo\">LISTA UTENTI REGISTRATI!</p>";

$connessione = new mysqli($host, $user, $password, $db);

//stampo tutti gli utenti, ma proprio tutti tutti, anche se stesso
$query = "SELECT umn.* FROM utenteMangaNett umn";

$ris = $connessione->query($query);

//Verifico se la query ha restituito risultati
if ($ris) {

    //Estraggo il risultato come un array associativo
    while($row = $ris->fetch_assoc()){
        
        // Dopo che il ciclo ha eseguito le istruzioni, PHP torna all'inizio del ciclo (while) e verifica se $ris->fetch_assoc() restituisce un altro record. 
        // Se sì, il ciclo viene eseguito di nuovo con un nuovo set di dati in $row; altrimenti, il ciclo si interrompe.

        echo "<div class=\"container\">";

            echo "<div class=\"columns-container\">";

                echo "<div class=\"column\">";
                    echo "<h3>NOME UTENTE:</h3>";
                    echo "<p>". $row['username'] ."</p>";
                echo "</div>";

                echo "<div class=\"column\">";
                    echo "<h3>RUOLO:</h3>";
                    echo "<p>" . $row['ruolo'] . "</p>";
                echo "</div>";

                echo "<div class=\"column\">";

                    echo "<div class=\"conferma\">";
                        echo "<h3>PROMUOVI/RETROCEDI RUOLO:</h3>";

                        echo "<form action = \"res/PHP/promuovi_retrocedi.php\" method='POST'>";

                            //mi invio anche l'id
                            echo "<input type=\"hidden\" name=\"id\" value=" . $row['id'] . ">";

                            //mi invio anche il ruolo attuale prima di, eventualmente, cambiarlo
                            echo "<input type=\"hidden\" name=\"ruolo\" value=" . $row['ruolo'] . ">";

                            echo "<button name=\"bottone_promuovi\" type=\"submit\">";
                            echo "<i id=\"promuovi\" class=\"material-icons\">keyboard_double_arrow_up</i></button>";

                            echo "<button name=\"bottone_retrocedi\" type=\"submit\">";
                            echo "<i id=\"retrocedi\" class=\"material-icons\">keyboard_double_arrow_down</i></button>";

                        echo "</form>";

                    echo "</div>";

                echo "</div>";

                echo "<div class=\"column\">";
                    echo "<h3>È SEGNALATO:</h3>";
                    
                    if($row['segnalazione']){
                        echo "<strong><p style=\"color: red;\">SI</p></strong>";
                    }
                    else{
                        echo "<strong><p style=\"color: green;\">NO</p></strong>";
                    }
                echo "</div>";

                echo "<div class=\"column\">";
                    echo "<h3>È BANNATO:</h3>";

                    if($row['ban']){
                        echo "<strong><p style=\"color: red;\">SI</p></strong>";
                    }
                    else{
                        echo "<strong><p style=\"color: green;\">NO</p></strong>";
                    }
                echo "</div>";

                echo "<div class=\"column\">";
                    echo "<h3>VISUALIZZA INFORMAZIONI:</h3>";

                    echo "<div class=\"conferma\">";

                        echo "<form action = \"res/PHP/vedi_informazioni.php\" method='POST'>";
                            echo "<button name=\"bottone_promuovi\" type=\"submit\">";
                            echo "<i id=\"info\" class=\"material-icons\">info</i></button>";
                        echo "</form>";

                    echo "</div>";
                echo "</div>";

            echo "</div>";

        echo "</div>";


    }
}
else{
    exit(1);
}

?>
</body>

<?php include('res/PHP/footer.php')?>


</html>