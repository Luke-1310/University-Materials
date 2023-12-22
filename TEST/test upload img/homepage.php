<p>
  Attenzione, il nome del file deve seguire il formato seguente: "il-fotocane" o ancora "spy-x-family-1" </br></br>
  Sostanzialmente tra una parola e l'altra, le quali compongono il titolo del libro, bisogna mettere un trattino alto ed inoltre tutto deve essere in minuscolo
</p>

<form action="upload.php" method="POST" enctype="multipart/form-data">
  <input type="file" name="image" accept="image/*">
  <input type="submit" value="Carica immagine">
</form>

<?php

  $nome = $_SESSION['nome'];

  echo "immagini/$nome";

?>

<style>
  /* Aggiungi stili personalizzati qui */
  form {
    margin-top: 20px;
  }

  input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
</style>
