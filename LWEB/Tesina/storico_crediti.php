<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_storico_cr_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_storico_cr.css\" type=\"text/css\" />";
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
    <title>Storico Crediti</title>
</head>
    
<?php 
    $pagina_corrente = "storico_crediti";
    include('res/PHP/navbar.php');
    include('res/PHP/funzioni.php');
    require('res/PHP/connection.php');
?>
<body>
   
<?php   
    if(isset($_SESSION['richiesta_ok']) && $_SESSION['richiesta_ok'] = true){
        echo "<h4 id=\"esito_positivo\">L'OPERAZIONE È ANDATA A BUON FINE!</h4>";
        unset($_SESSION['richiesta_ok']);
    }
    
    echo "<div class=\"container\">";

        $connessione = new mysqli($host, $user, $password, $db);

        //mi prendo i crediti attuali del utente loggato
        $query = "SELECT umn.crediti FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.username = '{$_SESSION['nome']}'";
        $result = $connessione->query($query);

        //Verifico se la query ha restituito risultati
        if ($result) {

            //Estraggo il risultato come un array associativo
            $row = $result->fetch_assoc();
        } 
        
        else {
            echo "Errore nella query: " . $connessione->error;
        }

        $creditiAttuali = number_format($row['crediti'], 2, '.', '');

        echo "<div class=\"columns-container\">";

            echo "<div class=\"column\">";
                echo "<h3>CREDITI ATTUALI:</h3>";
                echo "<p>". $creditiAttuali ." CR</p>";
            echo "</div>";

            $xmlPath = "res/XML/storico_acquisti.xml";
            $crediti_spesi = getCreditiSpesiTotCurr($xmlPath);

            echo "<div class=\"column\">";
                echo "<h3>CREDITI SPESI:</h3>";
                echo "<p>". $crediti_spesi ." CR</p>";
            echo "</div>";

            echo "<div class=\"column\">";
                
                if(isset($_SESSION['errore_storico_cr']) && $_SESSION['errore_storico_cr'] == 'true'){//isset verifica se errore è settata
                    echo "<h4 id=\"errore\">INSERISCI UN VALORE!</h4>";
                    unset($_SESSION['errore_storico_cr']);//la unsetto altrimenti rimarrebbe la scritta
                }

                echo "<div class=\"richiesta\">";
                    echo "<h3>RICHIESTA CREDITI:</h3>";

                    echo "<form id=\"richiestaForm\" action=\"res/PHP/storico_crediti.php\" method=\"POST\">";
                        echo "<input type=\"number\" name=\"crediti\" step=\"0.01\" min=\"0\" max=\"1000\">";
                        echo "<input type=\"submit\" value=\"INVIA\">";
                    echo "</form>";    
                    
                echo "</div>";

            echo "</div>";

        echo "</div>";

    echo "</div>";
    
    echo "<h3 class=\"titolo_storico\">STORICO RICHIESTE</h3>";

    $xmlPath ="res/XML/richieste_crediti.xml";

    //devo stampare tutte le richieste dell'utente corrente loggato
    $richieste = getRichiesteCr($xmlPath);
    
    // Ordina l'array $richieste in base alla data e all'ora in ordine decrescente
    usort($richieste, function($a, $b) {
        return strtotime($b['dataRichiesta']) - strtotime($a['dataRichiesta']);
    });

    //innanzitutto mi dovrei prendere l'id dell'utente corrente, in modo tale da poterlo associare alle varie richieste
    //$query2 = "SELECT umn.id FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.username = '{$_SESSION['nome']}'";      
    $query2 = "SELECT umn.id FROM utenteMangaNett umn WHERE umn.username='{$_SESSION['nome']}'";
    $result2 = $connessione->query($query2); 

    //Verifico se la query ha restituito risultati
    if ($result2) {

        $row2 = $result2->fetch_assoc();
    }

    echo "<div class=\"container-storico\">";

    $contatore = 0;

    //ora devo vedere se per caso non ci sono state richieste prima e per tale motivo faccio un controllo
    foreach($richieste as $richiesta){

        if($richiesta['IDUtente'] == $row2['id']){
            $contatore ++;
        }
    }

    if($contatore == 0)
    {
        echo "<p>NESSUNA RICHIESTA TROVATA!</p>";
    }
    else{   

        $stampaTitoli = false;

        foreach($richieste as $richiesta){

            if(!$stampaTitoli){
                echo "<div class=\"column\">";
                    echo "<h4>DATA:</h4>";
                echo"</div>";

                echo "<div class=\"column\">";
                    echo "<h4>ORA:</h4>";
                echo"</div>";

                echo "<div class=\"column\">";
                    echo "<h4>QUANTITÀ:</h4>";
                echo"</div>";

                echo "<div class=\"column\">";
                    echo "<h4>ESITO:</h4>";;
                echo"</div>";

                $stampaTitoli = true;
            }

            if($richiesta['IDUtente'] == $row2['id']){
                $quantita = $richiesta['quantita'];
                $data_XML = $richiesta['dataRichiesta'];
                $risposta = $richiesta['risposta'];

                //ovviamente la data presentandosi come 2023-08-27T14:30:00 va formattata 
                //Divide la stringa in base al carattere "T"
                $parti = explode("T", $data_XML);

                //$parti[0] conterrà la data (parte prima di T) e $parti[1] conterrà l'ora (parte dopo di T)
                $data = $parti[0];
                $ora = $parti[1];

                //bene, ora tocca formattare l'esito chiaramente -1,0, 1 non è intuitivo
                if($risposta == -1){
                    $esito = "NON ACCETTATA";
                }

                else if($risposta == 0){
                    $esito = "IN ATTESA";
                }

                else if($risposta == 1){
                    $esito = "ACCETTATA";
                }

                else{
                    $esito = "NON È STATO POSSIBILE TROVARE L'ESITO";
                }

                echo "<div class=\"column\">";
                    echo "<p>". $data ."</p>";
                echo "</div>";

                echo "<div class=\"column\">";
                    echo "<p>". $ora ."</p>";
                echo "</div>";

                echo "<div class=\"column\">";
                    echo "<p>". $quantita." CR</p>";
                echo "</div>";

                echo "<div class=\"column\">";

                    if ($esito == "NON ACCETTATA") {
                        echo '<strong><p style="color: red;">' . $esito . "</p></strong>";
                    } 
                    elseif ($esito == "IN ATTESA") {
                        echo '<strong><p style="color: gray;">' . $esito . "</p></strong>";
                    } 
                    elseif ($esito == "ACCETTATA") {
                        echo '<strong><p style="color: green;">' . $esito . "</p></strong>";
                    } 
                echo "</div>";
            }
        }
    }

    echo "</div>";

    $connessione->close();
?>

</body>

<?php include('res/PHP/footer.php')?>


</html>