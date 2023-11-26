<?php

session_start();

$fumetto_isbn_POST = $_POST['isbn'];

if (!empty($_SESSION['carrello'])) {
    foreach ($_SESSION['carrello'] as $indice => $fumetto_carrello) {
        if ($fumetto_carrello['isbn'] == $fumetto_isbn_POST) {
            unset($_SESSION['carrello'][$indice]);
        }
    }

    //array_values mi riorganizza gli indici dell'array per prevenire errori quando cancello un elemento che non è l'ultimo nel carrello 
    $_SESSION['carrello'] = array_values($_SESSION['carrello']);

    if (empty($_SESSION['carrello'])) {
        unset($_SESSION['carrello']); // Rimuovi completamente la variabile di sessione 'carrello'
    }
}

$_SESSION['rimuovi_prodotto_carrello_ok'] = true;

header('Location: ../../carrello.php');

?>