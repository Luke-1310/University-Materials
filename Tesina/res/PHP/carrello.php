<?php

session_start();

$fumetto_isbn_POST = $_POST['isbn'];
$isPresente = false;

//controllo se è già stato aggiunto, in modo tale da non aggiungere un nuovo elemento ma aggiorno solo la quantità
//$indice risulta essenziale per evitare che venga manipolata la copia dell'array e non quello originale
if (!empty($_SESSION['carrello'])) {

    foreach ($_SESSION['carrello'] as $indice => $fumetto_carrello) {
        
        if ($fumetto_carrello['isbn'] == $fumetto_isbn_POST) {

            if($_SESSION['carrello'][$indice]['quantita'] < 10){
                $_SESSION['carrello'][$indice]['quantita'] += 1;
                break;
            }

            $isPresente = true;
        }
    }
}


if(!$isPresente){

    //creo un array con i dati del POST, faccio perché voglio salvare diverse informazioni del fumetto
    $fumetto = array(
        'isbn' => $fumetto_isbn_POST,
        'quantita' => 1
    );

    //il [] alla fine serve per aggiungere l'elemento alla coda
    $_SESSION['carrello'][] = $fumetto;
}

$_SESSION['prodotto_aggiunto'] = true;

header('Location: ../../catalogo.php');
?>