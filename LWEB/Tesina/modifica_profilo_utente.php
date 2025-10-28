<?php

session_start();

include('res/PHP/connection.php');

$connessione = new mysqli($host, $user, $password, $db);

if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
    echo "<link rel=\"stylesheet\" href=\"res/CSS/external_mod_profile_dark.css\" type=\"text/css\" />";
}
else{
    echo "<link rel=\"stylesheet\" href=\"res/CSS/external_mod_profile.css\" type=\"text/css\" />";
}

if (isset($_GET['nome_utente'])) {

    $nick_utente = $_GET['nome_utente'];
} 

//metto il nick utente dentro una variabile di sessione perché se cambio il tema nella pagina delle info mi da errore (giustamente)
if (!empty($nick_utente)) {

    $_SESSION['nome_utente'] = $nick_utente;
}

//ho incluso la connessione al db perché adesso mi prendo i valori dell'utente e gli compilo il form
$query = "SELECT ud.* FROM utenteDati ud INNER JOIN utenteMangaNett umn ON ud.id = umn.id WHERE umn.username = '{$_SESSION['nome_utente']}'";

$result = $connessione->query($query);

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    // Ora puoi utilizzare i dati ottenuti da $row
    $_SESSION['mod_nome'] = $row['nome'];
    $_SESSION['mod_cognome'] = $row['cognome'];
    $_SESSION['mod_email'] = $row['email'];
    $_SESSION['mod_residenza'] = $row['via_di_residenza'];
    $_SESSION['mod_civico'] = $row['civico'];
    $_SESSION['mod_telefono'] = $row['numero_di_telefono'];
}

//ho incluso la connessione al db perché adesso mi prendo i valori dell'utente e gli compilo il form (per la password c'è da fare qualcosina in più)
$query_umn = "SELECT umn.* FROM utenteDati ud INNER JOIN utenteMangaNett umn ON ud.id = umn.id WHERE umn.username = '{$_SESSION['nome_utente']}'";

$result_umn = $connessione->query($query_umn);

if ($result_umn->num_rows > 0) {

    $row = $result_umn->fetch_assoc();

    // Ora puoi utilizzare i dati ottenuti da $row
    $_SESSION['mod_username'] = $row['username'];
}
?>

<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<head>
    <title>Modifica i dati dell'utente</title>
</head>

<?php 
    $pagina_corrente = "modifica_profilo_utente";
    include('res/PHP/navbar.php')
?>

<body>
    <?php
        if(isset($_SESSION['errore_query']) && $_SESSION['errore_query'] == 'true'){
            echo "<h3>ERRORE DURANTE LA RICHIESTA!</h3>";
            unset($_SESSION['errore_query']);
        }
    
        if(isset($_SESSION['errore_email_ex']) && $_SESSION['errore_email_ex'] == 'true'){//isset verifica se errore è settata
            echo "<h3>EMAIL GIÀ IN USO!</h3>";
            unset($_SESSION['errore_email_ex']);//la unsetto altrimenti rimarrebbe la scritta
            unset($_SESSION['mod_mail']);//pulisco il form del campo email perché è errato
        }
    
        if(isset($_SESSION['errore_tel_ex']) && $_SESSION['errore_tel_ex'] == 'true'){
            echo "<h3>IL NUMERO DI TELEFONO GIÀ IN USO!</h3>";
            unset($_SESSION['errore_tel_ex']);
            unset($_SESSION['mod_telefono']);
        }
        
        if(isset($_SESSION['errore_usr_ex']) && $_SESSION['errore_usr_ex'] == 'true'){
            echo "<h3>USERNAME GIÀ IN USO !</h3>";
            unset($_SESSION['errore_usr_ex']);
            unset($_SESSION['mod_username']);
        }

    ?>

    <div class="container_reg">

        <form id="loginForm" action = "res/PHP/modifica_profilo_utente.php" method="POST">
            
            <h2>DATI ANAGRAFICI:</h2>
            <div class="form-row">
                <label for="nome">NOME</label>
                <input type="text" id="nome" name="nome" placeholder="Mario" 
                       value="<?php  if(isset($_SESSION['mod_nome'])) echo $_SESSION['mod_nome']; ?>" required>
                
                <label for="cognome">COGNOME</label>
                <input type="text" id="cognome" name="cognome" placeholder="Rossi"
                       value="<?php  if(isset($_SESSION['mod_cognome'])) echo $_SESSION['mod_cognome']; ?>" required>
            </div>

            <div class="form-row">
                <label for="email">EMAIL</label>
                <input type="email" name="email" id="email" placeholder="example@email.com"
                       value="<?php  if(isset($_SESSION['mod_email'])) echo $_SESSION['mod_email']; ?>" required>

                <label for="telefono">NUMERO DI TELEFONO</label>
                <input type="text" name="telefono" id="telefono" placeholder="1234567890" maxlength="10" pattern="[0-9]{10}"
                       value="<?php  if(isset($_SESSION['mod_telefono'])) echo $_SESSION['mod_telefono']; ?>" required>
            </div>
            
            <h2>RESIDENZA:</h2>
            <div class="form-row">
                <label for="residenza">VIA DI RESIDENZA</label>
                <input type="text" name="residenza" id="residenza" placeholder="Andrea Doria"
                       value="<?php  if(isset($_SESSION['mod_residenza'])) echo $_SESSION['mod_residenza']; ?>" required>

                <label for="civico">NUMERO CIVICO</label>
                <input type="text" name="civico" id="civico" placeholder="3" maxlength="4"
                       value="<?php  if(isset($_SESSION['mod_civico'])) echo $_SESSION['mod_civico']; ?>" required>
            </div>

            <h2>CRENDZIALI SITO:</h2>
            <div class="form-row">
                <label for="username">USERNAME</label>
                <input type="text" name="username" id="username" placeholder="MarioBros"
                       value="<?php  if(isset($_SESSION['mod_username'])) echo $_SESSION['mod_username']; ?>" required>
            </div>

            <span class ="bottone"><input type="submit" value="INVIA"></span>
            
        </form>
    </div>

</body>
    
<?php include('res/PHP/footer.php')?>

</html>