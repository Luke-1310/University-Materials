<?php
    session_start();

    if (isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro") {
        echo '<link rel="stylesheet" href="res/CSS/external_domspec_dark.css" type="text/css" />';
    } else {
        echo '<link rel="stylesheet" href="res/CSS/external_domspec.css" type="text/css" />';
    }
?>

<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Domande su un prodotto</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <?php 
        $pagina_corrente = "mostra_domanda_specifica";
        include('res/PHP/connection.php');
        include('res/PHP/navbar.php');
        include('res/PHP/funzioni.php');
    ?>
</head>

<body>
    
    <?php

        // Percorso del file XML
        $xmlFile = "res/XML/Q&A.xml";
        $xmlFile2 = "res/XML/catalogo.xml";

        $domande = getDomande($xmlFile);
        $fumetti = getFumetti($xmlFile2);

        //importantissimo controllo perché se si cambia tema si perde ovviamente il valore preso tramite il post
        if (isset($_POST['titolo'])) {

            $_SESSION['titolo_dom_sp'] = $_POST["titolo"];
        }
        
        echo "<p class=\"titoletto\">DOMANDE RELATIVE A ". $_SESSION['titolo_dom_sp'] ."!</p>";

        //mi prendo l'isbn
        foreach($fumetti as $fumetto){

            if($fumetto['titolo'] == $_SESSION['titolo_dom_sp']){
                
                $isbn = $fumetto['isbn'];
            }
        }

        $connessione = new mysqli($host, $user, $password, $db);

        foreach($domande as $domanda){

            if($domanda['ISBNProdotto'] == $isbn){
                
                echo "<div class=\"container_sp\">";

                    //mi devo prendere il nome utente corrispettivo del domandante
                    $query = "SELECT umn.username FROM utenteMangaNett umn WHERE umn.id = {$domanda['IDDom']}";

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

                    $parti = explode("T", $domanda['dataDom']);

                    //$parti[0] conterrà la data (parte prima di T) e $parti[1] conterrà l'ora (parte dopo di T)
                    $data = $parti[0];
                    $ora = $parti[1];

                    echo "<div class=\"domanda\">";

                        echo"<div class=\"info-domanda\">";
                            echo"<p class=\"utente\">$username</p>";
                            echo"<p class=\"data\">" . $data . " ". $ora ."</p>";
                        echo"</div>";

                        echo"<p class=\"testo-domanda\">" . $domanda['testoDom'] . "</p>";

                        if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){

                            //form AGGIUNGI RISPOSTA
                            echo"<form id=\"rispostaForm\" action = \"res/PHP/mostra_domanda_specifica.php\" method=\"POST\" >";

                                echo"<div class=\"form-row\">";
                                    echo"<label for=\"risposta\">AGGIUNGI UNA RISPOSTA...</label>";
                                    echo "<textarea id=\"risposta\" name=\"risposta\" rows=\"10\" cols=\"40\" placeholder=\"Inserisci qui la tua risposta....\" required></textarea>";
                                echo"</div>";

                                //mi invio la data della domanda
                                echo"<input type=\"hidden\" name=\"data\" value=". $domanda['dataDom'] . ">";

                                //mi invio anche l'ISBN 
                                echo"<input type=\"hidden\" name=\"isbn\" value=$isbn>";

                                echo "<span class =\"bottone\"><input type=\"submit\" value=\"INVIA\"></span>";

                            echo "</form>";

                            //faccio questo controllo perché solo l'admin può elevare a FAQ una domanda/risposta
                            $sql_am = "SELECT u.ruolo FROM utentemanganett u WHERE u.username = '{$_SESSION['nome']}' AND u.ruolo = 'AM'";
                            $ris_am = mysqli_query($connessione, $sql_am);

                            if(mysqli_num_rows($ris_am) == 1){

                                echo"<form id=\"bottoniForm\" action = \"res/PHP/eleva_a_FAQ.php\" method=\"POST\" >";

                                    //mi invio la  data della domanda
                                    echo"<input type=\"hidden\" name=\"dataDom\" value=". $domanda['dataDom'] . ">";

                                    //mi invio l'id della domanda
                                    echo"<input type=\"hidden\" name=\"IDDom\" value=". $domanda['IDDom'] . ">";
                                    echo "<span class =\"bottone\"><input type=\"submit\" value=\"ELEVA A FAQ\"></span>";
                                
                                echo "</form>";
                            }
                        }
                        else{
                            echo"<p id=\"new_question\"><a href=\"login.php\">LOGGATI PER INSERIRE UNA NUOVA RISPOSTA!</a></p>";
                        }

                        echo"<form id=\"bottoniForm\" action = \"res/PHP/segnala_contributo.php?from=domanda\" method=\"POST\" >";

                            // //mi invio la  data della domanda
                            // echo"<input type=\"hidden\" name=\"dataDom\" value=". $domanda['dataDom'] . ">";

                            // //mi invio l'id della domanda
                            // echo"<input type=\"hidden\" name=\"IDDom\" value=". $domanda['IDDom'] . ">";
                            echo "<span class =\"bottone\"><input type=\"submit\" value=\"SEGNALA\"></span>";
                        
                        echo "</form>";

                    echo"</div>";

                    //ora devo stampare le risposte se ci sono
                    foreach($domanda['risposte'] as $risposta){
                        
                        //mi devo prendere il nome utente corrispettivo del domandante
                        $query_r = "SELECT umn.username FROM utenteMangaNett umn WHERE umn.id = {$risposta['IDRisp']}";

                        $ris_r = $connessione->query($query_r);

                        //Verifico se la query ha restituito risultati
                        if ($ris_r) {

                            //Estraggo il risultato come un array associativo
                            $row_r = $ris_r->fetch_assoc();
                            $username_r = $row_r['username']; 
                        }
                        else{
                            exit(1);
                        }

                        $parti_r = explode("T", $risposta['dataRisp']);

                        $data_r = $parti_r[0];
                        $ora_r = $parti_r[1];

                        echo "<div class=\"risposta\">";

                            echo"<div class=\"info-risposta\">";

                                echo "<p class=\"utente\">$username_r</p>";
                                echo "<p class=\"data\">" . $data_r . " ". $ora_r . "</p>";
                            
                            echo "</div>";
                            
                            echo "<p class=\"testo-risposta\">" . $risposta['testoRisp'] . "</p>";

                            if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){

                                //mi devo prendere il nome utente corrispettivo del valutante
                                $query_v = "SELECT umn.id FROM utenteMangaNett umn WHERE umn.username = '{$_SESSION['nome']}'";

                                $ris_v = $connessione->query($query_v);

                                //Verifico se la query ha restituito risultati
                                if ($ris_v) {

                                    //Estraggo il risultato come un array associativo
                                    $row_v = $ris_v->fetch_assoc();
                                    $id_valutante = $row_v['id']; 
                                }
                                else{
                                    exit(1);
                                }
                                
                                $ha_votato = false;

                                if (isset($risposta['votazioni'])) {

                                    foreach ($risposta['votazioni'] as $votazione) {

                                        if ($votazione['IDValutante'] == $id_valutante) {
                                            $ha_votato = true;
                                            break;
                                        }
                                    }
                                }

                                //se l'utente ha già votato allora devo impedirgli di votare ancora
                                if($ha_votato){
                                    echo "<p id=\"ha_votato\">HAI GIÀ VOTATO QUESTO CONTRIBUTO... ¯\_(ツ)_/¯</p>";
                                }

                                else{
                                    //form valutazione UTILITÀ e SUPPORTO
                                    echo "<form id=\"valutazioneForm\" action=\"res/PHP/aggiungi_valutazione_specifica.php\" method=\"POST\">";

                                        echo "<div class=\"form-row\">";
                                            echo "<label for=\"utilita\">UTILITÀ:</label>";
                                            echo "<select name=\"utilita\" id=\"utilita\">";
                                        
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo "<option value=\"$i\">$i</option>";
                                                }

                                            echo "</select>";
                                        echo "</div>";

                                        echo "<div class=\"form-row\">";

                                            echo "<label for=\"supporto\">SUPPORTO:</label>";
                                            echo "<select name=\"supporto\" id=\"supporto\">";

                                                for ($i = 1; $i <= 3; $i++) {
                                                    echo "<option value=\"$i\">$i</option>";
                                                }
                                                
                                            echo "</select>";
                                            
                                            //mi invio l'id del valutante
                                            echo"<input type=\"hidden\" name=\"IDValutante\" value=".  $id_valutante .">";

                                            //mi invio l'id di chi ha fatto la risposta per fare dei controlli
                                            echo"<input type=\"hidden\" name=\"IDRisp\" value=". $risposta['IDRisp'] .">";
                                            
                                            //mi invio la data del rispondente per fare dei controlli
                                            echo"<input type=\"hidden\" name=\"dataRisp\" value=". $risposta['dataRisp'] .">";

                                        echo "</div>";
                                        
                                        echo "<span class=\"bottone\"><input type=\"submit\" value=\"INVIA\"></span>";

                                    echo "</form>";

                                    //faccio questo controllo perché solo l'admin può elevare a FAQ una domanda/risposta
                                    $sql_am = "SELECT u.ruolo FROM utentemanganett u WHERE u.username = '{$_SESSION['nome']}' AND u.ruolo = 'AM'";
                                    $ris_am = mysqli_query($connessione, $sql_am);

                                    if(mysqli_num_rows($ris_am) == 1){

                                        echo"<form id=\"rispostaForm\" action = \"res/PHP/eleva_a_rispostaFAQ.php\" method=\"POST\" >";

                                            //mi invio la data della risposta
                                            echo"<input type=\"hidden\" name=\"dataRisp\" value=". $risposta['dataRisp'] . ">";

                                            //mi invio l'id della risposta
                                            echo"<input type=\"hidden\" name=\"IDRisp\" value=". $risposta['IDRisp'] . ">";
                                            echo "<span class =\"bottone\"><input type=\"submit\" value=\"ELEVA RISP.\"></span>";
                                        
                                        echo "</form>";
                                    }

                                }
                            }
                            else{
                                echo"<p id=\"new_question\"><a href=\"login.php\">LOGGATI PER VALUTARE LA RISPOSTA!</a></p>";
                            }

                            echo"<form id=\"bottoniForm\" action = \"res/PHP/segnala_contributo.php?from=risposta\" method=\"POST\" >";

                                // //mi invio la  data della domanda
                                // echo"<input type=\"hidden\" name=\"dataDom\" value=". $domanda['dataDom'] . ">";

                                // //mi invio l'id della domanda
                                // echo"<input type=\"hidden\" name=\"IDDom\" value=". $domanda['IDDom'] . ">";
                                echo "<span class =\"bottone\"><input type=\"submit\" value=\"SEGNALA\"></span>";
                            
                            echo "</form>";
                            
                        echo "</div>";    
                    }
                
                echo "</div>";
            }
        }
        
    ?>
</body>

<?php include('res/PHP/footer.php'); ?>

</html>
