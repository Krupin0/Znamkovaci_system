<?php
    require_once("Db.php");
    $db = new Db();
    
    //id, ucitel id, predmetTridy id
    //vaha, nazev
    echo("<div class=\"atributy\">");
    echo("<label for=\"nazevKategorie\">Název kategorie:</label>");
    echo("<input type=\"text\" id=\"nazevKategorie\" name=\"nazevKategorie\">");
    echo("<label for=\"vaha\">Váha:</label>");
    echo("<input type=\"text\" id=\"vaha\" name=\"vaha\">");
    echo("<button id=\"pridatKategorii\" onclick=\"pridatKategoriiPost()\">Přidat kategorii</button>");
    echo("</div>");
?>