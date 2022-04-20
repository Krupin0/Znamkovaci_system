<?php
    require_once("Db.php");
    $db = new Db();
    
    //id, ucitel id, predmetTridy id
    //vaha, nazev

    echo("<input type=\"text\" id=\"nazevKategorie\" name=\"nazevKategorie\">");
    echo("<input type=\"text\" id=\"vaha\" name=\"vaha\">");
    echo("<button id=\"pridatKategorii\" onclick=\"pridatKategoriiPost()\">Přidat kategorii</button>");

?>