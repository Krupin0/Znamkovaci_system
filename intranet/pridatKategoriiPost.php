<?php
    require_once("Db.php");
    $db = new Db();
    $_POST["ucitel"] = $db->getUcitel($_POST["predmetTridy"]);

    $db->poslatKategorii($_POST["vaha"], $_POST["nazev"], $_POST["ucitel"], $_POST["predmetTridy"]);
?>