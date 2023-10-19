<?php

session_start();

include("connection.php");
include("funzioni.php");

$reputazione = $_POST['reputazione'];
$id = $_POST['id'];

//ora dovrei prendere tutte le risposte di questo utente ed applicare la formula di ciascun voto
$xmlpath="../XML/Q&A.xml";

$domande = getDomande($xmlpath);

$temp_num = 0;  //Qui ci metto i calcoli da fare per il numeratore della formula
$temp_den = 0;  //Qui ci metto i calcoli da fare per il denominatore della formula
$temp_norm = 10/8; //Qui ci metto la costante per effettuare la normalizzazione

$reputazione_def = 0;

foreach ($domande as $domanda) {

    foreach ($domanda['risposte'] as $risposta) {

        if ($risposta['IDRisp'] == $id) {

            foreach ($risposta['votazioni'] as $votazione) {

                // Calcola la reputazione per questa risposta e la aggiunge al totale
                $temp_num += ($votazione['supporto'] + $votazione['utilita']) * $reputazione;

                $temp_den = $temp_den + $reputazione;
            }
        }
    }
}

$reputazione_def = ($temp_norm * $temp_num)/$temp_den; //calcolo finale per la reputazione

//faccio un controllo, se la reputazione supera 10 allora deve essere arrotondato a 10
if($reputazione_def > 10){
    $reputazione_def = 10;
}

$reputazione_difetto_def = floor($reputazione_def); //arrotonda per difetto

// echo $reputazione_difetto_def;
//una volta calcolata devo aggiornare il campo corrispettivo nel DB

$connessione = new mysqli($host, $user, $password, $db);

$query = "UPDATE utenteMangaNett 
                   SET reputazione = '$reputazione_difetto_def'
                        WHERE id IN (SELECT id FROM utenteMangaNett WHERE username = '{$_SESSION['nome']}')";

$result = $connessione->query($query);

//Verifico se la query ha restituito risultati
if (!$result) {
    echo "Errore nella query: " . $connessione->error;
    exit(1);
}

header('Location: ../../reputazione.php');
    
?>