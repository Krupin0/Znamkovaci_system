<?php
    require_once("Db.php");
    $db = new Db();
    $db->odebratZnamku($_POST["id"]);
?>