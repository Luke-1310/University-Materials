<?php
$xmlFile = 'biblioteca.xml';
$dtdFile = 'biblioteca.dtd';

// Load XML file
$xml = new DOMDocument();
$xml->load($xmlFile);

// Enable DTD validation
$xml->validateOnParse = true;

// Validate XML against DTD
if ($xml->validate()) {
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
