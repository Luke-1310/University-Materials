<?php
    // header
    echo "<div class=\"header\">";
        
        // barra superiore di navigazione
        echo "<div class=\"nav\">";

            if(isset($_COOKIE["tema"]) && $_COOKIE["tema"] == "scuro"){

                echo "<link rel=\"stylesheet\" href=\"res/CSS/navbar_dark.css\" type=\"text/css\" />";

                //logo sito
                echo "<a href=\"homepage.php\"><img src=\"res/WEBSITE_MEDIA/d_website_logo.png\" alt=\"logo\" width=\"20%\"></a>";
                echo "<form action=\"res/PHP/tema.php\" method='post'>";

                    echo "<div class = \"sole_luna\">";

                        //mi invio anche la posizione attuale per coordinare l'header di tema.php
                        echo "<input type=\"hidden\" name=\"pagina\" value=\"$pagina_corrente\">"; 

                        echo "<button name=\"bottone_c\" type=\"submit\" value=\"chiaro\">";
                            echo "<i class=\"dark_mode material-icons\">light_mode</i>";
                        echo "</button>";
                        echo "<button name=\"bottone_s\" type=\"submit\" value=\"scuro\">";
                            echo "<i class=\"dark_mode material-icons\">dark_mode</i>";
                        echo "</button>";
                    echo "</div>";

                echo "</form>";
                
                if (isset($_SESSION['loggato']) && $_SESSION['loggato'] === 'true') {
                    echo "<a href=\"area_privata.php\"><i class=\"dark_mode material-icons\">person</i></a>";
                    echo "<a href=\"area_privata.php\">".$_SESSION['nome']."</a>";
                    echo "<a href=\"res/PHP/logout.php\"><i class=\"dark_mode material-icons\">logout</i></a>";
                    echo "<a href=\"res/PHP/logout.php\">LOGOUT</a>";
                }
                else{
                    echo "<a href=\"login.php\"><i class=\"dark_mode material-icons\">person</i></a>";
                    echo "<a href=\"login.php\">ACCEDI</a>";
                }
                echo "<a href=\"catalogo.php\"><i class=\"dark_mode material-icons\">book</i></a>";
                echo "<a href=\"catalogo.php\">CATALOGO</a>";
                
                if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){
                    echo "<a href=\"carrello.php\"><i class=\"dark_mode material-icons\">shopping_cart</i></a>";
                    echo "<a href=\"carrello.php\">CARRELLO</a>";
                }
                else{
                    echo "<a href=\"login.php\"><i class=\"dark_mode material-icons\">shopping_cart</i></a>";
                    echo "<a href=\"login.php\">REGISTRATI!</a>";
                }
            }

            else
            {
                echo "<link rel=\"stylesheet\" href=\"res/CSS/navbar.css\" type=\"text/css\" />";

                echo "<a href=\"homepage.php\"><img src=\"res/WEBSITE_MEDIA/website_logo.png\" alt=\"logo\" width=\"20%\"></a>";
                echo "<form action = \"res/PHP/tema.php\" method='post'>";

                    echo "<div class = \"sole_luna\">";

                        echo "<input type=\"hidden\" name=\"pagina\" value=\"$pagina_corrente\">";

                        echo "<button name=\"bottone_c\" type=\"submit\" value=\"chiaro\">";
                            echo "<i class=\"light_mode material-icons\">light_mode</i>";
                        echo "</button>";
                        echo "<button name=\"bottone_s\" type=\"submit\" value=\"scuro\">";
                            echo "<i class=\"light_mode material-icons\">dark_mode</i>";
                        echo "</button>";
                    echo "</div>";

                echo "</form>";

                if (isset($_SESSION['loggato']) && $_SESSION['loggato'] === 'true') {
                    echo "<a href=\"area_privata.php\"><i class=\"light_mode material-icons\">person</i></a>";
                    echo "<a href=\"area_privata.php\">".$_SESSION['nome']."</a>";
                    echo "<a href=\"res/PHP/logout.php\"><i class=\"light_mode material-icons\">logout</i></a>";
                    echo "<a href=\"res/PHP/logout.php\">LOGOUT</a>";
                }
                else{
                    echo "<a href=\"login.php\"><i class=\"light_mode material-icons\">person</i></a>";
                    echo "<a href=\"login.php\">ACCEDI</a>";
                }
                echo "<a href=\"catalogo.php\"><i class=\"light_mode material-icons\">book</i></a>";
                echo "<a href=\"catalogo.php\">CATALOGO</a>";

                if(isset($_SESSION['loggato']) && $_SESSION['loggato'] == true){
                    echo "<a href=\"carrello.php\"><i class=\"light_mode material-icons\">shopping_cart</i></a>";
                    echo "<a href=\"carrello.php\">CARRELLO</a>";
                }
                else{
                    echo "<a href=\"login.php\"><i class=\"light_mode material-icons\">shopping_cart</i></a>";
                    echo "<a href=\"login.php\">REGISTRATI!</a>";
                }

            }
        echo "</div>";

    echo "</div
    
    >";
?>