<?php
    require_once("Db.php");
    $db = new Db();
    function str_starts_with ( $haystack, $needle ) {
        return strpos( $haystack , $needle ) === 0;
    }
    #var_dump($_POST);
    if(str_starts_with($_POST["id"], "trida")){
        $info = $db->getTridniSkupiny(substr($_POST["id"], -2));
        #var_dump($info);
        echo("<select onchange=\"zobrazitStudentyTridni();smazatInfo()\" name=\"tridyTridni\" id=\"tridyTridni\">");
        foreach($info as $predmetTridy){
            echo("<option value=".$predmetTridy["idpredmetTridy"].">".$predmetTridy["predmet_zkratka"]."(skupina ".$db->getCisloSkupiny($predmetTridy["skupinaZaku_idSkupiny"]).")</option>");
        }
        echo("</select>");
        
    }
    else{
        $idZaku = $db->getZaciSKupiny($_POST["id"]);
        $zkratka = $db->getZkratka($_POST["id"]);
        $zaci = [];
        $znamky = [];
        
        if($_POST["id"])

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
    }
?>