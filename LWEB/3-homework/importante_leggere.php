<?php
    session_start();

    if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_leg_dark.css\" type=\"text/css\" />";
    }
    else{
        echo "<link rel=\"stylesheet\" href=\"res/CSS/external_leg.css\" type=\"text/css\" />";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Perché è importante leggere... </title>
</head>

<body>
    <h1 class="titolo">PERCHÉ È IMPORTANTE LEGGERE?&#x1F914;</h1>

<?php
    if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){
        echo "<div class=\"home\">";
        echo "<a href = \"homepage.php\"><img src = \"res/IMG_GIF/home2.png\" alt=\"home.png\" width=\"10%\"/></a>";
        echo "</div>";
    }
    else{
        echo "<div class=\"home\">";
        echo "<a href = \"homepage.php\"><img src = \"res/IMG_GIF/home.png\" alt=\"home.png\" width=\"10%\"/></a>";
        echo "</div>";
    }
?> 

<div class="main">
    <h2>“Non si nasce con l’istinto della lettura, come si nasce con quello di mangiare o bere.”</h2>
    <p>
        La passione per la lettura non si può né insegnare né inoculare alle persone perché questa è una cosa molto personale.
        I libri, da quando è stata inventata la stampa, sono diventati i migliori mezzi di trasmissione della sapienza e della cultura e mantengono la stessa validità anche ai giorni nostri. 
        La lettura è un efficace metodo di apprendimento, uno stimolo alla capacità critica, un piacevole passatempo e fonte d'intense emozioni e sentimenti.
    </p>

    <p>
        Come dimostrano le ricerche degli psicologi, una cosa letta rimarrà sicuramente impressa nella nostra memoria più a lungo che non lo stesso messaggio visto in televisione o ascoltato alla radio. 
        Il testo scritto ci dà la possibilità di soffermarci su ciò che non ci è chiaro o su ciò che ci interessa. Con la lettura non siamo passivi nell'assimilazione delle informazioni ma ne siamo parte integrante. 
        I libri sono strumenti che formano intellettualmente le persone: in un buon testo si possono apprendere nuove nozioni, e si possono trovare risposte a molte domande.
    </p>

    <p>
        I libri sono quei mezzi che servono per comunicare i propri pensieri e il proprio modo di concepire la realtà e dato che gli uomini non sono stati creati tutti con lo stesso intelletto ma hanno un modo di pensare differente l'uno dall'altro, 
        è evidente che un libro è anche una fonte di riflessione, di critica oltre che di informazione.
        “Leggendo non cerchiamo idee nuove ma pensieri già da noi pensati, che acquisiscono sulla pagina un suggello di conferma.”
    </p>
    <h2>Ecco un libro che rappresenta quanto detto...</h2>
</div> 

<div class="libro">
    <p><strong>Fahrenheit 451</strong></p>

    <p>
        "Ecco perché un libro è un fucile carico nella casa del tuo vicino. Diamolo alle fiamme!<br/>
        Rendiamo inutile l'arma. Castriamo la mente dell'uomo." 
    </p>
    
    <img src = "res/IMG_GIF/copertina_451.jpg" alt="copertina.jpg" width="50%"/>
    
    <p>
        Per saperne di più clicca <a href="https://it.wikipedia.org/wiki/Fahrenheit_451">qui</a>
    </p>
</div>

<hr/>
<div class="crediti">
    <p>Fonte del testo <a href="https://www.skuola.net/temi-saggi-svolti/saggi-brevi/piacere-lettura-saggio-breve.html">qui</a></p>

    <p>
        Responsabili del sito: 
        <a href="mailto:privitera.1938225@studenti.uniroma1.it">privitera.1938225@studenti.uniroma1.it</a>    
        <a href="mailto:coluzzi.1912970@studenti.uniroma1.it">coluzzi.1912970@studenti.uniroma1.it</a>    
    </p>
</div>

</body>
</html>