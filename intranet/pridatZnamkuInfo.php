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

    echo("<select name=\"zak\" id=\"zak\">");
    $i = 0;
    foreach($zaci as $zak){
        echo("<option value=".$idZaku[$i].">".$zak["jmeno"]." ".$zak["prijmeni"]."</option>");
        $i = $i+1;
    }
    echo("</select>");
    echo("<select name=\"kategorie\" id=\"kategorie\">");
    foreach($kategorie as $kategories){
        echo("<option value=".$kategories["idKategorieZnamek"].">".$kategories["nazev"].", váha ".$kategories["vaha"]."</option>");
    }
    echo("</select>");
    echo("<select name=\"znamka\" id=\"znamka\">");
    echo("<option value=1>1</option>");
    echo("<option value=2>2</option>");
    echo("<option value=3>3</option>");
    echo("<option value=4>4</option>");
    echo("<option value=5>5</option>");
    echo("</select>");
    echo("<input type=\"text\" id=\"latka\" name=\"latka\">");
    echo("<button id=\"datZnamku\" onclick=\"datZnamkuPost()\">Dát známku</button>");
    #var_dump($zaci, $kategorie, $idZaku);
    
?>