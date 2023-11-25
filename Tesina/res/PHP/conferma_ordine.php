<?php

session_start();

$importoDaPagare = $_POST['daPagare'];
$bonusDaAccreditare = $_POST['totaleBonus'];

//ora bisogna fare dei controlli ed, eventualemente confermare l'ordine



$_SESSION['prodotto_aggiunto'] = true;

header('Location: ../../catalogo.php');
?>