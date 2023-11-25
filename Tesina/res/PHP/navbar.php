<?php
    // header
    echo "<header>";
        
        // barra superiore di navigazione
        echo "<nav>";

            if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){

                echo "<link rel=\"stylesheet\" href=\"res/CSS/navbar_dark.css\" type=\"text/css\" />";

                //logo sito
                echo "<a href=\"homepage.php\"><img src=\"res/WEBSITE_MEDIA/d_website_logo.png\" alt=\"logo\" width=\"20%\"></a>";

                echo "<form action=\"res/PHP/tema.php\" method='POST'>";

                    //mi invio anche la posizione attuale per coordinare l'header di tema.php
                    echo "<input type=\"hidden\" name=\"pagina\" value=$pagina_corrente>"; 

                    echo "<button name=\"bottone_c\" type=\"submit\" value=\"chiaro\">";
                    echo "<i id=\"dark\" class=\"material-icons\">light_mode</i></button>";
                    echo "<button name=\"bottone_s\" type=\"submit\" value=\"scuro\">";
                    echo "<i id=\"dark\" class=\"material-icons\">dark_mode</i></button>";
                echo "</form>";
                
                if (isset($_SESSION['loggato']) && $_SESSION['loggato'] === 'true') {
                    echo "<a href=\"area_privata.php\"><i id=\"dark\" class=\"material-icons\">person</i></a>";
                    echo "<a href=\"area_privata.php\">".$_SESSION['nome']."</a>";
                    echo "<a href=\"res/PHP/logout.php\"><i id=\"dark\" class=\"material-icons\">logout</i></a>";
                    echo "<a href=\"res/PHP/logout.php\">LOGOUT</a>";
                }
                else{
                    echo "<a href=\"login.php\"><i id=\"dark\" class=\"material-icons\">person</i></a>";
                    echo "<a href=\"login.php\">ACCEDI</a>";
                }
                echo "<a href=\"catalogo.php\"><i id=\"dark\" class=\"material-icons\">book</i></a>";
                echo "<a href=\"catalogo.php\">CATALOGO</a>";
                echo "<a href=\"carrello.php\"><i id=\"dark\" class=\"material-icons\">shopping_cart</i></a>";
                echo "<a href=\"carrello.php\">CARRELLO</a>";
            }
            else
            {

                echo "<link rel=\"stylesheet\" href=\"res/CSS/navbar.css\" type=\"text/css\" />";

                echo "<a href=\"homepage.php\"><img src=\"res/WEBSITE_MEDIA/website_logo.png\" alt=\"logo\" width=\"20%\"></a>";

                echo "<form action = \"res/PHP/tema.php\" method='POST'>";

                    echo "<input type=\"hidden\" name=\"pagina\" value=\"$pagina_corrente\">";

                    echo "<button name=\"bottone_c\" type=\"submit\" value= \"chiaro\">";
                    echo "<i id=\"light\" class=\"material-icons\">light_mode</i></button>";
                    echo "<button name=\"bottone_s\" type=\"submit\" value= \"scuro\">";
                    echo "<i id=\"light\" class=\"material-icons\">dark_mode</i></button>";
                echo "</form>";

                if (isset($_SESSION['loggato']) && $_SESSION['loggato'] === 'true') {
                    echo "<a href=\"area_privata.php\"><i id=\"light\" class=\"material-icons\">person</i></a>";
                    echo "<a href=\"area_privata.php\">".$_SESSION['nome']."</a>";
                    echo "<a href=\"res/PHP/logout.php\"><i id=\"light\" class=\"material-icons\">logout</i></a>";
                    echo "<a href=\"res/PHP/logout.php\">LOGOUT</a>";
                }
                else{
                    echo "<a href=\"login.php\"><i id=\"light\" class=\"material-icons\">person</i></a>";
                    echo "<a href=\"login.php\">ACCEDI</a>";
                }
                echo "<a href=\"catalogo.php\"><i id=\"light\" class=\"material-icons\">book</i></a>";
                echo "<a href=\"catalogo.php\">CATALOGO</a>";
                echo "<a href=\"carrello.php\"><i id=\"light\" class=\"material-icons\">shopping_cart</i></a>";
                echo "<a href=\"carrello.php\">CARRELLO</a>";
            }
        echo "</nav>";

    echo "</header>";
?>