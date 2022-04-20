<!DOCTYPE html>
<?php
    require_once("Db.php");
    session_start();
    $databaze = new Db(); 
?>
<phtml>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Intranet-login</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="stylesLogin.css">
</head>
<body>
    <div class="wrap">
        <h1>Přihlášení</h1>
        <form action="zpracovaniLogin.php" method="post">
            <div class="input">
                <input type="text" name="login" id="login">
                <label for="login">Uživatel</label>
            </div>
            <div class="input">
                <input type="password" name="heslo" id="heslo">
                <label for="heslo">Heslo</label>
            </div>
            <button type="submit">Přihlásit se</button>
        </form>
        <h2>
            <?php 
                if(isset($_SESSION["err"])){
                    echo($_SESSION["err"]);
                }
            ?>
        </h2>
    </div>
</body>
</phtml>