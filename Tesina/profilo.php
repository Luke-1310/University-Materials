<?php
   session_start();

   require_once('res/PHP/connection.php');
    
   $connessione = new mysqli($host, $user, $password, $db);

   if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_profilo_dark.css\" type=\"text/css\" />";
   }
   else{
       echo "<link rel=\"stylesheet\" href=\"res/CSS/external_profilo.css\" type=\"text/css\" />";
   }
?>
<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- titolo della scheda -->
<head>
    <title>Profilo</title>
</head>
    
<?php 
    $pagina_corrente = "profilo";
    include('res/PHP/navbar.php')
?>

<body>

<?php

    //mi prendo i valori da stampare tramite l'username, il quale è univoco
    $query = "SELECT ud.* FROM utenteDati ud INNER JOIN utenteMangaNett umn ON ud.id = umn.id WHERE umn.username = '{$_SESSION['nome']}'";

    $result = $connessione->query($query);

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    // Ora puoi utilizzare i dati ottenuti da $row
    $nome = $row['nome'];
    $cognome = $row['cognome'];
    $password = $row['password'];
    $email = $row['email'];
    $via_di_residenza = $row['via_di_residenza'];
    $civico = $row['civico'];
    $numero_di_telefono = $row['numero_di_telefono'];
    }
else{
    $_SESSION['errore_profilo'] = 'true';
}
?>


<div class="container-external">
    <?php
        if(isset($_SESSION['errore_profilo']) && $_SESSION['errore_profilo'] == 'true'){//isset verifica se errore è settata
            echo "<h3>SI È VERIFICATO UN ERRORE!</h3>";
            unset($_SESSION['errore_profilo']);//la unsetto altrimenti rimarrebbe la scritta
        }
    ?>
    <div class="container">

        <h1>DATI ANAGRAFICI</h1>
        <div class="info-field">
            <span class="field-label">NOME:</span>
            <span class="field-value"><?php echo $nome;?></span>
        </div>

        <div class="info-field">
            <span class="field-label">COGNOME:</span>
            <span class="field-value"><?php echo $cognome;?></span>
        </div>
        
        <div class="info-field">
            <span class="field-label">EMAIL:</span>
            <span class="field-value"><?php echo $email;?></span>
        </div>

        <div class="info-field">
            <span class="field-label">NUMERO DI TELEFONO:</span>
            <span class="field-value"><?php echo $numero_di_telefono;?></span>
        </div>

        <h1>RESIDENZA</h1>
        <div class="info-field">
            <span class="field-label">VIA:</span>
            <span class="field-value"><?php echo $via_di_residenza;?></span>
        </div>
        
        <div class="info-field">
            <span class="field-label">CIVICO:</span>
            <span class="field-value"><?php echo $civico;?></span>
        </div>
        
        <h1>CREDENZIALI SITO</h1>
        <div class="info-field">
            <span class="field-label">USERNAME:</span>
            <span class="field-value"><?php echo $_SESSION['nome'];?></span>
        </div>

    </div>

    <p>PER MODIFICARE I TUOI DATI CLICCA <a href="inserisci_password.php">QUI</a></p>

</div>

</body>

<?php include('res/PHP/footer.php')?>

</html>