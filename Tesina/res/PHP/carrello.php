<?php

session_start();

$fumetto_isbn_POST = $_POST['isbn'];
$isPresente = false;

//controllo se è già stato aggiunto, in modo tale da non aggiungere un nuovo elemento ma aggiorno solo la quantità
if(!empty($_SESSION['carrello'])){

    echo 0;

    foreach($_SESSION['carrello'] as $fumetto_carrello){

        echo $fumetto_carrello['isbn'];
        echo $fumetto_isbn_POST;

        if($fumetto_carrello['isbn'] == $fumetto_isbn_POST){
            $fumetto_carrello['quantita'] += 1;
            $isPresente = true;
            break;
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

// header('Location: ../../catalogo.php');
?>