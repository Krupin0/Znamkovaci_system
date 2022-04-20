<?php

require_once("Db.php");
session_start();
$db = new Db();

if(empty($_POST)){
    header("Location: login.php");
    exit();
}

if($db->userExists($_POST["login"]) && $db->getPassword($_POST["login"]) == hash("ripemd160", $_POST["heslo"])){
    header("Location: main.php");
    $_SESSION["login"] = $_POST["login"];
    exit();
}
else{
    header("Location: login.php");
    $_SESSION["err"] = "Špatné přihlašovací údaje";
    exit();
}


?>