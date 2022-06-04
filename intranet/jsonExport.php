<?php
    session_start();
    //var_dump($_SESSION);
    require_once("Db.php");
    $db = new Db();
    $filename = "export_znamek.json";
    if($_SESSION["role"] == "Žák"){
        $query = $db->exportCsvZak($_SESSION["login"]); 
    }
    else{ 
        $query = $db->exportCsvUcitel($_SESSION["tridniTrida"]);
    }
    //write to json file
    $file = fopen($filename, 'w');
    fwrite($file, json_encode($query, JSON_UNESCAPED_UNICODE));
    fclose($file);
    //echo json_encode($query);
    if (file_exists($filename)) {
        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-type: text/json; charset=UTF-8');
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;
    }
?>