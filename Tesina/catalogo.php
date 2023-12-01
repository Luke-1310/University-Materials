<?php
    session_start();

    if (isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro") {
        echo '<link rel="stylesheet" href="res/CSS/external_cat_dark.css" type="text/css" />';
    } else {
        echo '<link rel="stylesheet" href="res/CSS/external_cat.css" type="text/css" />';
    }
?>
<?php 
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Catalogo</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- libreria di javascript -->
    <?php 
        $pagina_corrente = "catalogo";
        include('res/PHP/navbar.php');
    ?>
</head>

<body>

<?php

    if(isset($_SESSION['prodotto_aggiunto']) && $_SESSION['prodotto_aggiunto'] = true){
        echo "<h4 id=\"esito_positivo\">IL PRODOTTO È STATO AGGIUNTO AL CARRELLO!</h4>";
        unset($_SESSION['prodotto_aggiunto']);
    }
    if(isset($_SESSION['prodotto_eliminato']) && $_SESSION['prodotto_eliminato'] = true){
        echo "<h4 id=\"esito_positivo\">IL PRODOTTO È STATO ELIMINATO!</h4>";
        unset($_SESSION['prodotto_eliminato']);
    }
    
    //includo il file funzioni.php contenente la funzione per caricare i fumetti dal file xml
    include('res/PHP/funzioni.php');

    // Percorso del file XML
    $xmlFile = "res/XML/catalogo.xml";

    //Assegno a $fumetti il risultato della funzione getFumetti
    $fumetti = getFumetti($xmlFile);

    // Ordina i fumetti in base alla selezione dell'utente
    /*Il funzionamento è il seguente: prima di tutto controlla se nella richiesta GET è
    presente un parametro di nome 'ordina', se si allora si avrà come risultato true e ne restituirà il valore,
    altrimenti darà come risultato false ed il catalogo verrà ordinato in base al titolo di default*/ 
    $ordinamento = isset($_GET['ordina']) ? $_GET['ordina'] : 'titolo';

    //schiera di if per capire come stampare il catalogo
    if ($ordinamento === 'titolo') {
        usort($fumetti, function ($a, $b) {                 //usort mi ordina i fumetti nell'array in base al titolo
            return strcmp($a['titolo'], $b['titolo']);      //strcmp mi compara le due stringhe e mi fornisce un valore negativo/postivo se la
        });                                                 //prima stringa è minore/maggiore della seconda, zero se sono uguali
    } 
    
    elseif ($ordinamento === 'prezzo_cr') {
        usort($fumetti, function ($a, $b) {
            // Confronta i prezzi come stringhe per gestire i decimali con la virgola
            return strcmp($a['prezzo'], $b['prezzo']);
        });
    }
    
    elseif ($ordinamento === 'prezzo_dr') {
        usort($fumetti, function ($a, $b) {
            // Confronta i prezzi come stringhe per gestire i decimali con la virgola
            return strcmp($b['prezzo'], $a['prezzo']);
        });
    }

    elseif ($ordinamento === 'data_old') {
        usort($fumetti, function ($a, $b) {
            //creo un formato in modo tale che l'ordinamento segua giorno/mese/anno
            $dataA = DateTime::createFromFormat('Y-m-d', $a['data']);
            $dataB = DateTime::createFromFormat('Y-m-d', $b['data']);
    
            if ($dataA === false || $dataB === false) {
                //Caso in cui il formato della data non è valido
                return 0;
            }
    
            return $dataA->getTimestamp() - $dataB->getTimestamp();
        });
    } 
    
    elseif ($ordinamento === 'data_new') {
        usort($fumetti, function ($a, $b) {
            //creo un formato in modo tale che l'ordinamento segua giorno/mese/anno
            $dataA = DateTime::createFromFormat('Y-m-d', $a['data']);
            $dataB = DateTime::createFromFormat('Y-m-d', $b['data']);
    
            if ($dataA === false || $dataB === false) {
                //Caso in cui il formato della data non è valido
                return 0;
            }
    
            return $dataB->getTimestamp() - $dataA->getTimestamp() ;
        });
    } 

    elseif ($ordinamento === 'publisher') {
        usort($fumetti, function ($a, $b) {
            return strcmp($a['editore'], $b['editore']);
        });
    }

    if(isset($_SESSION['nuovoprodotto_ok']) && $_SESSION['nuovoprodotto_ok'] == 'true'){//isset verifica se errore è settata

        echo "<div class=\"nuovoprodotto\">";
            echo "<h3>IL PRODOTTO È STATO AGGIUNTO CORRETTAMENTE!</h3>";
            unset($_SESSION['nuovoprodotto_ok']);
        echo "</div>";
    }

    echo "<div class=\"search-bar\">";
        echo "<input type=\"text\" class=\"search-input\" placeholder=\"Cerca il titolo...\">";
        echo "<button class=\"search-button\"><i id=\"search\" class=\"material-icons\">search</i></button>";
        
        //menù per ordinare i prodotti del catalogo in diversi ordini
        echo "<div class=\"opzioni-ordinamento\">";

            echo "<select id=\"ordina\" name=\"ordina\">";
            
            //possibili ordinamenti
            echo "<option value=\"titolo\"";
            if ($ordinamento === 'titolo') {
                echo " selected";
            }
            echo ">Titolo</option>";
            
            echo "<option value=\"prezzo_dr\"";
            if ($ordinamento === 'prezzo_dr') {
                echo " selected";
            }
            echo ">Prezzo decrescente</option>";

            echo "<option value=\"prezzo_cr\"";
            if ($ordinamento === 'prezzo_cr') {
                echo " selected";
            }
            echo ">Prezzo crescente</option>";

            echo "<option value=\"data_old\"";
            if ($ordinamento === 'data_old') {
                echo " selected";
            }
            echo ">Data di uscita (non recente)</option>";

            echo "<option value=\"data_new\"";
            if ($ordinamento === 'data_new') {
                echo " selected";
            }
            echo ">Data di uscita (recente)</option>";

            echo "<option value=\"publisher\"";
            if ($ordinamento === 'publisher') {
                echo " selected";
            }
            echo ">Casa editrice</option>";
            echo "</select>";

            echo "<button class=\"filter-button\"><i id=\"filter\" class=\"material-icons\">filter_alt</i></button>";

        echo "</div>";

    echo "</div>";

    echo "<div class=\"container\">";

        foreach ($fumetti as $fumetto) {
            // Estrai i dati del fumetto
            $ISBN = $fumetto['isbn'];
            $titolo = $fumetto['titolo'];
            $prezzo = $fumetto['prezzo'];
    
            echo "<div class=\"cell\">";

                // Estensione dell'immagine (dovrebbe essere jpg)
                $ext = ".jpg";

                // Componi il nome completo dell'immagine da stampare
                $nomeImg = $ISBN . $ext;

                // Percorso dell'immagine
                $pathImg = "res/WEBSITE_MEDIA/PRODUCT_MEDIA/";

                echo "<img src='" . $pathImg . $nomeImg . "' alt=\"Copertina.jpg\" >";

                echo "<form id=\"prod_info\" action=\"prodotti_info.php\" method=\"POST\">";
                echo "<span class=\"bottone\"><h5><input type=\"submit\" name=\"titolo\" value=\"$titolo\"></h5></span>";
                echo "</form>";

                echo $prezzo . " CR";

                if($fumetto['quantita'] != 0){

                    echo "<form action=\"res/PHP/carrello.php\" method=\"POST\">";
                        echo "<input type=\"hidden\" name=\"titolo\" value='" . $fumetto['titolo'] . "'>";
                        echo "<input type=\"hidden\" name=\"isbn\" value='" . $fumetto['isbn'] . "'>";
                        echo "<input type=\"hidden\" name=\"prezzo\" value='" . $fumetto['prezzo'] . "'>";
                        echo "<input type=\"hidden\" name=\"bonus\" value='" . $fumetto['bonus'] . "'>";
                        echo "<span class=\"carrello\"><h5><input type=\"submit\" name=\"Disponibile\" value=\"AGGIUNGI AL CARRELLO\"></h5></span>";
                    echo "</form>";
                }
                else{
                    echo "<span class=\"carrello\"><h5><input type=\"button\" name=\"nonDisponibile\" value=\"NON DISPONIBILE\"></h5></span>";
                }

            echo "</div>";
        }

    echo "</div>";
?>


<script>
    //quando il documento è caricato
    $(document).ready(function() {

        //associo un'azione al bottone di ricerca "search-button", in questo caso quando ci clicchi
        $('.search-button').on('click', function() {

            //prendo il testo inserito nella barra di ricerca e lo converto in minuscolo -> rendo la ricerca case insensitive
            var searchText = $('.search-input').val().toLowerCase();

            //per ogni elemento "cell", dove si trova un libro si fa:
            $('.cell').each(function() {
                
                //prendo il titolo del libro (dentro cell) e lo converto in minuscolo
                var titolo = $(this).find('.bottone input[name="titolo"]').val().toLowerCase();

                //controllo se nel titolo del libro c'è ciò che ho scritto nella barra
                if (titolo.indexOf(searchText) !== -1) {
                    //lo mostro
                    $(this).show();
                } else {
                    //lo nascondo
                    $(this).hide();
                }
            });
        });

        //associo un'azione al bottone di ricerca "search-button", in questo caso quando scrivo qualcosa sulla tastiera
        $('.search-input').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();

            $('.cell').each(function() {
                var titolo = $(this).find('.bottone input[name="titolo"]').val().toLowerCase();

                if (titolo.indexOf(searchText) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });

    //ordino i titoli del catalogo in base alla richiesta
    $(document).ready(function() {

        // Quando si clicca sul pulsante "Ordina"
        $('#filter').on('click', function() {
            // Leggi il valore selezionato nel menu a discesa
            var selectedOption = $('#ordina').val();

            // Effettua una richiesta GET per ricaricare la pagina con il parametro "ordina"
            window.location.href = 'catalogo.php?ordina=' + selectedOption;
        });
    });
</script>

</body>

<?php include('res/PHP/footer.php'); ?>

</html>
