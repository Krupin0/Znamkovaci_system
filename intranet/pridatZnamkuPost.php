<?php
    #var_dump($_POST);
    require_once("Db.php");
    $db = new Db();

    $rId = "#^\d+$#";
    $rZnamka = "#^[1-5]{1}$#";
    $rDatum = "#^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$#";
    $rLatka = "#^[a-žA-Ž\d\s\-\_\,]{1,30}$#";

    if(preg_match($rId, $_POST["zakId"]) && preg_match($rId, $_POST["kategorieId"]) && preg_match($rId, $_POST["predmetTridy"]) && preg_match($rZnamka, $_POST["znamka"]) && preg_match($rDatum, $_POST["datum"]) && preg_match($rLatka, $_POST["latka"])){
        $db->poslatZnamku($_POST["znamka"], $_POST["datum"], $_POST["zakId"], $_POST["kategorieId"], $_POST["predmetTridy"], $_POST["latka"]);
    }
?>