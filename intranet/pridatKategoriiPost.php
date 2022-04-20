<?php
    require_once("Db.php");
    $db = new Db();
    $_POST["ucitel"] = $db->getUcitel($_POST["predmetTridy"]);
    
    var_dump($_POST);
?>