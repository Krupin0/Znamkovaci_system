<?php
    #var_dump($_POST);
    require_once("Db.php");
    $db = new Db();

    $db->poslatZnamku($_POST["znamka"], $_POST["datum"], $_POST["zakId"], $_POST["kategorieId"], $_POST["predmetTridy"], $_POST["latka"]);
?>