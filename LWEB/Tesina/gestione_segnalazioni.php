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
$xmlpathfumetti = "res/XML/catalogo.xml";

$domande = getDomande($xmlpath);
$fumetti = getFumetti($xmlpathfumetti);

$connessione = new mysqli($host, $user, $password, $db);

$iSSegnalazioni = false;

//controllo errori
if(isset($_SESSION['errore_query']) && $_SESSION['errore_query'] == 'true'){
    echo "<h4>ERRORE NELL'ESECUZIONE DELLA QUERY!</h4>";
    unset($_SESSION['errore_query']);
}

if(isset($_SESSION['ban_ok']) && $_SESSION['ban_ok'] == 'true'){
    echo "<h4 id=\"esito_positivo\">L'UTENTE È STATO BANNATO E IL CONTRIBUTO NON È PIÙ VISIBILE!</h4>";
    unset($_SESSION['ban_ok']);
}

if(isset($_SESSION['noban_ok']) && $_SESSION['noban_ok'] == 'true'){
    echo "<h4 id=\"esito_positivo\">LA SEGNALAZIONE ERRONEA È STATA RIMOSSA!</h4>";
    unset($_SESSION['noban_ok']);
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

foreach($fumetti as $fumetto){

    foreach($fumetto['recensione'] as $recensione){
        
        if($recensione['segnalazione'] == 1){
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
            echo "<h4>SEGNALATO DA:</h4>";
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

                $query= "SELECT umn.username FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.id = '{$domanda['IDDom']}'";
                $result = $connessione->query($query);
        
                //Verifico se la query ha restituito risultati
                if ($result){
        
                    $row = $result->fetch_assoc();
                    $usernameDom = $row['username'];
                } 
                
                else {
                    echo "Errore nella query: " . $connessione->error;
                }

                echo "<div class=\"column\">";
                    echo $usernameDom;
                echo"</div>"; 

                //mi prendo l'ID del segnalatore
                $query = "SELECT umn.username FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.id = '{$domanda['IDSegnalatore']}'";
                $result = $connessione->query($query);
        
                //Verifico se la query ha restituito risultati
                if ($result) {
        
                    $row = $result->fetch_assoc();
                    $usernameSign_Dom = $row['username'];
                } 
                
                else {
                    echo "Errore nella query: " . $connessione->error;
                }

                echo "<div class=\"column\">";
                    echo $usernameSign_Dom;
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

                    $query = "SELECT umn.username FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.id = '{$risposta['IDRisp']}'";
                    $result = $connessione->query($query);
            
                    //Verifico se la query ha restituito risultati
                    if ($result){
            
                        $row = $result->fetch_assoc();
                        $usernameRisp = $row['username'];
                    } 
                    else {
                        echo "Errore nella query: " . $connessione->error;
                    }

                    echo "<div class=\"column\">";
                        echo $usernameRisp;
                    echo"</div>"; 

                    //mi prendo il nick dell'utente segnalatore
                    $query = "SELECT umn.username FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.id = '{$risposta['IDSegnalatore']}'";
                    $result = $connessione->query($query);
            
                    //Verifico se la query ha restituito risultati
                    if ($result) {
            
                        $row = $result->fetch_assoc();
                        $usernameSign_Risp = $row['username'];
                    } 
                    else {
                        echo "Errore nella query: " . $connessione->error;
                    }

                    echo "<div class=\"column\">";
                        echo $usernameSign_Risp;
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

        foreach($fumetti as $fumetto){

            foreach($fumetto['recensione'] as $recensione){

                if($recensione['segnalazione'] == 1){

                    echo "<div class=\"column\">";
                        echo "RECENSIONE";
                    echo"</div>"; 

                    echo "<div class=\"column\">";
                        echo $fumetto['isbn'];
                    echo"</div>"; 

                    echo "<div class=\"column-text\">";
                        echo $recensione['testoRecensione'];
                    echo"</div>"; 

                    $query= "SELECT umn.username FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.id = '{$recensione['IDRecensore']}'";
                    $result = $connessione->query($query);
            
                    //Verifico se la query ha restituito risultati
                    if ($result){
            
                        $row = $result->fetch_assoc();
                        $usernameRec = $row['username'];
                    } 
                    
                    else {
                        echo "Errore nella query: " . $connessione->error;
                    }

                    echo "<div class=\"column\">";
                        echo $usernameRec;
                    echo"</div>"; 

                    //mi prendo l'ID del segnalatore
                    $query = "SELECT umn.username FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.id = '{$recensione['IDSegnalatore']}'";
                    $result = $connessione->query($query);
            
                    //Verifico se la query ha restituito risultati
                    if ($result) {
            
                        $row = $result->fetch_assoc();
                        $usernameSign_Rec = $row['username'];
                    } 
                    
                    else {
                        echo "Errore nella query: " . $connessione->error;
                    }

                    echo "<div class=\"column\">";
                        echo $usernameSign_Rec;
                    echo"</div>"; 

                    echo "<div class=\"column\">";

                        echo "<div class=\"conferma\">";

                            echo "<form action = \"res/PHP/gestione_segnalazioni.php?from=recensione\" method='POST'>";

                                echo "<input type=\"hidden\" name=\"username\" value=". $usernameRec .">";
                                echo "<input type=\"hidden\" name=\"id\" value=". $recensione['IDRecensore'] .">";
                                echo "<input type=\"hidden\" name=\"data\" value=". $recensione['dataRecensione'] .">";

                                echo "<button name=\"bottone_ban_ok\" type=\"submit\">";
                                echo "<i id=\"check\" class=\"material-icons\">check</i></button>";

                                echo "<input type=\"hidden\" name=\"username\" value=". $usernameRec .">";
                                echo "<input type=\"hidden\" name=\"id\" value=". $recensione['IDRecensore'] .">";
                                echo "<input type=\"hidden\" name=\"data\" value=". $recensione['dataRecensione'] .">";

                                echo "<button name=\"bottone_ban_ko\" type=\"submit\">";
                                echo "<i id=\"block\" class=\"material-icons\">close</i></button>";

                            echo "</form>";

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