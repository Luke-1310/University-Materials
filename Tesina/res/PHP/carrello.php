<?php

session_start();

$fumetto_isbn = $_POST['isbn'];

// $fumetto_prezzo = $_POST['prezzo'];

// //creo un array con i dati del POST
// $fumetto = array(
//     'isbn' => $fumetto_isbn,
//     'prezzo' => $fumetto_prezzo
// );

//il [] alla fine serve per aggiungere l'elemento alla coda
$_SESSION['carrello'][] = $fumetto_isbn;

header('Location: ../../carrello.php');
?>