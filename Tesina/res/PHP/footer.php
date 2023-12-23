<?php
    if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
        echo "<link rel=\"stylesheet\" href=\"res/CSS/footer_dark.css\" type=\"text/css\" />";
    }
    else{
        echo "<link rel=\"stylesheet\" href=\"res/CSS/footer.css\" type=\"text/css\" />"; //il percorso è da stabilire in base a dove viene stampato, non del file footer.php
    }
?>

<div class = "footer">
    <ul>
        <li><a href="chi_siamo.php">CHI SIAMO</a></li>
        <li><a href="https://www.google.com/maps/dir//Viale+Andrea+Doria,+5,+04100+Latina+LT/@41.4714065,12.9077018,18.5z/data=!4m9!4m8!1m0!1m5!1m1!1s0x13250c863f296777:0x1a9a2ce6fa96fe1a!2m2!1d12.9077336!2d41.4714773!3e0?hl=it&entry=ttu">DOVE SIAMO</a></li>
        <li>NUMERO VERDE: +39 1234567891</li>
        <li><a href="FAQ.php">FAQ</a></li>
        <?php
            if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){
                
                include('connection.php');
                $connessione = new mysqli($host, $user, $password, $db);

                $query = "SELECT umn.ban FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";
                $ris = $connessione->query($query);

                if ($ris) {
                    $row = $ris->fetch_assoc();
                    $ban = $row['ban'];
                }
                else{
                    exit(1);
                }
                
                if($ban == 0){
                    echo "<li><a href=\"aggiungi_domanda_prodotto.php\">HAI UNA DOMANDA SU PRODOTTO?</a></li>";
                }  
            }
        ?>
        <li><a href="mostra_domande_prodotto.php">DOMANDE SUI PRODOTTI</a></li>
    </ul>
    <p>TUTTI I DIRITTI RISERVATI &copy; <span id="current-year">2023</span></p>
</div>
