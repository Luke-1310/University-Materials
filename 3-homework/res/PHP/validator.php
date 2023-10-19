<?php
// Percorso del file XML da validare
$xmlFile = '../XML/libri.xml';

// Percorso del file schema XSD
$xsdFile = '../XML/libro.xsd';

//Contatore di errori
$counter = 0;

// Crea un oggetto DOMDocument
$dom = new DOMDocument();

// Carica il file XML
$dom->load($xmlFile);

// Abilita la validazione dello schema
$dom->schemaValidate($xsdFile);

// Altrimenti, se la validazione fallisce, mostra gli eventuali errori
libxml_use_internal_errors(true);

$errors = libxml_get_errors();

foreach ($errors as $error) {
    echo "Errore di validazione: " . $error->message . "\n";
    $counter = $counter + 1;
}

if($counter>0){
    // Se la validazione ha successo, il documento XML è conforme allo schema
    echo "Il documento XML è valido secondo lo schema specificato.";
}


?>
