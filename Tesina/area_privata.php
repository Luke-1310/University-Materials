<?php
   session_start();

    require_once('res/PHP/connection.php');
    
    $connessione = new mysqli($host, $user, $password, $db);

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_area_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_area.css\" type=\"text/css\" />";
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
    <title>Area Privata</title>
</head>
    
<?php 
    $pagina_corrente = "area_privata";
    include('res/PHP/navbar.php')
?>

<body>
    
    <div class="container-external">
        
        <div class="container">
            
            <div class="cell">
                <a href="profilo.php"><i id="info" class="material-icons">info</i></a>
                <a href="profilo.php">VISUALIZZA PROFILO</a>
            </div>

            <div class="cell">
                <a href="area_privata.php"><i id="shop" class="material-icons">shopping_bag</i></a>
                <a href="area_privata.php">STORICO ACQUISTI</a>
            </div>
            
            <div class="cell">
                <a href="storico_crediti.php"><i id="credit" class="material-icons">savings</i></a>
                <a href="storico_crediti.php">STORICO CREDITI</a>
            </div>

            <div class="cell">
                <a href="reputazione.php"><i id="status" class="material-icons">military_tech</i></a>
                <a href="reputazione.php">REPUTAZIONE</a>
            </div>

            <div class="cell">
                <a href="mostra_domande_prodotto.php"><i id="question" class="material-icons">contact_support</i></a>
                <a href="mostra_domande_prodotto.php">DOMANDE E RISPOSTE SU UN PRODOTTO</a>
            </div>

            <?php
                //se l'utente è un gestore allora ha delle funzionalità in più
                $sql = "SELECT u.ruolo FROM utentemanganett u WHERE u.username = '{$_SESSION['nome']}' AND u.ruolo = 'GS'";
                $ris = mysqli_query($connessione, $sql);

                if(mysqli_num_rows($ris) == 1){
                    echo "<div class=\"cell\">";
                    echo "<a href=\"aggiungi_prodotto.php\"><i id=\"product\" class=\"material-icons\">sell</i></a>";
                    echo "<a href=\"aggiungi_prodotto.php\">AGGIUNGI PRODOTTO</a>";
                    echo "</div>";
                    
                }

                //se l'utente è un amministratore allora ha tutte le funzionalità
                $sql = "SELECT u.ruolo FROM utentemanganett u WHERE u.username = '{$_SESSION['nome']}' AND u.ruolo = 'AM'";
                $ris = mysqli_query($connessione, $sql);

                if(mysqli_num_rows($ris) == 1){
                    echo "<div class=\"cell\">";
                    echo "<a href=\"aggiungi_prodotto.php\"><i id=\"product\" class=\"material-icons\">sell</i></a>";
                    echo "<a href=\"aggiungi_prodotto.php\">AGGIUNGI PRODOTTO</a>";
                    echo "</div>";

                    echo "<div class=\"cell\">";
                    echo "<a href=\"richiesta_crediti.php\"><i id=\"richiesta\" class=\"material-icons\">currency_exchange</i></a>";
                    echo "<a href=\"richiesta_crediti.php\">RICHIESTE CREDITI</a>";
                    echo "</div>";

                    echo "<div class=\"cell\">";
                    echo "<a href=\"lista_utenti.php\"><i id=\"lista_utenti\" class=\"material-icons\">list</i></a>";
                    echo "<a href=\"lista_utenti.php\">LISTA UTENTI</a>";
                    echo "</div>";
                }
            ?>
        </div>
    </div>
</body>

<?php include('res/PHP/footer.php')?>


</html>