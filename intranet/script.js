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
    console.log(select.value);
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open("POST", "zobrazitStudenty.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                if(xmlhttp.responseText.startsWith("<s")){
                    var mySelect = xmlhttp.responseText;
                    document.getElementById("tridniSelect").innerHTML = xmlhttp.responseText;
                    document.getElementById("pridatZnamkuInfo").classList.add("skryt");
                    document.getElementById("pridatKategoriiInfo").classList.add("skryt");
                    document.getElementById("odebratZnamku").classList.add("skryt");
                    zobrazitStudentyTridni();
                }
                else{
                    document.getElementsByTagName("table")[0].innerHTML =  xmlhttp.responseText;
                    document.getElementById("odebratZnamku").classList.remove("skryt");
                    document.getElementById("pridatZnamkuInfo").classList.remove("skryt");
                    document.getElementById("pridatKategoriiInfo").classList.remove("skryt");
                    if(document.getElementById("tridyTridni") != null){
                        document.getElementById("tridyTridni").classList.add("skryt");
                    }
                }
                
                //console.log(xmlhttp.responseText);
            }
        }
        xmlhttp.send("id="+select.value);  
}
function zobrazitStudentyTridni(){
    select = document.getElementById("tridyTridni");
    console.log(select.value);
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open("POST", "zobrazitStudenty.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    document.getElementsByTagName("table")[0].innerHTML =  xmlhttp.responseText;
                }
                
                //console.log(xmlhttp.responseText);
            }
        xmlhttp.send("id="+select.value);  
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
    datum = document.getElementById("datum").value;
    zakId = document.getElementById("zak").value;
    kategorieId = document.getElementById("kategorie").value;
    predmetTridy = document.getElementById("tridy").value;
    latka = document.getElementById("latka").value;

    if(kontrolaZnamky(znamka, datum, zakId, kategorieId, predmetTridy, latka)){
        if(select.value != ""){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open("POST", "pridatZnamkuPost.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                //document.getElementById("text").innerHTML =  xmlhttp.responseText;
                zobrazitStudenty();
            }
        }
        xmlhttp.send("znamka="+znamka+ "&datum="+datum+"&zakId="+zakId+"&kategorieId="+kategorieId+"&predmetTridy="+predmetTridy+"&latka="+latka);
        let index = document.getElementById("zak").selectedIndex + 1;
        document.getElementById("zak").selectedIndex = index;
        if(document.getElementById("zak").value == ""){
            document.getElementById("zak").selectedIndex = 0;
        }
         
        }
    }
    else{
        alert("Špatně zadané vlastnosti známky");
    }

}
function pridatKategoriiPost(){
    //console.log("nova kategorie");
    vaha = document.getElementById("vaha").value;
    nazev = document.getElementById("nazevKategorie").value;
    ucitel_predmettridyid = document.getElementById("tridy").value;
    nazev = nazev.trim();
    vaha = vaha.trim();

    if(kontrolaKategorie(vaha, nazev, ucitel_predmettridyid)){
        console.log("spravne");
        if(select.value != ""){
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("POST", "pridatKategoriiPost.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    //document.getElementById("text").innerHTML =  xmlhttp.responseText;
                    //pridatKategorii();
                    //location.reload();
                    smazatInfo();
                }
            }
            xmlhttp.send("vaha="+vaha+ "&nazev="+nazev+"&predmetTridy="+ucitel_predmettridyid);
        }
    }
    else{
        alert("Špatně zadané vlastnosti kategorie");
    }
}
function kontrolaZnamky(znamka, datum, zakId, kategorieId, predmetTridyId, latka){
    znamka = znamka.trim();//
    datum = datum.trim();
    zakId = zakId.trim();//
    kategorieId = kategorieId.trim();//
    predmetTridyId = predmetTridyId.trim();//
    latka = latka.trim();//

    var rId = /^\d+$/;
    var rLatka = /^[a-žA-Ž\d\s\-\_\,]{1,30}$/;
    var rZnamka = /^[1-5]{1}$/;
    var rDatum = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$/;
     
    if(znamka == "" || datum == "" || zakId == "" || kategorieId == "" || predmetTridyId == "" || latka == ""){
        return false;
    }
    else if(!rId.test(zakId) || !rId.test(kategorieId) || !rId.test(predmetTridyId) || !rZnamka.test(znamka) ||!rDatum.test(datum) ||!rLatka.test(latka)){
        return false;
    }
    else{
        return true;
    }
}
function kontrolaKategorie(vaha, nazev, id){
    vaha = vaha.trim();
    nazev = nazev.trim();

    var rVaha = /^\d$/;
    var rNazev = /^[a-žA-Ž\d\s\-\_\,]{1,30}$/;
    var rId = /^\d+$/;

    if(vaha == "" || nazev == "" || id == ""){
        return false;
    }
    else if((!rVaha.test(vaha) && vaha != "10") || !rNazev.test(nazev) || !rId.test(id)){
        return false;
    }
    else{
        return true;
    }
}
function odebratZnamku(){
    if(document.getElementsByClassName("vybrano")[0] != null){
        //console.log(document.getElementsByClassName("vybrano")[0].id);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open("POST", "odebratZnamku.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                console.log("odstraněno");
                zobrazitStudenty();
            }
        }
        xmlhttp.send("id="+document.getElementsByClassName("vybrano")[0].id);
        smazatInfo();
    }
}
function csvExport(){
    window.location.href = "csvExport.php";
}
function jsonExport(){
    window.location.href = "jsonExport.php";
}