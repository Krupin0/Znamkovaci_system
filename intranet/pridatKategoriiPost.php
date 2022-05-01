<?php
    require_once("Db.php");
    $db = new Db();

    $rId = "#^\d+$#";
    $rVaha = "#^\d$#";
    $rNazev = "#^[a-žA-Ž\d\s\-\_\,]{1,30}$#";

    if(preg_match($rId, $_POST["predmetTridy"]) && preg_match($rNazev, $_POST["nazev"]) && (preg_match($rVaha, $_POST["vaha"]) || $_POST["vaha"] == "10")){
        $_POST["ucitel"] = $db->getUcitel($_POST["predmetTridy"]);
        $db->poslatKategorii($_POST["vaha"], $_POST["nazev"], $_POST["ucitel"], $_POST["predmetTridy"]);
    }
?>