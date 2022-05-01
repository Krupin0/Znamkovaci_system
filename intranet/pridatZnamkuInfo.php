<?php
    require_once("Db.php");
    $db = new Db();
    $idZaku = $db->getZaciSKupiny($_POST["id"]);
    $zaci = [];
    $kategorie = $db->getKategorie($_POST["id"]);

    //id*, datum*, predmetTridy id *
    //zak id*, kategorie id*, poznamka*, znamka*


    foreach($idZaku as $id){
        array_push($zaci,$db->getJmenoZak($id));
    }

    echo("<div class=\"atributy\">");
    echo("<label for=\"zak\">Žák:</label>");
    echo("<select name=\"zak\" id=\"zak\">");
    $i = 0;
    foreach($zaci as $zak){
        echo("<option value=".$idZaku[$i].">".$zak["jmeno"]." ".$zak["prijmeni"]."</option>");
        $i = $i+1;
    }
    echo("</select>");
    echo("<label for=\"kategorie\">Kategorie:</label>");
    echo("<select name=\"kategorie\" id=\"kategorie\">");
    foreach($kategorie as $kategories){
        echo("<option value=".$kategories["idKategorieZnamek"].">".$kategories["nazev"].", váha ".$kategories["vaha"]."</option>");
    }
    echo("</select>");
    echo("<label for=\"znamka\">Známka:</label>");
    echo("<select name=\"znamka\" id=\"znamka\">");
    echo("<option value=1>1</option>");
    echo("<option value=2>2</option>");
    echo("<option value=3>3</option>");
    echo("<option value=4>4</option>");
    echo("<option value=5>5</option>");
    echo("</select>");
    echo("<label for=\"latka\">Látka:</label>");
    echo("<input type=\"text\" id=\"latka\" name=\"latka\">");
    echo("<label for=\"datum\">Datum:</label>");
    echo("<input type=\"date\" id=\"datum\" name=\"datum\">");
    echo("<button id=\"datZnamku\" onclick=\"datZnamkuPost()\">Dát známku</button>");
    echo("</div>");
    #var_dump($zaci, $kategorie, $idZaku);
    
?>