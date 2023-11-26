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
            
            // Verifica se è l'ultimo elemento rimasto dopo la rimozione
            if (count($_SESSION['carrello']) === 0) {
                unset($_SESSION['carrello']); // Rimuovi completamente la variabile di sessione 'carrello'
            }
        }
    }
}

$_SESSION['rimuovi_prodotto_carrello_ok'] = true;

header('Location: ../../carrello.php');
?>