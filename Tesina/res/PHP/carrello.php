<?php

session_start();

$fumetto_isbn_POST = $_POST['isbn'];
$fumetto_prezzo_POST = $_POST['prezzo'];
$fumetto_bonus_POST = $_POST['bonus'];
$isPresente = false;

//controllo se è già stato aggiunto, in modo tale da non aggiungere un nuovo elemento ma aggiorno solo la quantità
//$indice risulta essenziale per evitare che venga manipolata la copia dell'array e non quello originale
if (!empty($_SESSION['carrello'])) {

    foreach ($_SESSION['carrello'] as $indice => $fumetto_carrello) {
        
        if ($fumetto_carrello['isbn'] == $fumetto_isbn_POST) {

            $isPresente = true;
            
            if($_SESSION['carrello'][$indice]['quantita'] < 10){
                $_SESSION['carrello'][$indice]['quantita'] += 1;
                break;
            }
        }
    }
}


if(!$isPresente){

    //creo un array con i dati del POST, faccio perché voglio salvare diverse informazioni del fumetto
    $fumetto = array(
        'isbn' => $fumetto_isbn_POST,
        'prezzo' => $fumetto_prezzo_POST,
        'bonus' => $fumetto_bonus_POST,
        'quantita' => 1
    );

    //il [] alla fine serve per aggiungere l'elemento alla coda
    $_SESSION['carrello'][] = $fumetto;
}

$_SESSION['prodotto_aggiunto'] = true;

header('Location: ../../catalogo.php');
?>