<?php
    require_once("Db.php");
    $db = new Db();
    $znamka = $db->getZnamkaInfo($_POST["id"]);
    $kategorie = $db->getKategorieZnamky($znamka["idZnamka"]);
    $ucitel = $db->getUcitelJmeno($kategorie["ucitel_ucet_idLogin"]);

    $latka = $znamka["poznamka"];
    $ucitel = $ucitel["jmeno"]." ".$ucitel["prijmeni"];
    $datum = $znamka["datum"];
    $kategorie = $kategorie["nazev"].", váha ".$kategorie["vaha"];

    echo("<p>Látka: ".$latka."</p><p>Učitel: ".$ucitel."</p><p>Datum: ".$datum."</p><p>Kategorie: ".$kategorie);
?>