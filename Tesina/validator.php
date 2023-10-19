<?php
$xmlFile = 'res/XML/Q&A.xml';
$xsdFile = 'res/XML/Q&A.xsd';

// Load XML file
$xml = new DOMDocument();
$xml->load($xmlFile);

// Load XSD schema
$xsd = new DOMDocument();
$xsd->load($xsdFile);

// Validate XML against XSD
$isValid = $xml->schemaValidateSource($xsd->saveXML());

if ($isValid) {
    echo "XML is valid.";
} else {
    echo "XML is not valid.";
    libxml_display_errors();
}

function libxml_display_errors() {
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        echo "\nError: " . $error->message;
    }
    libxml_clear_errors();
}
?>
