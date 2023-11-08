<?php
   session_start();

   require_once('res/PHP/connection.php');
    
   $connessione = new mysqli($host, $user, $password, $db);

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_reputazione_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_reputazione.css\" type=\"text/css\" />";
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
    <title>Reputazione</title>
</head>
    
<?php 
    $pagina_corrente = "reputazione";
    include('res/PHP/funzioni.php');
    include('res/PHP/connection.php');
    include('res/PHP/navbar.php')
?>


<body>

<?php
    echo "<div class=\"container\">";

        echo "<p id=\"titolo\">VEDIAMO LA TUA REPUTAZIONE!</p>";

        //mi prendo anche l'ID che poi mi invio in res/PHP/reputazione.php
        $sql_id = "SELECT u.id FROM utentemanganett u WHERE u.username = '{$_SESSION['nome']}'";

        $ris_id = mysqli_query($connessione, $sql_id);

        if(mysqli_num_rows($ris_id) == 1){

            //Estraggo il risultato come un array associativo
            $row = $ris_id->fetch_assoc();
            $id = $row['id']; 
        }
        else{
            exit(1);
        }

        $isAMorGS = false;

        //nel caso in cui l'utente loggato sia un GS o AM, la reputazione è fissata a 12!
        $sql = "SELECT u.ruolo FROM utentemanganett u WHERE u.username = '{$_SESSION['nome']}' AND (u.ruolo = 'GS' OR u.ruolo = 'AM' OR u.ruolo = 'SA')";

        $ris_r = mysqli_query($connessione, $sql);

        if(mysqli_num_rows($ris_r) == 1){
            $isAMorGS = true;
        }

        if($isAMorGS){
            echo "<p id=\"titolo\">IN BASE AL TUO RUOLO HAI LA REPUTAZIONE FISSATA A 12 ( ＾◡＾)</p>";
        }
        
        else{
            $xmlpath= "res/XML/Q&A.xml";
            $reputazione = calcolaReputazione($id, $xmlpath);
            
            echo "<p id=\"titolo\"> IL TUO LIVELLO È: " . $reputazione. "</p>";
        }

        echo "<img src=\"res/WEBSITE_MEDIA/military-salute.gif\" alt=\"reputation_GIF\" width=\"18%\" id=\"military_salute\">";

   echo"</div>";
?>

</body>

<?php include('res/PHP/footer.php')?>

</html>