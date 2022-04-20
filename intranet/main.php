<?php
    require_once("Db.php");
    session_start();
    $db = new Db();

    $_SESSION["err"] = "";
    if(!isset($_SESSION["login"])){
        header("Location: login.php");
        exit();
    }

    $info = $db->getBasicInfo($_SESSION["login"]);

    if($info["role"] == "Žák"){
        $znamky = $db->getZnamkyZakPredmet($info["id"]);
        $prumer = $db->prumer($info["id"]);
    }      
    else if($info["role"] == "Učitel"){
        $skupiny=$db->getSkupinyUcitele($info["id"]);
    }
    #kontrola vstupu, next na tlacitko, custom datum, styly, odeslat znamky/kategorie, auto update pri odeslani
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
                       echo("<option value=".$skupina["idPredmetTridy"].">".$skupina["predmet_zkratka"]."-".$skupina["trida_nazev"]."(skupina ".$skupina["cisloSkupiny"].")</option>");
                   }
                echo("</select>");
                echo("<button id=\"pridatZnamkuInfo\" onclick=\"pridatZnamku()\">Přidat známku</button>");
                echo("<button id=\"pridatKategoriiInfo\" onclick=\"pridatKategorii()\">Přidat kategorii</button>");
            }
            ?>
            
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
    <script>
        if(document.getElementById("tridy") != null){
            zobrazitStudenty();
        }
        function znamkaInfo(id){
            for(var i = 0; i < document.getElementsByTagName("button").length;i++){
                document.getElementsByTagName("button")[i].classList.remove("vybrano");
            }
            document.getElementById(id).classList.add("vybrano");
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("POST", "znamkaInfo.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementById("text").innerHTML =  xmlhttp.responseText;
                }
            }

            xmlhttp.send("id="+id);
            console.log(id);
        }
        function zobrazitStudenty(){
            select = document.getElementById("tridy");
            if(select.value != ""){
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "zobrazitStudenty.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.onreadystatechange = function(){
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        document.getElementsByTagName("table")[0].innerHTML =  xmlhttp.responseText;
                    }
                }
                xmlhttp.send("id="+select.value);
                console.log(select.value);   
            }
        }
        function pridatZnamku(){
            select = document.getElementById("tridy");
            console.log("plus znamka");
            text = document.getElementById("text");
            if(select.value != ""){
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "pridatZnamkuInfo.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.onreadystatechange = function(){
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        text.innerHTML =  xmlhttp.responseText;
                    }
                }
                xmlhttp.send("id="+select.value);
                console.log(select.value);   
            }
        }
        function pridatKategorii(){
            select = document.getElementById("tridy");
            console.log("plus kategorie");
            text = document.getElementById("text");
            if(select.value != ""){
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "pridatKategoriiInfo.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.onreadystatechange = function(){
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        text.innerHTML =  xmlhttp.responseText;
                    }
                }
                xmlhttp.send("id="+select.value);
                console.log(select.value);   
            }
        }
        function smazatInfo(){
            document.getElementById("text").innerHTML = "";
        }
        function datZnamkuPost(){
            znamka = document.getElementById("znamka").value;
            datum = new Date().toISOString().split("T")[0];
            zakId = document.getElementById("zak").value;
            kategorieId = document.getElementById("kategorie").value;
            predmetTridy = document.getElementById("tridy").value;
            latka = document.getElementById("latka").value;
            console.log("nova znamka");
            console.log(znamka, datum, zakId, kategorieId, predmetTridy, latka);
            if(select.value != ""){
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "pridatZnamkuPost.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.onreadystatechange = function(){
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        document.getElementById("text").innerHTML =  xmlhttp.responseText;
                    }
                }
                xmlhttp.send("znamka="+znamka+ "&datum="+datum+"&zakId="+zakId+"&kategorieId="+kategorieId+"&predmetTridy="+predmetTridy+"&latka="+latka);
            }
        }
        function pridatKategoriiPost(){
            console.log("nova kategorie");
            vaha = document.getElementById("vaha").value;
            nazev = document.getElementById("nazevKategorie").value;
            ucitel_predmettridyid = document.getElementById("tridy").value;
            
            if(select.value != ""){
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "pridatKategoriiPost.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.onreadystatechange = function(){
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        document.getElementById("text").innerHTML =  xmlhttp.responseText;
                    }
                }
                xmlhttp.send("vaha="+vaha+ "&nazev="+nazev+"&predmetTridy="+ucitel_predmettridyid);
            }
        }
   </script>
</body>
</html>

