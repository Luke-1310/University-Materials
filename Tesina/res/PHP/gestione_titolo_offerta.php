<?php

session_start();

include('connection.php');
include('funzioni.php');

if(isset($_GET['titolo'])) {

    $titolo_corrente = $_GET['titolo'];

    $pathXml = "../XML/catalogo.xml";
    $fumetti = getFumetti($pathXml);

    foreach($fumetti as $fumetto){

        if($fumetto['titolo'] == $titolo_corrente){

            $_SESSION['titolo_selezionato'] = $titolo_corrente;

            //bene sono nel fumetto corretto, ora quello che devo fare è attivare le variabili di sessione
            $_SESSION['form_off_X'] = $fumetto['X'];
            $_SESSION['form_off_Y'] = $fumetto['Y'];
            $_SESSION['form_off_M'] = $fumetto['M'];
            $_SESSION['form_off_data_M'] = $fumetto['data_M'];
            $_SESSION['form_off_N'] = $fumetto['N'];
            $_SESSION['form_off_R'] = $fumetto['R'];
            $_SESSION['form_off_ha_acquistato'] = $fumetto['ha_acquistato'];

            $_SESSION['form_off_generico'] = $fumetto['sconto_generico'];
            $_SESSION['form_off_bonus'] = $fumetto['bonus'];

            header('Location:../../modifica_offerta.php');
        }
    }

} 
else {
    // Gestione degli errori nel caso il titolo non sia ricevuto correttamente
    
}

?>