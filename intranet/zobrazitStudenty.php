<?php
    require_once("Db.php");
    $db = new Db();
    $idZaku = $db->getZaciSKupiny($_POST["id"]);
    $zkratka = $db->getZkratka($_POST["id"]);
    $zaci = [];
    $znamky = [];
    

    foreach($idZaku as $id){
        array_push($zaci,$db->getJmenoZak($id));
    }
    foreach($idZaku as $zakId){
        array_push($znamky, $db->getZnamky($zakId, $_POST["id"]));
    }
    #var_dump($znamky, $zaci);
    
        
        $i = 0;
            foreach($znamky as $zaciZnamky){
                echo("<tr><th><p>".$zaci[$i]["jmeno"]. " " . $zaci[$i]["prijmeni"] . "</p><p>Průměr: ". $db->prumerZakaPredmet($idZaku[$i], $zkratka)."</p></th>");
                    foreach($zaciZnamky as $znamka){
                        echo("<th class=\"znamka\"><button id =". $znamka["idZnamka"]. " onclick=\"znamkaInfo(this.id)\">".$znamka["znamka"]."</button></th>");
                    }
                    echo("</tr>");
                    $i = $i+1;
                }
    
        
    
?>