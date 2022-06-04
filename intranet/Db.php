<?php

class Db{
    private $connection;

    public function __construct(){
        $this->connection = new PDO("mysql:host=localhost;port=3306;dbname=mydb;charset=utf8mb4", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_MULTI_STATEMENTS => false]);
    }

    public function userExists(string $login){
        $stmt = $this->connection->prepare("SELECT * FROM ucet WHERE ucet.login = ?");
        if(!$stmt->execute([$login]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if($stmt->fetch() == ""){
            return false;
        }
        else{
            return true;
        }
    }

    public function getPassword(string $login){
        $stmt = $this->connection->prepare("SELECT ucet.heslo FROM ucet WHERE ucet.login = ?");
        if(!$stmt->execute([$login]))
            throw new Exception("Dotaz se neprovedl");
        return $stmt->fetch()["heslo"];
    }





    public function getBasicInfo(string $login){
        $stmt = $this->connection->prepare("SELECT * FROM ucet WHERE ucet.login = ?");
        if(!$stmt->execute([$login]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        $data = $stmt->fetch();
        $id = $data["idLogin"];
        $jmeno = $data["jmeno"];
        $prijmeni = $data["prijmeni"];
        $role = "";
        $trida = "";

        $stmt = $this->connection->prepare("SELECT * FROM zak WHERE zak.ucet_idLogin = ?");
        if(!$stmt->execute([$id]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $data = $stmt->fetch();
        if(!$data){
            $role = "Učitel";
        }
        else{
            $role = "Žák";
            $trida = $data["trida_nazev"];
        }
        return array("jmeno"=>$jmeno, "prijmeni"=>$prijmeni, "role"=>$role, "trida"=>$trida, "id"=>$id);
    }


    public function getZnamkyZakPredmet($id){
        $predmety = Db::getPredmetyZaka($id);
        $znamky = [];
        $stmt = $this->connection->prepare("SELECT * FROM znamka WHERE znamka.zak_ucet_idLogin = ? and znamka.predmetTridy_idpredmetTridy = any (SELECT predmettridy.idpredmetTridy FROM predmettridy WHERE predmettridy.predmet_zkratka = (SELECT predmet.zkratka FROM predmet where predmet.nazev = ?)) ORDER BY znamka.datum");
        foreach($predmety as $predmet){
            if(!$stmt->execute([$id, $predmet]))
                throw new Exception("Dotaz se neprovedl");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
            $znamky[$predmet]=$stmt->fetchAll();
        }
        
        #var_dump($znamky);
        return $znamky;
    }
    public function getZnamkaInfo($id){
        $stmt = $this->connection->prepare("SELECT * from znamka WHERE znamka.idZnamka = ?");
        if(!$stmt->execute([$id]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        $znamka = $stmt->fetch();
        return $znamka;
    }

    public function getPredmetyZaka($id){
        $stmt = $this->connection->prepare("SELECT predmet.nazev from predmet WHERE predmet.zkratka = ANY (SELECT predmettridy.predmet_zkratka FROM predmettridy WHERE predmettridy.skupinaZaku_idSkupiny in(SELECT skupinazaku_has_zak.skupinaZaku_idSkupiny FROM skupinazaku_has_zak WHERE skupinazaku_has_zak.zak_ucet_idLogin = ?)) ORDER by predmet.nazev");
        if(!$stmt->execute([$id]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        $predmety = $stmt->fetchAll();
        return array_column($predmety, "nazev");
    }

    public function getSkupinyUcitele($id){
        $stmt = $this->connection->prepare("SELECT skupinaZaku.idSkupiny, skupinazaku.trida_nazev, skupinazaku.cisloSkupiny, predmettridy.predmet_zkratka, predmettridy.idPredmetTridy FROM skupinazaku, predmettridy WHERE skupinazaku.idSkupiny = predmettridy.skupinaZaku_idSkupiny and skupinazaku.idSkupiny in (SELECT predmettridy.skupinaZaku_idSkupiny FROM predmettridy WHERE predmettridy.ucitel_ucet_idLogin = ?) and predmettridy.ucitel_ucet_idLogin = ?");
        if(!$stmt->execute([$id, $id]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        $predmety = $stmt->fetchAll();
        if(Db::getTridniTrida($id) != false){
            array_push($predmety, Db::getTridniTrida($id));
        }
        #var_dump($predmety);
        return $predmety;
    }

    private function getTridniTrida($id){
        $stmt = $this->connection->prepare("SELECT trida.nazev from trida WHERE trida.ucitel_ucet_idLogin = ?");
        if(!$stmt->execute([$id]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $trida = $stmt->fetch();
        if($trida != false){
            $trida = array("trida_nazev"=> $trida["nazev"], "cisloSkupiny" => "0", "predmet_zkratka"=>"třídní");
        }
        
        return($trida);
    }
    public function getTridniSkupiny($nazevTridy){
        $stmt = $this->connection->prepare("SELECT * FROM predmettridy WHERE predmettridy.skupinaZaku_idSkupiny = any(SELECT skupinazaku.idSkupiny FROM skupinazaku WHERE skupinazaku.trida_nazev = ?)");
        if(!$stmt->execute([$nazevTridy]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return($stmt->fetchAll());
    }
    public function getCisloSkupiny($id){
        $stmt = $this->connection->prepare("SELECT skupinazaku.cisloSkupiny FROM skupinazaku WHERE skupinazaku.idSkupiny = ?");
        if(!$stmt->execute([$id]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return($stmt->fetch()["cisloSkupiny"]);
    }

    public function getKategorieZnamky($id){
        $stmt = $this->connection->prepare("SELECT * from kategorieZnamek WHERE kategorieZnamek.idKategorieZnamek = any (SELECT znamka.kategorieZnamek_idKategorieZnamek from znamka WHERE znamka.idZnamka = ?)");
        if(!$stmt->execute([$id]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }

    public function getUcitelJmeno($id){
        $stmt = $this->connection->prepare("SELECT ucet.jmeno, ucet.prijmeni from ucet WHERE ucet.idLogin = ?");
        if(!$stmt->execute([$id]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }

    public function prumer($idZaka){
        #pole[nazevPredmetu->prumer]
        $vazeneZnamky = [];


        $znamky = Db::getZnamkyZakPredmet($idZaka);
        foreach($znamky as $key=>$value){
            $vynasobenaZnamka = 0;
            $soucetVah = 0;
            foreach($value as $znamka){
                $vynasobenaZnamka = $vynasobenaZnamka + ($znamka["znamka"]*Db::getKategorieZnamky($znamka["idZnamka"])["vaha"]);
                $soucetVah = $soucetVah + Db::getKategorieZnamky($znamka["idZnamka"])["vaha"];
            }
            if($vynasobenaZnamka == 0){
                $vazeneZnamky[$key] = "";
            }
            else{
                $vazeneZnamky[$key] = round(($vynasobenaZnamka/$soucetVah), 2, PHP_ROUND_HALF_UP);
            }
        }
        return $vazeneZnamky;
    }

    public function getZaciSKupiny($id){
        $stmt = $this->connection->prepare("SELECT skupinaZaku_has_zak.zak_ucet_idLogin from skupinaZaku_has_zak, ucet WHERE skupinaZaku_has_zak.zak_ucet_idLogin = ucet.idLogin and skupinaZaku_has_zak.skupinaZaku_idSkupiny = any (SELECT predmetTridy.skupinaZaku_idSkupiny from predmetTridy where predmetTridy.idPredmetTridy = ?) ORDER BY ucet.prijmeni ASC");
        if(!$stmt->execute([$id]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return array_column($stmt->fetchAll(), "zak_ucet_idLogin");
    }
    public function getJmenoZak($id){
        $stmt = $this->connection->prepare("SELECT ucet.jmeno, ucet.prijmeni from ucet WHERE ucet.idLogin = ?");
        if(!$stmt->execute([$id]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }
    public function getZnamky($idZak, $idPredmetTridy){
        $stmt = $this->connection->prepare("SELECT * from znamka WHERE znamka.zak_ucet_idLogin = ? and znamka.predmetTridy_idPredmetTridy = ?");
        if(!$stmt->execute([$idZak, $idPredmetTridy]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }
    public function prumerZakaPredmet($id, $zkratka){
        $stmt = $this->connection->prepare("SELECT predmet.nazev from predmet WHERE predmet.zkratka = ?");
        if(!$stmt->execute([$zkratka]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        

        return Db::prumer($id)[$stmt->fetch()["nazev"]];
    }
        public function getZkratka($id){
        $stmt = $this->connection->prepare("SELECT predmetTridy.predmet_zkratka from predmetTridy WHERE predmetTridy.idPredmetTridy = ?");
        if(!$stmt->execute([$id]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        

        return $stmt->fetch()["predmet_zkratka"];
    }
    public function getKategorie($id){
        $stmt = $this->connection->prepare("SELECT * from kategorieznamek WHERE kategorieznamek.predmetTridy_idpredmetTridy = ?");
        if(!$stmt->execute([$id])) 
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }
    public function getUcitel(int $idPredmetTridy){
        $stmt = $this->connection->prepare("SELECT predmetTridy.ucitel_ucet_idLogin from predmetTridy WHERE predmetTridy.idpredmetTridy = ?");
        if(!$stmt->execute([$idPredmetTridy])) 
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch()["ucitel_ucet_idLogin"];
    }
    public function poslatZnamku($znamka, $datum, $zakId, $kategorieId, $predmetTridyId, $latka){
        $stmt = $this->connection->prepare("INSERT INTO znamka VALUES(null, ?, ?, ?, ?, ?, ?)");
        if(!$stmt->execute([$znamka, $datum ,$zakId, $kategorieId, $predmetTridyId, $latka]))
            throw new Exception("Dotaz se neprovedl");
    }
    public function poslatKategorii(int $vaha, string $nazev, int $ucitel, int $predmetTridy){
        $stmt = $this->connection->prepare("INSERT INTO kategorieZnamek VALUES(null, ?, ?, ?, ?)");
        if(!$stmt->execute([$vaha, $nazev, $ucitel, $predmetTridy]))
            throw new Exception("Dotaz se neprovedl");
    }
    public function odebratZnamku($idZnamka){
        $stmt = $this->connection->prepare("DELETE FROM znamka WHERE znamka.idZnamka=?");
        if(!$stmt->execute([$idZnamka]))
            throw new Exception("Dotaz se neprovedl");
    }
    public function exportCsvZak($logZak){
        $stmt = $this->connection->prepare("SELECT znamka.znamka AS \"znamka\", znamka.datum AS \"datum\", znamka.poznamka AS \"latka\", kategorieznamek.nazev AS \"kategorie\", ucet.jmeno AS \"ucitel_jmeno\", ucet.prijmeni AS \"ucitel_prijmeni\", kategorieznamek.vaha AS \"vaha\", predmettridy.predmet_zkratka AS \"predmet\" FROM znamka, kategorieznamek, ucet, predmettridy WHERE znamka.zak_ucet_idLogin = (SELECT zak.ucet_idLogin FROM zak, ucet WHERE ucet.idLogin = zak.ucet_idLogin AND ucet.login = ?) AND kategorieznamek.idKategorieZnamek = znamka.kategorieZnamek_idKategorieZnamek AND kategorieznamek.ucitel_ucet_idLogin = ucet.idLogin AND predmetTridy.idpredmetTridy = znamka.predmetTridy_idpredmetTridy GROUP BY znamka.idZnamka ORDER BY predmettridy.predmet_zkratka, znamka.datum");
        if(!$stmt->execute([$logZak]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }   
    public function exportCsvUcitel($predmetTridy){
        $stmt = $this->connection->prepare("SELECT ucet.jmeno AS \"zak_jmeno\", ucet.prijmeni AS \"zak_prijmeni\", znamka.znamka AS \"znamka\", znamka.datum AS \"datum\", znamka.poznamka AS \"latka\", kategorieznamek.vaha AS \"vaha\", kategorieznamek.nazev AS \"kategorie\", (SELECT ucet.jmeno FROM ucet WHERE predmettridy.ucitel_ucet_idLogin = ucet.idLogin) AS \"ucitel_jmeno\", predmettridy.predmet_zkratka AS \"predmet\", (SELECT ucet.prijmeni FROM ucet WHERE predmettridy.ucitel_ucet_idLogin = ucet.idLogin) AS \"ucitel_prijmeni\" FROM ucet, znamka, kategorieznamek, predmettridy WHERE predmettridy.idpredmetTridy = znamka.predmetTridy_idpredmetTridy AND znamka.kategorieZnamek_idKategorieZnamek = kategorieznamek.idKategorieZnamek AND ucet.idLogin = znamka.zak_ucet_idLogin AND predmetTridy.idpredmetTridy = ? GROUP BY znamka.idZnamka ORDER BY ucet.prijmeni, znamka.datum");
        if(!$stmt->execute([$predmetTridy]))
            throw new Exception("Dotaz se neprovedl");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }  
}
