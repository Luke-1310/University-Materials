<?php

session_start();

$fumetto_isbn_POST = $_POST['isbn'];

if (!empty($_SESSION['carrello'])) {

    foreach ($_SESSION['carrello'] as $indice => $fumetto_carrello) {
        
        if ($fumetto_carrello['isbn'] == $fumetto_isbn_POST) {
            unset($_SESSION['carrello'][$indice]);
        }
    }
}

$_SESSION['rimuovi_prodotto_carrello_ok'] = true;

header('Location:../../carrello.php');

?>