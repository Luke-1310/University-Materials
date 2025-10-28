<?php

    session_start();

    $fumetto_isbn_POST = $_POST['isbn'];

    if (isset($_POST['bottone_aumenta'])) {

        foreach ($_SESSION['carrello'] as $indice => $fumetto_carrello) {
        
            if ($fumetto_carrello['isbn'] == $fumetto_isbn_POST) {

                if($_SESSION['carrello'][$indice]['quantita'] < 10){

                    $_SESSION['carrello'][$indice]['quantita'] += 1;
                    break;
                }
            }
        }
    } 
    
    else if (isset($_POST['bottone_decrementa'])) {

        foreach ($_SESSION['carrello'] as $indice => $fumetto_carrello) {
        
            if ($fumetto_carrello['isbn'] == $fumetto_isbn_POST) {

                if($_SESSION['carrello'][$indice]['quantita'] > 1){

                    $_SESSION['carrello'][$indice]['quantita'] -= 1;
                    break;
                }
            }
                    
        }
        
    }

    header('Location:../../carrello.php');

?>