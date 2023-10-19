<?php
    session_start();

    if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_register_dark.css\" type=\"text/css\" />";
    }
    else{
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_register.css\" type=\"text/css\" />";
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
    <title>Registrati...</title>
</head>

<?php 
    $pagina_corrente = "register";
    include('res/PHP/navbar.php')
?>

<body>

    <div class="container_reg">

        <form id="loginForm" action = "res/PHP/register.php" method="POST">

            <?php
                if(isset($_SESSION['errore_e']) && $_SESSION['errore_e'] == 'true'){//isset verifica se errore è settata
                    echo "<h4>EMAIL GIÀ ESISTENTE!</h4>";
                    unset($_SESSION['errore_e']);//la unsetto altrimenti rimarrebbe la scritta
                    unset($_SESSION['form_mail']);//pulisco il form del campo email perché è errato
                }

                if(isset($_SESSION['errore_preg']) && $_SESSION['errore_preg'] == 'true'){
                    echo "<h4>LA PASSWORD NON RISPETTA I CRITERI DI SICUREZZA!</h4>";
                    unset($_SESSION['errore_preg']);
                }
            
                if(isset($_SESSION['errore_p']) && $_SESSION['errore_p'] == 'true'){
                    echo "<h4>LE PASSWORD NON SONO UGUALI!</h4>";
                    unset($_SESSION['errore_p']);
                }

                if(isset($_SESSION['errore_t']) && $_SESSION['errore_t'] == 'true'){
                    echo "<h4>IL NUMERO DI TELEFONO È GIÀ ESISTENTE!</h4>";
                    unset($_SESSION['errore_t']);
                    unset($_SESSION['form_telefono']);
                }

                if(isset($_SESSION['errore_ur']) && $_SESSION['errore_ur'] == 'true'){
                    echo "<h4>USERNAME GIÀ ESISTENTE!</h4>";
                    unset($_SESSION['errore_ur']);
                    unset($_SESSION['form_username']);
                }

                if(isset($_SESSION['errore_utDati']) && $_SESSION['errore_utDati'] == 'true'){
                    echo "<h4>ERRORE DURANTE LA REGISTRAZIONE!</h4>";
                    unset($_SESSION['errore_utDati']);
                }
            ?>

            <div class="titoletto">

                <div class="tooltip">
                    <span class="tooltiptext">SONO CONSENTITI SOLO NUMERI NEL CAMPO "NUMERO DI TELEFONO"</span>
                    <i id="info" class="material-icons">info</i>
                </div>

                <h2>DATI ANAGRAFICI:</h2>
                
            </div>

            <div class="form-row">
                <label for="nome">NOME</label>
                <input type="text" id="nome" name="nome" placeholder="Mario" 
                       value="<?php  if(isset($_SESSION['form_nome'])) echo $_SESSION['form_nome']; ?>" required>   <!--Se settata (regist non corretta, la stampo)-->
                
                <label for="cognome">COGNOME</label>
                <input type="text" id="cognome" name="cognome" placeholder="Rossi"
                       value="<?php  if(isset($_SESSION['form_cognome'])) echo $_SESSION['form_cognome']; ?>" required>
            </div>

            <div class="form-row">
                <label for="email">EMAIL</label>
                <input type="email" name="email" id="email" placeholder="example@email.com"
                       value="<?php  if(isset($_SESSION['form_email'])) echo $_SESSION['form_email']; ?>" required>

                <label for="telefono">NUMERO DI TELEFONO</label>
                <input type="text" name="telefono" id="telefono" placeholder="1234567890" maxlength="10" pattern="[0-9]{10}"
                       value="<?php  if(isset($_SESSION['form_telefono'])) echo $_SESSION['form_telefono']; ?>" required>
            </div>
            
            <h2>RESIDENZA:</h2>
            <div class="form-row">
                <label for="residenza">VIA DI RESIDENZA</label>
                <input type="text" name="residenza" id="residenza" placeholder="Andrea Doria"
                       value="<?php  if(isset($_SESSION['form_residenza'])) echo $_SESSION['form_residenza']; ?>" required>

                <label for="civico">NUMERO CIVICO</label>
                <input type="text" name="civico" id="civico" placeholder="3" maxlength="4"
                       value="<?php  if(isset($_SESSION['form_civico'])) echo $_SESSION['form_civico']; ?>" required>
            </div>
            
            <div class="titoletto">

                <div class="tooltip">
                    <span class="tooltiptext">LA PASSWORD DEVE SODDISFARE I SEGUENTI REQUISITI:
                        <ul>
                            <li>DEVE ESSERE LUNGA ALMENO 8 CARATTERI.</li>
                            <li>DEVE CONTENERE ALMENO UNA LETTERA MAIUSCOLA E UNA MINUSCOLA</li>
                            <li>DEVE CONTENERE ALMENO UN NUMERO.</li>
                            <li>DEVE CONTENERE ALMENO UN SIMBOLO SPECIALE.</li>
                        </ul>       
                    </span>
                    <i id="info" class="material-icons">info</i>
                </div>

                <h2>CREDENZIALI DI ACCESSO:</h2>
                
            </div>

            <div class="form-row">
                <label for="username">USERNAME</label>
                <input type="username" name="username" id="username" placeholder="MarioBros" 
                       value="<?php  if(isset($_SESSION['form_username'])) echo $_SESSION['form_username']; ?>" required>
            </div>

            <div class="form-row">
                <label for="password">PASSWORD</label>
                <input type="password" name="password" id="password" placeholder="Password123!" required>
            
                <label for="password">CONFERMA PASSWORD</label>
                <input type="password" name="password2" id="password2" placeholder="Password123!" required>
            </div>

            <span class ="bottone"><input type="submit" value="INVIA"></span>
            
            <div class="login-text">
                <a href="login.php">FAI IL LOGIN</a>
            </div>
        </form>
    </div>

</body>
    
<?php include('res/PHP/footer.php')?>

</html>