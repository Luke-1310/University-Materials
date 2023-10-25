<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_gestione_segnalazione_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_gestione_segnalazione.css\" type=\"text/css\" />";
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
    <title>Gestione Segnalazioni</title>
</head>
    
<?php 
    $pagina_corrente = "gestione_segnalazioni";
    include('res/PHP/navbar.php');
    include('res/PHP/funzioni.php');
    require('res/PHP/connection.php');
?>
<body>
   
<?php   

$xmlpath = "res/XML/Q&A.xml";
$domande = getDomande($xmlpath);

$connessione = new mysqli($host, $user, $password, $db);

$iSSegnalazioni = false;

//controllo errori
if(isset($_SESSION['errore_query']) && $_SESSION['errore_query'] == 'true'){
    echo "<h4>ERRORE NELL'ESECUZIONE DELLA QUERY!</h4>";
    unset($_SESSION['errore_query']);
}

if(isset($_SESSION['ban_ok']) && $_SESSION['ban_ok'] == 'true'){
    echo "<h4>L'UTENTE È STATO BANNATO E IL COMMENTO NON È PIÙ VISIBILE!</h4>";
    unset($_SESSION['ban_ok']);
}

if(isset($_SESSION['noban_ok']) && $_SESSION['noban_ok'] == 'true'){
    echo "<h4>LA SEGNALAZIONE ERRONEA È STATA RIMOSSA!</h4>";
    unset($_SESSION['ban_ok']);
}

foreach($domande as $domanda){  

    if($domanda['segnalazione'] == 1){
        $iSSegnalazioni = true;
    }

    foreach($domanda['risposte'] as $risposta){

        if($risposta['segnalazione'] == 1){
            $iSSegnalazioni = true;
        }
    }
}

if($iSSegnalazioni){

    echo"<div class=\"container-storico\">";

        echo "<div class=\"column\">";
            echo "<h4>TIPOLOGIA:</h4>";
        echo"</div>";

        echo "<div class=\"column\">";
            echo "<h4>ISBN PRODOTTO:</h4>";
        echo"</div>";

        echo "<div class=\"column\">";
            echo "<h4>TESTO CONTRIBUTO:</h4>";
        echo"</div>";

        echo "<div class=\"column\">";
            echo "<h4>SCRITTO DA:</h4>";
        echo"</div>";

        echo "<div class=\"column\">";
            echo "<h4>BAN:</h4>";;
        echo"</div>";

        foreach($domande as $domanda){

            if($domanda['segnalazione'] == 1){

                echo "<div class=\"column\">";
                    echo "DOMANDA";
                echo"</div>"; 

                echo "<div class=\"column\">";
                    echo $domanda['ISBNProdotto'];
                echo"</div>"; 

                echo "<div class=\"column-text\">";
                    echo $domanda['testoDom'];
                echo"</div>"; 

                $query_dom = "SELECT umn.username FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.id = '{$domanda['IDDom']}'";
                $result_dom = $connessione->query($query_dom);
        
                //Verifico se la query ha restituito risultati
                if ($result_dom) {
        
                    $row_dom = $result_dom->fetch_assoc();
                    $usernameDom = $row_dom['username'];
                } 
                
                else {
                    echo "Errore nella query: " . $connessione->error;
                }

                echo "<div class=\"column\">";
                    echo $usernameDom;
                echo"</div>"; 

                echo "<div class=\"column\">";

                    echo "<div class=\"conferma\">";

                        echo "<form action = \"res/PHP/gestione_segnalazioni.php?from=domanda\" method='POST'>";

                            echo "<input type=\"hidden\" name=\"username\" value=". $usernameDom .">";
                            echo "<input type=\"hidden\" name=\"id\" value=". $domanda['IDDom'] .">";
                            echo "<input type=\"hidden\" name=\"data\" value=". $domanda['dataDom'] .">";

                            echo "<button name=\"bottone_ban_ok\" type=\"submit\">";
                            echo "<i id=\"check\" class=\"material-icons\">check</i></button>";

                            echo "<input type=\"hidden\" name=\"username\" value=". $usernameDom .">";
                            echo "<input type=\"hidden\" name=\"id\" value=". $domanda['IDDom'] .">";
                            echo "<input type=\"hidden\" name=\"data\" value=". $domanda['dataDom'] .">";

                            echo "<button name=\"bottone_ban_ko\" type=\"submit\">";
                            echo "<i id=\"block\" class=\"material-icons\">close</i></button>";

                        echo "</form>";

                    echo "</div>";

                echo"</div>"; 
            }
        
            foreach($domanda['risposte'] as $risposta){
        
                if($risposta['segnalazione'] == 1){

                    echo "<div class=\"column\">";
                        echo "RISPOSTA";
                    echo"</div>";

                    echo "<div class=\"column\">";
                        echo $domanda['ISBNProdotto'];
                    echo"</div>"; 

                    echo "<div class=\"column-text\">";
                        echo $risposta['testoRisp'];
                    echo"</div>"; 

                    $query_ris = "SELECT umn.username FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.id = '{$risposta['IDRisp']}'";
                    $result_ris = $connessione->query($query_ris);
            
                    //Verifico se la query ha restituito risultati
                    if ($result_ris) {
            
                        $row_ris = $result_ris->fetch_assoc();
                        $usernameRisp = $row_ris['username'];
                    } 
                    
                    else {
                        echo "Errore nella query: " . $connessione->error;
                    }
                    echo "<div class=\"column\">";
                        echo $usernameRisp;
                    echo"</div>"; 

                    echo "<div class=\"column\">";

                        echo "<div class=\"conferma\">";

                            echo "<form action = \"res/PHP/gestione_segnalazioni.php?from=risposta\" method='POST'>";

                                echo "<input type=\"hidden\" name=\"username\" value=". $usernameRisp .">";
                                echo "<input type=\"hidden\" name=\"id\" value=". $risposta['IDRisp'] .">";
                                echo "<input type=\"hidden\" name=\"data\" value=". $risposta['dataRisp'] .">";

                                echo "<button name=\"bottone_ban_ok\" type=\"submit\">";
                                echo "<i id=\"check\" class=\"material-icons\">check</i></button>";
                        
                                echo "<input type=\"hidden\" name=\"username\" value=". $usernameRisp .">";
                                echo "<input type=\"hidden\" name=\"id\" value=". $risposta['IDRisp'] .">";
                                echo "<input type=\"hidden\" name=\"data\" value=". $risposta['dataRisp'] .">";

                                echo "<button name=\"bottone_ban_ko\" type=\"submit\">";
                                echo "<i id=\"block\" class=\"material-icons\">close</i></button>";

                            echo"</form>";
                            
                        echo "</div>";

                    echo"</div>"; 
                }
            }
        }

    echo "</div>";
}

else{
    echo "<p id=\"no_response\">NESSUNA SEGNALAZIONE TROVATA... ¯\_(ツ)_/¯</p>";
}

?>

</body>

<?php include('res/PHP/footer.php')?>


</html>