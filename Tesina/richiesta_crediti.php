<?php
   session_start();

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_richiesta_cr_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_richiesta_cr.css\" type=\"text/css\" />";
   }
?>
<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<head>
    <title>Richiesta Crediti</title>
</head>
    
<?php 
    $pagina_corrente = "richiesta_crediti";
    include('res/PHP/navbar.php');
    require('res/PHP/funzioni.php');
    require('res/PHP/connection.php');
?>

<body>

   <?php
        $xmlPath ="res/XML/richieste_crediti.xml";
    
        //devo stampare tutte le richieste dell'utente corrente loggato
        $richieste = getRichiesteCr($xmlPath);
        
        // Ordina l'array $richieste in base alla data e all'ora in ordine decrescente
        usort($richieste, function($a, $b) {
            return strtotime($b['dataRichiesta']) - strtotime($a['dataRichiesta']);
        });
    
        $contatore = 0;

        //ora devo vedere se per caso non ci sono state richieste prima e per tale motivo faccio un controllo
        //se il controllo mi da come risultato 0 allora non c'è stata nessuna richiesta
        foreach($richieste as $richiesta){
    
            if($richiesta['risposta'] == 0){
                $contatore ++;
            }
        }
    
        if($contatore == 0)
        {
            echo "<p id=\"no_response\">NESSUNA RICHIESTA DA ACCETTARE TROVATA... ¯\_(ツ)_/¯</p>";
        }
        else{
            
            echo "<h3 class=\"titolo_storico\">RICHIESTE DA ACCETTARE</h3>";

            //apro un nuovo container per stampare lo storico delle richieste  che devono essere
            echo "<div class=\"container-storico\">";
                
                //qui ci siamo presi le richieste corrette però alcune non sono ben formattate
                foreach($richieste as $richiesta){
        
                    if($richiesta['risposta'] == 0){

                        $id = $richiesta['IDUtente'];
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

                        $connessione = new mysqli($host, $user, $password, $db);

                        //ed infine invece di stampare l'id meglio stampare l'username dell'utente richiedente
                        $query = "SELECT umn.username FROM utenteMangaNett umn WHERE umn.id = $id";
                        $ris = $connessione->query($query);

                        //Verifico se la query ha restituito risultati
                        if ($ris) {

                            //Estraggo il risultato come un array associativo
                            $row = $ris->fetch_assoc();
                            $username = $row['username']; 
                        }
                        else{
                            exit(1);
                        }

                        echo "<div class=\"column\">";
                            echo "<h4>UTENTE:</h4>";
                            echo "<p>". $username ."</p>";
                        echo "</div>";

                        echo "<div class=\"column\">";
                            echo "<h4>DATA:</h4>";
                            echo "<p>". $data ."</p>";
                        echo "</div>";
        
                        echo "<div class=\"column\">";
                            echo "<h4>ORA:</h4>";
                            echo "<p>". $ora ."</p>";
                        echo "</div>";
        
                        echo "<div class=\"column\">";
                            echo "<h4>QUANTITÀ:</h4>";
                            echo "<p>". $quantita." CR</p>";
                        echo "</div>";
        
                        echo "<div class=\"column\">";
                            echo "<h4>STATO:</h4>";
        
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

                        echo "<div class=\"column\">";
                            echo "<div class=\"conferma\">";
                                echo "<h4>CONFERMA:</h4>";
                                echo "<form action = \"res/PHP/richiesta_crediti.php\" method='POST'>";

                                    //mi invio anche l'id, dataT e quantità  per poter, in caso, aggiungere i crediti all'utente
                                    echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
                                    echo "<input type=\"hidden\" name=\"crediti\" value=\"$quantita\">";
                                    echo "<input type=\"hidden\" name=\"data\" value=\"$data_XML\">";

                                    echo "<button name=\"bottone_si\" type=\"submit\">";
                                    echo "<i id=\"conferma\" class=\"material-icons\">done</i></button>";
                                    echo "<button name=\"bottone_no\" type=\"submit\">";
                                    echo "<i id=\"rifiuta\" class=\"material-icons\">close</i></button>";

                                echo "</form>";

                            echo "</div>";
                        echo "</div>";
                    }
                }
            echo "</div>";
        }
    ?>

</body>

<?php include('res/PHP/footer.php')?>


</html>