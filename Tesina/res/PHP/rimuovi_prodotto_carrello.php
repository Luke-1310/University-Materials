<?php

session_start();

$fumetto_isbn_POST = $_POST['isbn'];

if (!empty($_SESSION['carrello'])) {

    $carrello = $_SESSION['carrello'];
    $keys = array_keys($carrello);
    $count = count($keys);
    
    for ($i = 0; $i < $count; $i++) {
        
        $indice = $keys[$i];
        $fumetto_carrello = $carrello[$indice];
        
        if ($fumetto_carrello['isbn'] == $fumetto_isbn_POST) {
            unset($_SESSION['carrello'][$indice]);
        }
    }
}

$_SESSION['rimuovi_prodotto_carrello_ok'] = true;

header('Location:../../carrello.php');

?>