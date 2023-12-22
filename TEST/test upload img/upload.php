<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
  // Verifica se è stato effettuato l'upload correttamente
  if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {

    $targetDir = "immagini/"; // La cartella in cui salvare l'immagine
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $targetName = $_FILES["image"]["name"]; 
 
    //questa riga di codice viene utilizzata per creare il percorso completo del file di destinazione, 
    //in base al percorso della cartella di destinazione specificato e al nome del file selezionato dall'utente durante il caricamento.
    
    // Verifica l'estensione del file per consentire solo immagini
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedExtensions = array("jpg", "jpeg", "png", "gif");

    if (in_array($imageFileType, $allowedExtensions)) {

      // Sposta il file dalla directory temporanea alla cartella di destinazione
      if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        header('Location:homepage.php');
      } 
      
      else {
        echo "Si è verificato un errore durante il caricamento dell'immagine.";
      }
    } 
    
    else {
      echo "Sono consentiti solo file di tipo JPG, JPEG, PNG e GIF.";
    }

  } 
  
  else {
    echo "Si è verificato un errore durante l'upload dell'immagine.";
  }
  
}
?>
