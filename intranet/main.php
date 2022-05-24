<?php
    session_start();
    require_once("Db.php");
    $db = new Db();

    $_SESSION["err"] = "";
    if(!isset($_SESSION["login"])){
        header("Location: login.php");
        exit();
    }

    $info = $db->getBasicInfo($_SESSION["login"]);
    $_SESSION["role"] = $info["role"];

    if($info["role"] == "Žák"){
        $znamky = $db->getZnamkyZakPredmet($info["id"]);
        $prumer = $db->prumer($info["id"]);
    }      
    else if($info["role"] == "Učitel"){
        $skupiny=$db->getSkupinyUcitele($info["id"]);
    }
?>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Intranet-známky</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='styles.css'>
</head>
<body>
    <div class="content">
        <div class="infoPanel">
            <div class="jmeno">
                <h1> <?php echo($info["jmeno"] . " " . $info["prijmeni"])?></h1>
                <h2> <?php echo($info["role"]); 
                if($info["trida"] != ""){
                    echo(" - " . $info["trida"]);
                } 
                ?> </h2>
            </div>

            <a href="odhlaseni.php">Odhlásit se</a>
        </div>
        <div class="znamky">
            <?php if($info["role"] == "Učitel"){
                echo("<select onchange=\"zobrazitStudenty();smazatInfo()\" name=\"tridy\" id=\"tridy\">");
                   foreach($skupiny as $skupina){
                       if($skupina["idPredmetTridy"] == ""){
                            $skupina["idPredmetTridy"] = "trida".$skupina["trida_nazev"];
                       }
                       echo("<option value=".$skupina["idPredmetTridy"].">".$skupina["predmet_zkratka"]."-".$skupina["trida_nazev"]."(skupina ".$skupina["cisloSkupiny"].")</option>");
                   }
                echo("</select>");
                echo("<button id=\"pridatZnamkuInfo\" onclick=\"pridatZnamku()\">Přidat známku</button>");
                echo("<button id=\"pridatKategoriiInfo\" onclick=\"pridatKategorii()\">Přidat kategorii</button>");
                echo("<button id=\"odebratZnamku\" onclick=\"odebratZnamku()\">OdebratZnamku</button>");
            }
            echo("<button id=\"csvExport\" onclick=\"csvExport()\">Exportovat do CSV</button>");
            echo("<button id=\"jsonExport\" onclick=\"jsonExport()\">Exportovat do JSON</button>");
            ?>
            <div id="tridniSelect"></div>

            <table>
                <?php if($info["role"] == "Žák"){
                    foreach($znamky as $key=>$value) :?>
                        <tr>
                            <th><p><?php echo($key)?></p><p class="prumer">Průměr: <?php echo($prumer[$key]); ?></p></th>
                            <?php foreach($znamky[$key] as $znamka) :?>
                                <th class="znamka"><button id=<?php echo($znamka["idZnamka"]) ?> onclick="znamkaInfo(this.id)"> <?php echo($znamka["znamka"])?></button></th>
                            <?php endforeach;?>
                        </tr>
                    <?php endforeach;}?>
            </table>

        </div>
        <div class="infoOZnamce">
            <h1>Známka</h1>
            <div id="text"></div>
        </div>
        
    </div>
    <script src="script.js"></script>
</body>
</html>

